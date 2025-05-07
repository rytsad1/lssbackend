<?php

namespace App\Http\Controllers;

use App\Models\BillOfLading;
use Illuminate\Http\Request;
use App\Http\Resources\BillOfLadingResource;
use Illuminate\Http\Response;

class BillOfLadingController extends Controller
{
    public function index()
    {
        return BillOfLadingResource::collection(BillOfLading::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Date' => ['required', 'date'],
            'Sum' => ['required', 'numeric'],
            'Type' => ['required', 'integer'],
            'fkOrderid_Order' => ['required', 'integer'],
        ]);

        $billOfLading = BillOfLading::create($validated);

        return response(new BillOfLadingResource($billOfLading), 201);
    }

    public function show(BillOfLading $billOfLading)
    {
        return new BillOfLadingResource($billOfLading);
    }

    public function update(Request $request, BillOfLading $billOfLading)
    {
        $validated = $request->validate([
            'Date' => ['sometimes', 'date'],
            'Sum' => ['sometimes', 'numeric'],
            'Type' => ['sometimes', 'integer'],
            'fkOrderid_Order' => ['sometimes', 'integer'],
        ]);

        $billOfLading->update($validated);

        return new BillOfLadingResource($billOfLading);
    }

    public function destroy(BillOfLading $billOfLading)
    {
        $billOfLading->delete();

        return response()->noContent();
    }
}
