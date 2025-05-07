<?php

namespace App\Http\Controllers;

use App\Models\OrderType;
use Illuminate\Http\Request;
use App\Http\Resources\OrderTypeResource;
use App\Http\Requests\OrderType\CreateRequest;
use App\Http\Requests\OrderType\UpdateRequest;

class OrderTypeController extends Controller
{
    public function index()
    {
        return OrderTypeResource::collection(OrderType::all());
    }

    public function store(CreateRequest $request)
    {
        $orderType = OrderType::create($request->validated());
        return new OrderTypeResource($orderType);
    }

    public function show(OrderType $orderType)
    {
        return new OrderTypeResource($orderType);
    }

    public function update(UpdateRequest $request, OrderType $orderType)
    {
        $orderType->update($request->validated());
        return new OrderTypeResource($orderType);
    }

    public function destroy(OrderType $orderType)
    {
        $orderType->delete();
        return response()->noContent();
    }
}

