<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Resources\OrderItemResource;
use App\Http\Requests\OrderItem\CreateRequest;
use App\Http\Requests\OrderItem\UpdateRequest;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItemResource::collection(OrderItem::all());
    }

    public function store(CreateRequest $request)
    {
        $orderItem = OrderItem::create($request->validated());
        return new OrderItemResource($orderItem);
    }

    public function show(OrderItem $orderItem)
    {
        return new OrderItemResource($orderItem);
    }

    public function update(UpdateRequest $request, OrderItem $orderItem)
    {
        $orderItem->update($request->validated());
        return new OrderItemResource($orderItem);
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->noContent();
    }
}
