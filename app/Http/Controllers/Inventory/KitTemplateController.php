<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreKitTemplateRequest;
use App\Http\Requests\Inventory\UpdateKitTemplateRequest;
use App\Http\Resources\Inventory\KitTemplateResource;
use App\Models\Inventory\KitTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KitTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = KitTemplate::query()->withCount('items');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $templates = $query->latest()->paginate(20);

        return KitTemplateResource::collection($templates);
    }

    public function store(StoreKitTemplateRequest $request): KitTemplateResource
    {
        $template = KitTemplate::create($request->validated());

        return new KitTemplateResource($template->loadCount('items'));
    }

    public function show(KitTemplate $kit_template): KitTemplateResource
    {
        return new KitTemplateResource(
            $kit_template->load(['items.item'])->loadCount('items')
        );
    }

    public function update(UpdateKitTemplateRequest $request, KitTemplate $kit_template): KitTemplateResource
    {
        $kit_template->update($request->validated());

        return new KitTemplateResource(
            $kit_template->fresh()->load(['items.item'])->loadCount('items')
        );
    }

    public function destroy(KitTemplate $kit_template): JsonResponse
    {
        $kit_template->items()->delete();
        $kit_template->delete();

        return response()->json([
            'message' => 'Komplekto šablonas pašalintas.',
        ]);
    }
}
