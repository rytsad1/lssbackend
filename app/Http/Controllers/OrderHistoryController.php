<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use Illuminate\Http\Request;
use App\Http\Resources\OrderHistoryResource;
use App\Http\Requests\OrderHistory\CreateRequest;
use App\Http\Requests\OrderHistory\UpdateRequest;

class OrderHistoryController extends Controller
{
    public function index()
    {
        return OrderHistoryResource::collection(OrderHistory::all());
    }

    public function store(CreateRequest $request)
    {
        $orderHistory = OrderHistory::create($request->validated());
        return new OrderHistoryResource($orderHistory);
    }

    public function show(OrderHistory $orderHistory)
    {
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
