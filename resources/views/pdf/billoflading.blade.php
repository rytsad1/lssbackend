<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Važtaraštis</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        h2, h4 { text-align: center; margin: 0; }
    </style>
</head>
<body>
<h2>VAŽTARAŠTIS</h2>
<h4>Nr. {{ $bill->id_BillOfLading }} | Data: {{ \Carbon\Carbon::parse($bill->Date)->format('Y-m-d') }}</h4>

<p><strong>Išdavė:</strong> {{ $sender->Name }} ({{ $sender->Email }})</p>

<table>
    <thead>
    <tr>
        <th>Nr.</th>
        <th>Pavadinimas</th>
        <th>Inventorinis numeris</th>
        <th>Kiekis</th>
        <th>Vieneto kaina (€)</th>
        <th>Suma (€)</th>
    </tr>
    </thead>
    <tbody>
    @php $total = 0; @endphp
    @foreach ($items as $index => $item)
        @php
            $lineTotal = $item->Quantity * $item->Price;
            $total += $lineTotal;
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->Name }}</td>
            <td>{{ $item->InventoryNumber }}</td>
            <td>{{ $item->Quantity }}</td>
            <td>{{ number_format($item->Price, 2, ',', ' ') }}</td>
            <td>{{ number_format($lineTotal, 2, ',', ' ') }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5"><strong>Iš viso:</strong></td>
        <td><strong>{{ number_format($total, 2, ',', ' ') }}</strong></td>
    </tr>
    </tbody>
</table>

<p><strong>Suma žodžiais:</strong> {{ $amountInWords }} eurų</p>
</body>
</html>
