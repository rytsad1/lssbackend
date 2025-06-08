<?php

namespace App\Http\Controllers;

use App\Models\BillOfLading;
use Illuminate\Http\Request;
use App\Http\Resources\BillOfLadingResource;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;


class BillOfLadingController extends Controller
{
    public function index()
    {
        return BillOfLadingResource::collection(BillOfLading::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Date' => ['sometimes', 'date'],
            'Sum' => ['required', 'numeric'],
            'Type' => ['required', 'integer'],
            'fkOrderid_Order' => ['required', 'integer'],
        ]);

        $bill = BillOfLading::updateOrCreate(
            ['fkOrderid_Order' => $validated['fkOrderid_Order']],
            $validated
        );

        return response()->json([
            'message' => 'Važtaraštis sukurtas',
            'bill_id' => $bill->id_BillOfLading,
            'file' => url("/api/v1/billoflading/pdf/{$bill->id_BillOfLading}")
        ], 201);
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
    public function generateBillOfLadingPdf($billId)
    {
        $bill = BillOfLading::with('order.orderItems.item')->findOrFail($billId);
        $sender = auth()->user();

        $items = $bill->order->orderItems->map(function ($orderItem) {
            $item = $orderItem->item;
            $item->Quantity = $orderItem->Quantity;
            return $item;
        });

        $amountInWords = $this->numberToWords($items->sum(fn($i) => $i->Price * $i->Quantity));

        $pdf = Pdf::loadView('pdf.billoflading', compact('bill', 'sender', 'items', 'amountInWords'));

        $fileName = "vaztarastis_{$bill->id_BillOfLading}.pdf";

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }



    private function numberToWords($number)
    {
        $f = new \NumberFormatter("lt", \NumberFormatter::SPELLOUT);
        return $f->format($number);
    }
    public function findByOrder($orderId)
    {
        $bill = BillOfLading::where('fkOrderid_Order', $orderId)->first();
        if (!$bill) return response()->json(null, 204);
        return new BillOfLadingResource($bill);
    }


}
