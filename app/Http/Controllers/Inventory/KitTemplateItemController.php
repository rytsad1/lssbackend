<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreKitTemplateItemRequest;
use App\Http\Requests\Inventory\UpdateKitTemplateItemRequest;
use App\Http\Resources\Inventory\KitTemplateItemResource;
use App\Models\Inventory\KitTemplate;
use App\Models\Inventory\KitTemplateItem;
use Illuminate\Http\JsonResponse;

class KitTemplateItemController extends Controller
{
    public function store(
        StoreKitTemplateItemRequest $request,
        KitTemplate $kit_template
    ): KitTemplateItemResource {
        $item = $kit_template->items()->create($request->validated());

        return new KitTemplateItemResource($item->load('item'));
    }

    public function update(
        UpdateKitTemplateItemRequest $request,
        KitTemplate $kit_template,
        KitTemplateItem $kit_item
    ): KitTemplateItemResource {
        if ($kit_item->kit_template_id !== $kit_template->id) {
            abort(404, 'Šis elementas nepriklauso nurodytam komplektui.');
        }

        $kit_item->update($request->validated());

        return new KitTemplateItemResource($kit_item->fresh()->load('item'));
    }

    public function destroy(
        KitTemplate $kit_template,
        KitTemplateItem $kit_item
    ): JsonResponse {
        if ($kit_item->kit_template_id !== $kit_template->id) {
            abort(404, 'Šis elementas nepriklauso nurodytam komplektui.');
        }

        $kit_item->delete();

        return response()->json([
            'message' => 'Komplekto elementas pašalintas.',
        ]);
    }
}
