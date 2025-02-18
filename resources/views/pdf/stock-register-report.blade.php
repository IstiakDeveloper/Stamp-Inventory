<!DOCTYPE html>
<html>
<head>
    <title>Stock Register Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 20mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
        h1, h2, p {
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-left {
            text-align: left;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-gray-800 {
            background-color: #2d2d2d;
        }
        .text-white {
            color: #ffffff;
        }
        .bg-blue-200 {
            background-color: #e3f2fd;
        }
        .bg-yellow-200 {
            background-color: #fdd835;
        }
        .bg-green-200 {
            background-color: #c8e6c9;
        }
    </style>
</head>
<body>
    <h1>Stock Register Report</h1>
    <p><strong>Month:</strong> {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}</p>

    <h2>Stock In and Stock Out</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Stock In Sets</th>
                {{-- <th>Stock In Price</th> --}}
                {{-- <th>Stock In Total Price</th> --}}
                <th>Stock Out Sets</th>
                {{-- <th>Stock Out Price</th> --}}
                {{-- <th>Stock Out Total Price</th> --}}
                <th>Available Sets</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cumulativeStockIn = $soFarStockIn->sum('sets');
                $cumulativeStockOut = $soFarStockOut->sum('sets');
                $availableSetsSoFar = $cumulativeStockIn - $cumulativeStockOut;
            @endphp
            <tr class="bg-green-200 font-bold">
                <td>Previous {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->format('F') }}</td>
                <td>{{ $cumulativeStockIn == intval($cumulativeStockIn) ? number_format($cumulativeStockIn, 0) : number_format($cumulativeStockIn, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ $soFarStockIn->sum('total_purchase_price') == intval($soFarStockIn->sum('total_purchase_price')) ? number_format($soFarStockIn->sum('total_purchase_price'), 0) : number_format($soFarStockIn->sum('total_purchase_price'), 2) }}</td> --}}
                <td>{{ $cumulativeStockOut == intval($cumulativeStockOut) ? number_format($cumulativeStockOut, 0) : number_format($cumulativeStockOut, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ $soFarStockOut->sum('total_price') == intval($soFarStockOut->sum('total_price')) ? number_format($soFarStockOut->sum('total_price'), 0) : number_format($soFarStockOut->sum('total_price'), 2) }}</td> --}}
                <td>{{ $availableSetsSoFar == intval($availableSetsSoFar) ? number_format($availableSetsSoFar, 0) : number_format($availableSetsSoFar, 2) }}</td>
            </tr>

            @foreach ($completeMonth as $day)
                @php
                    $formattedDate = $day->format('Y-m-d');
                    $dayStockInData = $stockInData->firstWhere('date', $formattedDate) ?? ['sets' => 0, 'purchase_price' => 0, 'total_purchase_price' => 0];
                    $dayStockOutData = $stockOutData->get($formattedDate, ['sets' => 0, 'price' => 0, 'total_price' => 0]);

                    $cumulativeStockIn += $dayStockInData['sets'];
                    $cumulativeStockOut += $dayStockOutData['sets'];

                    $availableSets = $cumulativeStockIn - $cumulativeStockOut;
                @endphp
                <tr>
                    <td>{{ $day->format('d/m/Y') }}</td>
                    <td>{{ $dayStockInData['sets'] == intval($dayStockInData['sets']) ? number_format($dayStockInData['sets'], 0) : number_format($dayStockInData['sets'], 2) }}</td>
                    {{-- <td>{{ $dayStockInData['purchase_price'] == intval($dayStockInData['purchase_price']) ? number_format($dayStockInData['purchase_price'], 0) : number_format($dayStockInData['purchase_price'], 2) }}</td> --}}
                    {{-- <td>{{ $dayStockInData['total_purchase_price'] == intval($dayStockInData['total_purchase_price']) ? number_format($dayStockInData['total_purchase_price'], 0) : number_format($dayStockInData['total_purchase_price'], 2) }}</td> --}}
                    <td>{{ $dayStockOutData['sets'] == intval($dayStockOutData['sets']) ? number_format($dayStockOutData['sets'], 0) : number_format($dayStockOutData['sets'], 2) }}</td>
                    {{-- <td>{{ $dayStockOutData['price'] == intval($dayStockOutData['price']) ? number_format($dayStockOutData['price'], 0) : number_format($dayStockOutData['price'], 2) }}</td> --}}
                    {{-- <td>{{ $dayStockOutData['total_price'] == intval($dayStockOutData['total_price']) ? number_format($dayStockOutData['total_price'], 0) : number_format($dayStockOutData['total_price'], 2) }}</td> --}}
                    <td>{{ $availableSets == intval($availableSets) ? number_format($availableSets, 0) : number_format($availableSets, 2) }}</td>
                </tr>
            @endforeach

            <tr class="bg-blue-200 font-bold">
                <td>This Month:</td>
                <td>{{ $totalStockIn == intval($totalStockIn) ? number_format($totalStockIn, 0) : number_format($totalStockIn, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ $totalStockInPrice == intval($totalStockInPrice) ? number_format($totalStockInPrice, 0) : number_format($totalStockInPrice, 2) }}</td> --}}
                <td>{{ $totalStockOut == intval($totalStockOut) ? number_format($totalStockOut, 0) : number_format($totalStockOut, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ $totalStockOutPrice == intval($totalStockOutPrice) ? number_format($totalStockOutPrice, 0) : number_format($totalStockOutPrice, 2) }}</td> --}}
                <td>{{ $availableSets == intval($availableSets) ? number_format($availableSets, 0) : number_format($availableSets, 2) }}</td>
            </tr>

            <tr class="bg-yellow-200 font-bold">
                <td>Total:</td>
                <td>{{ ($soFarStockIn->sum('sets') + $totalStockIn) == intval($soFarStockIn->sum('sets') + $totalStockIn) ? number_format($soFarStockIn->sum('sets') + $totalStockIn, 0) : number_format($soFarStockIn->sum('sets') + $totalStockIn, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ ($soFarStockIn->sum('total_purchase_price') + $totalStockInPrice) == intval($soFarStockIn->sum('total_purchase_price') + $totalStockInPrice) ? number_format($soFarStockIn->sum('total_purchase_price') + $totalStockInPrice, 0) : number_format($soFarStockIn->sum('total_purchase_price') + $totalStockInPrice, 2) }}</td> --}}
                <td>{{ ($soFarStockOut->sum('sets') + $totalStockOut) == intval($soFarStockOut->sum('sets') + $totalStockOut) ? number_format($soFarStockOut->sum('sets') + $totalStockOut, 0) : number_format($soFarStockOut->sum('sets') + $totalStockOut, 2) }}</td>
                {{-- <td>-</td> --}}
                {{-- <td>{{ ($soFarStockOut->sum('total_price') + $totalStockOutPrice) == intval($soFarStockOut->sum('total_price') + $totalStockOutPrice) ? number_format($soFarStockOut->sum('total_price') + $totalStockOutPrice, 0) : number_format($soFarStockOut->sum('total_price') + $totalStockOutPrice, 2) }}</td> --}}
                <td>{{ ($availableSetsSoFar + $availableSets) == intval($availableSetsSoFar + $availableSets) ? number_format($availableSetsSoFar + $availableSets, 0) : number_format($availableSetsSoFar + $availableSets, 2) }}</td>
            </tr>

            <tr class="bg-green-200 font-bold">
                <td>Available Stock Price:</td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                {{-- <td></td> --}}
                {{-- <td></td> --}}
                {{-- <td></td> --}}
                <td>{{ number_format($availableSets * $averageStampPricePerSet, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
