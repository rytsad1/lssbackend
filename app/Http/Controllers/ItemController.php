<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Resources\ItemResource;
use App\Http\Requests\Item\CreateRequest;
use App\Http\Requests\Item\UpdateRequest;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ItemResource::collection(Item::all()), 200);
    }

    public function show(Item $item): JsonResponse
    {
        return response()->json(new ItemResource($item), 200);
    }

    public function store(CreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Tikriname ar DB yra daiktas su tokiu pačiu inventoriaus kodu
        $existingItem = Item::where('InventoryNumber', $validated['InventoryNumber'])->first();

        if ($existingItem) {
            // Tikrinti tik jei kodas toks pat
            $fieldsToCompare = ['Name', 'Description', 'Price', 'UnitOfMeasure'];
            $mismatch = false;

            foreach ($fieldsToCompare as $field) {
                if ($existingItem->$field != $validated[$field]) {
                    $mismatch = true;
                    break;
                }
            }

            if ($mismatch) {
                return response()->json([
                    'message' => "Toks inventoriaus kodas jau egzistuoja, bet su skirtingais duomenimis.",
                    'existing_item' => new ItemResource($existingItem),
                ], 422);
            }

            // Jei viskas atitinka – padidinti kiekį
            $existingItem->Quantity += $validated['Quantity'] ?? 1;
            $existingItem->save();

            return response()->json(new ItemResource($existingItem), 200);
        }

        // Jei toks kodas neegzistuoja – kurti naują įrašą
        $item = Item::create($validated);
        return response()->json(new ItemResource($item), 201);
    }


    public function update(UpdateRequest $request, Item $item): JsonResponse
    {
        $item->update($request->validated());
        return response()->json(new ItemResource($item), 200);
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
