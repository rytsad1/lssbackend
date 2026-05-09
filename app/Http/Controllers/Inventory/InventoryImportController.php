<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ImportInventoryPreviewRequest;
use App\Http\Requests\Inventory\ImportInventoryConfirmRequest;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\ItemVariant;
use App\Models\Inventory\StockBatch;
use App\Models\Inventory\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoryImportController extends Controller
{
    public function preview(ImportInventoryPreviewRequest $request)
    {
        $rows = Excel::toCollection(null, $request->file('file'))->first();

        // Kaip ir senam importe, praleidžiam pirmas 2 eilutes
        $data = $rows->slice(2);

        $preview = $data->map(function ($row) {
            $code = isset($row[1]) ? trim((string) $row[1]) : null;
            $name = isset($row[0]) ? trim((string) $row[0]) : null;
            $unit = isset($row[2]) ? trim((string) $row[2]) : 'vnt';
            $quantity = $this->parseToFloat($row[3] ?? 0);
            $price = $this->parseToFloat($row[5] ?? 0);

            return [
                'code' => $code,
                'name' => $name,
                'description' => null,
                'unit_of_measure' => $unit ?: 'vnt',
                'quantity' => $quantity,
                'price' => $price,
                'is_asset' => false,
                'is_serialized' => false,
                'is_expirable' => false,
                'expiration_date' => null,
                'batch_number' => null,
            ];
        });

        return response()->json(
            $preview
                ->filter(fn ($i) => !empty($i['name']) && !empty($i['code']))
                ->values(),
            200
        );
    }

    public function confirm(ImportInventoryConfirmRequest $request)
    {
        $validated = $request->validated();

        $imported = [];
        $conflicts = [];

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $entry) {
                $existingItem = InventoryItem::where('code', $entry['code'])->first();

                if ($existingItem) {
                    $mismatch = false;

                    $fieldsToCompare = [
                        'name',
                        'unit_of_measure',
                    ];

                    foreach ($fieldsToCompare as $field) {
                        if ((string) $existingItem->{$field} !== (string) $entry[$field]) {
                            $mismatch = true;
                            break;
                        }
                    }

                    if ($mismatch) {
                        $conflicts[] = [
                            'message' => "Skiriasi duomenys prie kodo {$entry['code']}",
                            'existing_item' => [
                                'id' => $existingItem->id,
                                'code' => $existingItem->code,
                                'name' => $existingItem->name,
                                'unit_of_measure' => $existingItem->unit_of_measure,
                            ],
                            'incoming_item' => $entry,
                        ];
                        continue;
                    }

                    $item = $existingItem;
                } else {
                    $item = InventoryItem::create([
                        'code' => $entry['code'],
                        'name' => $entry['name'],
                        'description' => $entry['description'] ?? null,
                        'unit_of_measure' => $entry['unit_of_measure'],
                        'is_expirable' => $entry['is_expirable'] ?? false,
                        'is_asset' => $entry['is_asset'] ?? false,
                        'is_serialized' => $entry['is_serialized'] ?? false,
                        'is_active' => true,
                    ]);
                }

                $variant = ItemVariant::firstOrCreate(
                    [
                        'sku' => $entry['code'] . '-DEFAULT',
                    ],
                    [
                        'item_id' => $item->id,
                        'name' => $entry['name'],
                        'size' => null,
                        'color' => null,
                        'model' => null,
                        'attributes' => null,
                        'is_active' => true,
                    ]
                );

                $batch = StockBatch::create([
                    'item_variant_id' => $variant->id,
                    'batch_number' => $entry['batch_number'] ?? null,
                    'received_date' => now()->toDateString(),
                    'quantity_initial' => $entry['quantity'],
                    'quantity_remaining' => $entry['quantity'],
                    'expiration_date' => $entry['expiration_date'] ?? null,
                    'source_reference' => 'excel_import',
                    'notes' => 'Importuota iš Excel',
                ]);

                $movement = InventoryMovement::create([
                    'item_variant_id' => $variant->id,
                    'stock_batch_id' => $batch->id,
                    'asset_unit_id' => null,
                    'legacy_user_id' => auth('api')->id(),
                    'legacy_department_id' => null,
                    'legacy_order_id' => null,
                    'movement_type' => 'initial_load',
                    'quantity' => $entry['quantity'],
                    'movement_date' => now(),
                    'reason' => 'Importuota iš Excel',
                    'context' => [
                        'source' => 'excel_import',
                        'price' => $entry['price'] ?? null,
                    ],
                ]);

                $imported[] = [
                    'item_id' => $item->id,
                    'variant_id' => $variant->id,
                    'batch_id' => $batch->id,
                    'movement_id' => $movement->id,
                    'code' => $item->code,
                    'name' => $item->name,
                    'quantity' => $entry['quantity'],
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Importas užbaigtas',
                'imported' => $imported,
                'conflicts' => $conflicts,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Importo klaida',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function parseToFloat($value): float
    {
        $clean = preg_replace('/[^\d,\.]/', '', (string) $value);
        $clean = str_replace(',', '.', $clean);

        return is_numeric($clean) ? (float) $clean : 0;
    }
}
