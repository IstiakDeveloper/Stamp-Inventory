<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reject or Free Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #000;
            padding: 15px;
            line-height: 1.3;
        }

        .report-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .report-header h2 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-info {
            margin-bottom: 12px;
            font-size: 10px;
        }

        .report-info p {
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px 4px;
        }

        th {
            font-weight: bold;
            text-align: center;
            border: 2px solid #000;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }

        .this-month-row {
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .cumulative-row {
            font-weight: bold;
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
        }

        .previous-row {
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h2>Reject or Free Report</h2>
    </div>

    <div class="report-info">
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
            <tr class="previous-row">
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
