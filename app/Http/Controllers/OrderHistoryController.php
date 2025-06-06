<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use Illuminate\Http\Request;
use App\Http\Resources\OrderHistoryResource;
use App\Http\Requests\OrderHistory\CreateRequest;
use App\Http\Requests\OrderHistory\UpdateRequest;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();

        if ($user->isWarehouseManager()) {
            return OrderHistoryResource::collection(
                OrderHistory::with(['order.orderItems.item', 'order.user', 'order.orderType', 'order.orderStatus', 'performedBy'])->get()
            );
        }

        return OrderHistoryResource::collection(
            OrderHistory::with(['order.orderItems.item', 'order.user', 'order.orderType', 'order.orderStatus', 'performedBy'])
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('fkUserid_User', $user->id_User);
                })
                ->get()
        );
    }

    public function store(CreateRequest $request)
    {
        $orderHistory = OrderHistory::create($request->validated());
        return new OrderHistoryResource($orderHistory);
    }

    public function show(OrderHistory $orderHistory)
    {
        $orderHistory->load([
            'order.orderItems.item',
            'order.user',
            'order.orderType',
            'performedBy',
        ]);

        return new OrderHistoryResource($orderHistory);
    }

    public function update(UpdateRequest $request, OrderHistory $orderHistory)
    {
        $orderHistory->update($request->validated());
        return new OrderHistoryResource($orderHistory);
    }

    public function destroy(OrderHistory $orderHistory)
    {
        $orderHistory->delete();
        return response()->noContent();
    }
}
