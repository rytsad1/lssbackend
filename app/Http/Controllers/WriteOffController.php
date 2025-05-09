<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WriteOffController extends Controller
{
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:item,id_Item',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.reason' => 'required|string|max:1000',
        ]);

        $itemsToRemove = collect($validated['items']);
        $fetchedItems = Item::whereIn('id_Item', $itemsToRemove->pluck('id'))->get();

        $templatePath = resource_path('excel/nurasymo_aktas_tuscias.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = 22;
        $currentRow = $startRow;

        foreach ($fetchedItems as $item) {
            $removal = $itemsToRemove->firstWhere('id', $item->id_Item);
            $writeOffQty = min($item->Quantity, $removal['quantity']);
            $reason = $removal['reason'] ?? 'Nenurodyta';
            $total = $writeOffQty * $item->Price;

            $sheet->setCellValue("A{$currentRow}", $item->Name);
            $sheet->setCellValue("B{$currentRow}", $item->InventoryNumber);
            $sheet->setCellValue("C{$currentRow}", $item->UnitOfMeasure);
            $sheet->setCellValue("D{$currentRow}", $reason);
            $sheet->setCellValue("E{$currentRow}", $item->Price);
            $sheet->setCellValue("F{$currentRow}", $writeOffQty);
            $sheet->setCellValue("G{$currentRow}", $total);

            if ($item->Quantity <= $writeOffQty) {
                $item->delete();
            } else {
                $item->Quantity -= $writeOffQty;
                $item->save();
            }

            $currentRow++;
        }

        // Įrašom į storage/app/public
        $fileName = now()->format('Y-m-d') . '_nurasymo_aktas.xlsx';
        $filePath = storage_path("app/public/{$fileName}");

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->json([
            'file' => asset("storage/{$fileName}")
        ]);
    }
}
