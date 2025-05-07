<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Resources\OrderStatusResource;
use App\Http\Requests\OrderStatus\CreateRequest;
use App\Http\Requests\OrderStatus\UpdateRequest;

class OrderStatusController extends Controller
{
    public function index()
    {
        return OrderStatusResource::collection(OrderStatus::all());
    }

    public function store(CreateRequest $request)
    {
        $status = OrderStatus::create($request->validated());
        return new OrderStatusResource($status);
    }

    public function show(OrderStatus $orderstatus)
    {
        return new OrderStatusResource($orderstatus);
    }

    public function update(UpdateRequest $request, OrderStatus $orderstatus)
    {
        $orderstatus->update($request->validated());
        return new OrderStatusResource($orderstatus);
    }

    public function destroy(OrderStatus $orderstatus)
    {
        $orderstatus->delete();
        return response()->noContent();
    }
}
