<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Http\Resources\ItemResource;

class ImportController extends Controller
{
    public function preview(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);

        $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $request->file('file'))->first();

        // Panaudojame pavadinimų eilutę
        $data = $rows->slice(2); // Jei reikia praleisti pirmą eilutę (pvz., pavadinimą, jei turi dvi header eilutes)

        $preview = $data->map(function ($row) {
            return [
                'Name' => (string) $row[0] ?? null,
                'InventoryNumber' => isset($row[1]) ? (string) $row[1] : '',
                'UnitOfMeasure' => (string) $row[2] ?? null,
                'Quantity' => $this->parseToFloat($row[3] ?? 0),
                'Price' => $this->parseToFloat($row[5] ?? 0),
            ];
        });

        return response()->json(
            $preview->filter(fn($i) => $i['Name'] && $i['InventoryNumber'])->values(),
            200
        );
    }


    public function confirm(Request $request)
    {

        $data = $request->validate([
            'items' => 'required|array',
            'items.*.Name' => 'required|string|max:255',
            'items.*.InventoryNumber' => 'required|string|max:255',
            'items.*.UnitOfMeasure' => 'required|string|max:255',
            'items.*.Quantity' => 'required|numeric',
            'items.*.Price' => 'required|numeric|min:0',
        ]);

        $imported = [];
        $conflicts = [];

        foreach ($data['items'] as $entry) {
            $entry['Description'] = ''; // pridedam tuščią aprašymą

            $existingItem = Item::where('InventoryNumber', $entry['InventoryNumber'])->first();

            if ($existingItem) {
                // Tikrinam ar kiti laukai sutampa
                $fieldsToCompare = ['Name', 'Description', 'Price', 'UnitOfMeasure'];
                $mismatch = false;

                foreach ($fieldsToCompare as $field) {
                    if ($existingItem->$field != $entry[$field]) {
                        $mismatch = true;
                        break;
                    }
                }

                if ($mismatch) {
                    $conflicts[] = [
                        'message' => "Skiriasi duomenys prie kodo {$entry['InventoryNumber']}",
                        'existing_item' => new ItemResource($existingItem),
                        'incoming_item' => $entry
                    ];
                    continue;
                }

                // Jei viskas atitinka – didinam kiekį
                $existingItem->Quantity += $entry['Quantity'];
                $existingItem->save();
                $imported[] = new ItemResource($existingItem);
            } else {
                $item = Item::create($entry);
                $imported[] = new ItemResource($item);
            }
        }

        return response()->json([
            'message' => 'Importas užbaigtas',
            'imported' => $imported,
            'conflicts' => $conflicts,
        ], 201);
    }
    private function parseToFloat($value): float
    {
        // Pašalina viską, kas nėra skaičiai ar kableliai/taškai, tada konvertuoja į float
        $clean = preg_replace('/[^\d,\.]/', '', $value);
        $clean = str_replace(',', '.', $clean);
        return is_numeric($clean) ? (float)$clean : 0;
    }

}

