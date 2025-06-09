<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Models\OrderItem;
use App\Models\OrderHistory;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TemporaryIssueLog;

class OrderController extends Controller
{
    public function createFullOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:item,id_Item',
            'items.*.quantity' => 'required|integer|min:1',
            'target_user_id' => 'nullable|exists:user,id_User',
            'comment' => 'nullable|string',
        ]);

        $user = Auth::guard('api')->user();
        $isWarehouse = $user->isWarehouseManager();
        $targetUserId = $isWarehouse ? $validated['target_user_id'] ?? null : $user->id_User;

        return DB::transaction(function () use ($validated, $user, $isWarehouse, $targetUserId) {
            $status = $isWarehouse ? 2 : 3;

            $order = Order::create([
                'Date' => now(),
                'State' => 1,
                'fkUserid_User' => $targetUserId,
                'fkOrderTypeid_OrderType' => 2,
                'fkOrderStatusid_OrderStatus' => $status,
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'fkOrderid_Order' => $order->id_Order,
                    'fkItemid_Item' => $item['item_id'],
                    'Quantity' => $item['quantity'],
                ]);

                if ($isWarehouse) {
                    $itemModel = Item::find($item['item_id']);
                    if ($itemModel->Quantity < $item['quantity']) {
                        throw new \Exception("Nepakanka kiekio daiktui: {$itemModel->Name}");
                    }
                    $itemModel->Quantity -= $item['quantity'];
                    $itemModel->save();

                    if ($targetUserId !== null) {
                        TemporaryIssueLog::create([
                            'fkItemid_Item' => $item['item_id'],
                            'fkUserid_User' => $targetUserId,
                            'IssuedDate' => now(),
                            'ReturnedDate' => null,
                            'Comment' => 'Sandėlininkas išdavė daiktą',
                            'Quantity' => $item['quantity'],
                        ]);
                    }
                }
            }

            OrderHistory::create([
                'Date' => now(),
                'fkOrderid_Order' => $order->id_Order,
                'PerformedByUserid' => $user->id_User,
                'Action' => $isWarehouse ? 'issued' : 'created',
                'Comment' => $validated['comment'] ?? ($isWarehouse ? 'Sandėlininkas išdavė daiktus' : 'Užsakymas pateiktas'),
            ]);

            return response()->json(['message' => 'Užsakymas pateiktas', 'order_id' => $order->id_Order], 201);
        });
    }

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        $query = Order::with(['user', 'orderHistory', 'orderType', 'orderStatus', 'orderItems.item']);

        if ($request->query('status') === 'waiting') {
            if (!$user->isWarehouseManager()) {
                return response()->json(['message' => 'Leidimas atmestas.'], 403);
            }
            $query->where('fkOrderStatusid_OrderStatus', 3);
        } elseif (!$user->isWarehouseManager()) {
            $query->where('fkUserid_User', $user->id_User);
        }

        return OrderResource::collection($query->get());
    }

    public function approve(Order $order)
    {
        return $this->handleOrderAction($order, 'approved', 2, true);
    }

    public function reject(Order $order)
    {
        return $this->handleOrderAction($order, 'rejected', 4, false);
    }

    protected function handleOrderAction(Order $order, string $action, int $statusId, bool $shouldDeduct)
    {
        $user = Auth::guard('api')->user();

        if (!$user->isWarehouseManager()) {
            return response()->json(['message' => 'Leidimas atmestas.'], 403);
        }

        if ($order->fkOrderStatusid_OrderStatus !== 3) {
            return response()->json(['message' => 'Užsakymas jau buvo apdorotas.'], 400);
        }

        return DB::transaction(function () use ($order, $action, $statusId, $user, $shouldDeduct) {
            $order->fkOrderStatusid_OrderStatus = $statusId;
            $order->save();

            if ($shouldDeduct) {
                foreach ($order->orderItems as $item) {
                    $stockItem = $item->item;
                    if ($stockItem->Quantity < $item->Quantity) {
                        throw new \Exception("Nepakanka kiekio daiktui: {$stockItem->Name}");
                    }
                    $stockItem->Quantity -= $item->Quantity;
                    $stockItem->save();

                    TemporaryIssueLog::create([
                        'fkItemid_Item' => $item->fkItemid_Item,
                        'fkUserid_User' => $order->fkUserid_User,
                        'IssuedDate' => now(),
                        'ReturnedDate' => null,
                        'Comment' => 'Automatinis įrašas iš užsakymo',
                        'Quantity' => $item->Quantity,
                    ]);
                }
            }

            if ($order->orderHistory) {
                $order->orderHistory->update([
                    'Action' => $action,
                    'Comment' => $action === 'approved' ? 'Užsakymas patvirtintas' : 'Užsakymas atmestas',
                    'PerformedByUserid' => $user->id_User,
                    'Date' => now(),
                ]);
            }

            return response()->json(['message' => "Užsakymas $action."]);
        });
    }

    public function userTemporaryIssues()
    {
        $user = Auth::guard('api')->user();

        $logs = TemporaryIssueLog::with('item')
            ->where('fkUserid_User', $user->id_User)
            ->whereNull('ReturnedDate')
            ->get();

        return response()->json(['data' => $logs]);
    }

    public function returnIssuedItem(Request $request, $id)
    {
        $user = Auth::guard('api')->user();
        $log = TemporaryIssueLog::where('id_TemporaryIssueLog', $id)
            ->where('fkUserid_User', $user->id_User)
            ->whereNull('ReturnedDate')
            ->first();

        if (!$log) {
            return response()->json(['message' => 'Įrašas nerastas arba jau grąžintas.'], 404);
        }

        return DB::transaction(function () use ($log) {
            $item = $log->item;
            $item->Quantity += $log->Quantity;
            $item->save();

            $log->ReturnedDate = now();
            $log->save();

            return response()->json(['message' => 'Daiktas sėkmingai grąžintas.']);
        });
    }
}
