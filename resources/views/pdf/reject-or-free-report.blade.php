<!DOCTYPE html>
<html>
<head>
    <title>Reject or Free Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-left { text-align: left; }
        .this-month-row { background-color: #f8f8f8; }
        .cumulative-row { background-color: #e6f0ff; }
    </style>
</head>
<body>
    <h2>Reject or Free Report</h2>

    <div>
        <p><strong>Month:</strong> {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}</p>
        <p><strong>Purchase Price:</strong> {{ number_format($averageStampPricePerSet, 2, '.', ',') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Note</th>
                <th>Sets</th>
                <th>Purchase Price</th>
                <th>Loss</th>
                <th>Total Loss</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-right font-bold">Previous Month:</td>
                <td class="text-left font-bold">{{ number_format($previousMonthNetLoss, 2, '.', ',') }}</td>
            </tr>

            @php
                $cumulativeNetLoss = $previousMonthNetLoss;
                $totalMonthlyLoss = 0;
                $totalSetsThisMonth = 0;
            @endphp

            @foreach ($rejectOrFreeRecords as $index => $record)
                @php
                    $totalPrice = $record->sets * $averageStampPricePerSet;
                    $cumulativeNetLoss += $totalPrice;
                    $totalMonthlyLoss += $totalPrice;
                    $totalSetsThisMonth += $record->sets;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td>{{ $record->note }}</td>
                    <td>{{ number_format($record->sets, 0) }}</td>
                    <td>{{ number_format($averageStampPricePerSet, 2, '.', ',') }}</td>
                    <td>{{ number_format($totalPrice, 2, '.', ',') }}</td>
                    <td>{{ number_format($cumulativeNetLoss, 2, '.', ',') }}</td>
                </tr>
            @endforeach

            <tr class="this-month-row">
                <td colspan="3" class="text-right font-bold">This Month:</td>
                <td class="text-left font-bold">{{ number_format($totalSetsThisMonth, 0) }}</td>
                <td></td>
                <td class="text-left font-bold">{{ number_format($totalMonthlyLoss, 2, '.', ',') }}</td>
                <td class="text-left font-bold">{{ number_format($cumulativeNetLoss, 2, '.', ',') }}</td>
            </tr>

            <!-- New Cumulative Row -->
            <tr class="cumulative-row">
                <td colspan="3" class="text-right font-bold">Cumulative:</td>
                <td class="text-left font-bold">{{ number_format($cumulativeSets, 0) }}</td>
                <td></td>
                <td class="text-left font-bold">{{ number_format($cumulativeLoss, 2, '.', ',') }}</td>
                <td class="text-left font-bold">{{ number_format($cumulativeLoss, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
