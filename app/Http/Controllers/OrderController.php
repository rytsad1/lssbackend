<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Order\CreateRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\OrderItem;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::with([
            'user',
            'orderHistory',
            'orderType',
            'orderStatus',
            'orderItems.item', // užkraunam susijusius daiktus
        ])->get());
    }

    public function store(CreateRequest $request)
    {
        $order = Order::create($request->validated());
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderHistory',
            'orderType',
            'orderStatus',
            'orderItems.item', // įtraukiam item informaciją
        ]);

        return new OrderResource($order);
    }

    public function update(UpdateRequest $request, Order $order)
    {
        $order->update($request->validated());
        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }

    public function createFullOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:item,id_Item',
            'items.*.quantity' => 'required|integer|min:1',
            'type' => 'required|exists:ordertype,id_OrderType',
            'comment' => 'nullable|string',
        ]);

        $user = Auth::guard('api')->user();

        return DB::transaction(function () use ($validated, $user) {
            // Sukuriam užsakymą
            $order = Order::create([
                'Date' => now(),
                'State' => 1,
                'fkUserid_User' => $user->id_User,
                'fkOrderTypeid_OrderType' => $validated['type'],
                'fkOrderStatusid_OrderStatus' => 3, // Waiting
            ]);

            // Sukuriam OrderItem įrašus
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'fkOrderid_Order' => $order->id_Order,
                    'fkItemid_Item' => $item['item_id'],
                    'Quantity' => $item['quantity'],
                ]);
            }

            // Sukuriam OrderHistory įrašą
            $history = OrderHistory::create([
                'Date' => now(),
                'fkOrderid_Order' => $order->id_Order,
                'PerformedByUserid' => $user->id_User,
                'Action' => 'created',
                'Comment' => $validated['comment'] ?? 'Užsakymas pateiktas',
            ]);

            // Priskiriam OrderHistory ID į užsakymą
            $order->fkOrderHistoryid_OrderHistory = $history->id_OrderHistory;
            $order->save();

            return response()->json(['message' => 'Užsakymas pateiktas', 'order_id' => $order->id_Order], 201);
        });
    }
}
