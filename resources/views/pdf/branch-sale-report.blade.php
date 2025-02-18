<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Sale Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-gray-100 {
            background-color: #f9f9f9;
        }
        .bg-gray-200 {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Branch Sale Report</h1>
    <p><strong>Branch:</strong> {{ $branchName }}</p>
    <p><strong>From Date:</strong> {{ $fromDate ?? 'No date specified' }}</p>
    <p><strong>To Date:</strong> {{ $toDate ?? 'No date specified' }}</p>

    <table>
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Date</th>
                <th>Sets</th>
                <th>Price</th>
                <th>Receive Cash</th>
                <th>Due</th>
                <th>Total Due</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">Outstanding balance before {{ $fromDate ?? 'the selected period' }} was</td>
                <td class="text-right">{{ number_format($soFarOutstanding, 2) }}</td>
            </tr>

            @forelse ($sales as $sale)
                <tr class="{{ $loop->odd ? 'bg-gray-100' : '' }}">
                    <td>{{ $loop->iteration }}</td> <!-- Serial Number -->
                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d') }}</td>
                    <td>{{ $sale->sets }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ number_format($sale->cash, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->total_price - $sale->cash, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->total_due, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No sales data available for the selected period.</td>
                </tr>
            @endforelse

            <tr class="bg-gray-200 font-bold">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-right">{{ $totalSets }}</td>
                <td class="text-right">{{ number_format($totalPrice, 2) }}</td>
                <td class="text-right">{{ number_format($totalCash, 2) }}</td>
                <td class="text-right">{{ number_format(max($totalPrice - $totalCash, 0), 2) }}</td>
                <td class="text-right">{{ number_format($totalDue, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
