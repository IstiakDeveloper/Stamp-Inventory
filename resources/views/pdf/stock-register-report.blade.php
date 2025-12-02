<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Register Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 9px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #000;
            font-size: 8px;
            text-transform: uppercase;
        }

        td {
            padding: 4px;
            border: 1px solid #000;
            font-size: 8px;
            vertical-align: middle;
        }

        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .previous-row {
            background-color: #90ee90;
            font-weight: bold;
        }

        .data-row {
            background-color: #f5f5f5;
        }

        .monthly-total-row {
            background-color: #add8e6;
            font-weight: bold;
        }

        .cumulative-total-row {
            background-color: #90ee90;
            font-weight: bold;
        }

        .stock-price-row {
            background-color: #90ee90;
            font-weight: bold;
        }

        .highlight-cell {
            background-color: #ffeb99;
            font-weight: bold;
        }

        .col-date { width: 25%; }
        .col-stock-in { width: 25%; }
        .col-stock-out { width: 25%; }
        .col-available { width: 25%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Stock Register Report</h2>
        <p>
            @php
                $monthName = \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F');
            @endphp
            {{ $monthName }} {{ $selectedYear }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-stock-in">Stock In Sets</th>
                <th class="col-stock-out">Stock Out Sets</th>
                <th class="col-available">Available Sets</th>
            </tr>
        </thead>
        <tbody>
            <!-- Previous Month Data -->
            <tr class="previous-row">
                <td class="text-left">Previous {{ $monthName }}:</td>
                <td class="text-center">
                    @php
                        $value = $soFarStockIn->sum('sets');
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-center">
                    @php
                        $value = $soFarStockOut->sum('sets');
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-right">
                    @php
                        $value = $soFarStockIn->sum('sets') - $soFarStockOut->sum('sets');
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
            </tr>

            <!-- Daily Stock Data -->
            @php
                $cumulativeStockIn = $soFarStockIn->sum('sets');
                $cumulativeStockOut = $soFarStockOut->sum('sets');
            @endphp
            @foreach ($completeMonth as $day)
                @php
                    $formattedDate = $day->format('Y-m-d');
                    $dayStockInData = $stockInData->firstWhere('date', $formattedDate) ?? ['sets' => 0];
                    $dayStockOutData = $stockOutData->get($formattedDate, ['sets' => 0]);

                    $cumulativeStockIn += $dayStockInData['sets'];
                    $cumulativeStockOut += $dayStockOutData['sets'];
                    $availableSets = $cumulativeStockIn - $cumulativeStockOut;

                    $isDataAvailable = $dayStockInData['sets'] > 0 || $dayStockOutData['sets'] > 0;

                    $stockInFormatted = $dayStockInData['sets'] % 1 === 0 ? number_format($dayStockInData['sets'], 0) : number_format($dayStockInData['sets'], 2);
                    $stockOutFormatted = $dayStockOutData['sets'] % 1 === 0 ? number_format($dayStockOutData['sets'], 0) : number_format($dayStockOutData['sets'], 2);
                    $availableFormatted = $availableSets % 1 === 0 ? number_format($availableSets, 0) : number_format($availableSets, 2);
                @endphp
                <tr class="{{ $isDataAvailable ? 'data-row' : '' }}">
                    <td class="text-left">{{ $day->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $stockInFormatted }}</td>
                    <td class="text-center">{{ $stockOutFormatted }}</td>
                    <td class="text-right">{{ $availableFormatted }}</td>
                </tr>
            @endforeach

            <!-- This Month Total -->
            <tr class="monthly-total-row">
                <td class="text-left"><strong>This Month Total:</strong></td>
                <td class="text-center">
                    @php
                        $value = $totalStockIn;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-center">
                    @php
                        $value = $totalStockOut;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-right">
                    @php
                        $value = $availableSets;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
            </tr>

            <!-- Total Cumulative -->
            <tr class="cumulative-total-row">
                <td class="text-left"><strong>Total Cumulative:</strong></td>
                <td class="text-center">
                    @php
                        $value = $soFarStockIn->sum('sets') + $totalStockIn;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-center">
                    @php
                        $value = $soFarStockOut->sum('sets') + $totalStockOut;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td class="text-right">
                    @php
                        $value = $availableSets;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
            </tr>

            <!-- Available Stock Price -->
            <tr class="stock-price-row">
                <td class="text-left"><strong>Available Stock Price:</strong></td>
                <td></td>
                <td></td>
                <td class="text-right highlight-cell">
                    {{ rtrim(rtrim(number_format($availableSets * $averageStampPricePerSet, 2, '.', ''), '0'), '.') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
