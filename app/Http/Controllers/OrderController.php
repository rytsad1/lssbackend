<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Order\CreateRequest;
use App\Http\Requests\Order\UpdateRequest;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    public function store(CreateRequest $request)
    {
        $order = Order::create($request->validated());
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
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
}
