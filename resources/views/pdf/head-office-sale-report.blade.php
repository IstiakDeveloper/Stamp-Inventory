<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Head Office Sale Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
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

        .report-header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-header h2 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .report-info {
            text-align: center;
            margin-bottom: 12px;
            font-size: 8px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px;
        }
        th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #d0d0d0;
            border-top: 2px solid #000;
        }
        .highlight-row {
            background-color: #f5f5f5;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Head Office Sale Report</h2>
    </div>

    <div class="report-info">
        <strong>Period:</strong> {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Sets</th>
                <th>Set Price</th>
                <th>Price</th>
                <th>Cash Received</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            <!-- Previous Row -->
            <tr class="highlight-row">
                <td></td>
                <td><strong>Previous:</strong></td>
                <td class="text-center">
                    @php
                        $formattedValue = $soFarSets == intval($soFarSets) ? number_format($soFarSets, 0) : number_format($soFarSets, 2);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>--</td>
                <td>--</td>
                <td class="text-center">
                    <strong>
                        @php
                            $formattedSoFarCash = $soFarCash == intval($soFarCash) ? number_format($soFarCash, 0) : number_format($soFarCash, 2);
                        @endphp
                        {{ $formattedSoFarCash }}
                    </strong>
                </td>
                <td class="text-right">
                    <strong>
                        @php
                            $formattedSoFarDue = $soFarDue == intval($soFarDue) ? number_format($soFarDue, 0) : number_format($soFarDue, 2);
                        @endphp
                        {{ $formattedSoFarDue }}
                    </strong>
                </td>
            </tr>

            <!-- Sales Data -->
            @foreach ($completeMonth as $day)
                @php
                    $sale = $sales->firstWhere('date', $day->format('Y-m-d'));
                @endphp
                <tr class="{{ !empty($sale) ? 'highlight-row' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $day->format('Y-m-d') }}</td>
                    <td>{{ $sale['sets'] ?? 0 }}</td>
                    <td>
                        @php
                            $formattedSetPrice = ($sale['set_price'] ?? 0) == intval($sale['set_price'] ?? 0) ? number_format($sale['set_price'] ?? 0, 0) : number_format($sale['set_price'] ?? 0, 2);
                        @endphp
                        {{ $formattedSetPrice }}
                    </td>
                    <td>
                        @php
                            $formattedPrice = ($sale['price'] ?? 0) == intval($sale['price'] ?? 0) ? number_format($sale['price'] ?? 0, 0) : number_format($sale['price'] ?? 0, 2);
                        @endphp
                        {{ $formattedPrice }}
                    </td>
                    <td>
                        @php
                            $formattedCash = ($sale['cash'] ?? 0) == intval($sale['cash'] ?? 0) ? number_format($sale['cash'] ?? 0, 0) : number_format($sale['cash'] ?? 0, 2);
                        @endphp
                        {{ $formattedCash }}
                    </td>
                    <td>
                        @php
                            $formattedDue = ($sale['due'] ?? 0) == intval($sale['due'] ?? 0) ? number_format($sale['due'] ?? 0, 0) : number_format($sale['due'] ?? 0, 2);
                        @endphp
                        {{ $formattedDue }}
                    </td>
                </tr>
            @endforeach

            <!-- Totals -->
            <tr class="total-row">
                <td colspan="2">Current Month Total</td>
                <td class="text-center">
                    @php
                        $formattedTotalSetPrice = $totalSetPrice == intval($totalSetPrice) ? number_format($totalSetPrice, 0) : number_format($totalSetPrice, 2);
                    @endphp
                    {{ $formattedTotalSetPrice }}
                </td>
                <td></td>
                <td class="text-center">
                    @php
                        $formattedTotalPrice = $totalPrice == intval($totalPrice) ? number_format($totalPrice, 0) : number_format($totalPrice, 2);
                    @endphp
                    {{ $formattedTotalPrice }}
                </td>
                <td class="text-center">
                    @php
                        $formattedTotalCashReceived = $totalCashReceived == intval($totalCashReceived) ? number_format($totalCashReceived, 0) : number_format($totalCashReceived, 2);
                    @endphp
                    {{ $formattedTotalCashReceived }}
                </td>
                <td class="text-right">
                    @php
                        $formattedTotalDue = $totalDue == intval($totalDue) ? number_format($totalDue, 0) : number_format($totalDue, 2);
                    @endphp
                    {{ $formattedTotalDue }}
                </td>
            </tr>

            <tr class="total-row">
                <td colspan="2">Total So Far:</td>
                <td class="text-center">
                    @php
                        $formattedTotalSetPriceSoFar = ($totalSetPrice + $soFarSets) == intval($totalSetPrice + $soFarSets) ? number_format($totalSetPrice + $soFarSets, 0) : number_format($totalSetPrice + $soFarSets, 2);
                    @endphp
                    {{ $formattedTotalSetPriceSoFar }}
                </td>
                <td></td>
                <td class="text-center"></td>
                <td class="text-center">
                    @php
                        $formattedTotalCashReceivedSoFar = ($totalCashReceived + $soFarCash) == intval($totalCashReceived + $soFarCash) ? number_format($totalCashReceived + $soFarCash, 0) : number_format($totalCashReceived + $soFarCash, 2);
                    @endphp
                    {{ $formattedTotalCashReceivedSoFar }}
                </td>
                <td class="text-right">
                    @php
                        $formattedTotalDueSoFar = ($totalDue + $soFarDue) == intval($totalDue + $soFarDue) ? number_format($totalDue + $soFarDue, 0) : number_format($totalDue + $soFarDue, 2);
                    @endphp
                    {{ $formattedTotalDueSoFar }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
