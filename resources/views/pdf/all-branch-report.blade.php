<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Branch Sales Report</title>
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
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
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
            margin-bottom: 10px;
            font-size: 8px;
        }

        th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            padding: 5px 4px;
            text-align: center;
            border: 1px solid #000;
        }

        td {
            padding: 4px;
            border: 1px solid #333;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .total-row {
            background-color: #d0d0d0 !important;
            font-weight: bold;
        }

        .subtotal-row {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }

        .grand-total-row {
            background-color: #000 !important;
            color: #fff !important;
            font-weight: bold;
            border: 2px solid #000 !important;
        }

        .grand-total-row td {
            border: 1px solid #000 !important;
        }

        .text-left {
            text-align: left;
            padding-left: 8px;
        }

        .text-right {
            text-align: right;
            padding-right: 8px;
        }

        .col-sl { width: 5%; }
        .col-branch { width: 25%; }
        .col-prev-due { width: 12%; }
        .col-sets { width: 10%; }
        .col-price { width: 13%; }
        .col-cash { width: 13%; }
        .col-total-due { width: 12%; }

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
        <h2>All Branch Sales Report</h2>
    </div>

    <div class="report-info">
        @if($fromDate && $toDate)
            Report Period: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}
        @else
            All Time Data
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-sl">SL</th>
                <th class="col-branch">Branch Name</th>
                <th class="col-prev-due">Previous Due (Tk)</th>
                <th class="col-sets">Sets</th>
                <th class="col-price">Price (Tk)</th>
                <th class="col-cash">Cash (Tk)</th>
                <th class="col-total-due">Total Due (Tk)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $data['branch_name'] }}</td>
                <td class="text-right">{{ number_format($data['previous_outstanding'], 2) }}</td>
                <td class="text-right">
                    @php
                        $value = $data['sets'];
                        echo $value == floor($value) ? number_format($value, 0) : number_format($value, 2);
                    @endphp
                </td>
                <td class="text-right">{{ number_format($data['price'], 2) }}</td>
                <td class="text-right">{{ number_format($data['cash'], 2) }}</td>
                <td class="text-right">{{ number_format($data['total_due'], 2) }}</td>
            </tr>
            @endforeach

            <!-- Branch Total Row -->
            <tr class="subtotal-row">
                <td colspan="3" class="text-right">BRANCH TOTAL</td>
                <td class="text-right">
                    @php
                        echo $totalSets == floor($totalSets) ? number_format($totalSets, 0) : number_format($totalSets, 2);
                    @endphp
                </td>
                <td class="text-right">{{ number_format($totalPrice, 2) }}</td>
                <td class="text-right">{{ number_format($totalCash, 2) }}</td>
                <td class="text-right">{{ number_format($totalDue, 2) }}</td>
            </tr>

            <!-- Head Office Total Row -->
            <tr class="total-row">
                <td colspan="3" class="text-right">HEAD OFFICE TOTAL</td>
                <td class="text-right">
                    @php
                        $value = $hoSetsSum;
                        echo $value == floor($value) ? number_format($value, 0) : number_format($value, 2);
                    @endphp
                </td>
                <td class="text-right">{{ number_format($hoTotalPriceSum, 2) }}</td>
                <td class="text-right">{{ number_format($hoCashSum, 2) }}</td>
                <td class="text-right">0.00</td>
            </tr>

            <!-- Reject or Free Total Row -->
            <tr class="total-row">
                <td colspan="3" class="text-right">REJECT / FREE TOTAL</td>
                <td class="text-right">
                    @php
                        $value = $rfSetsSum;
                        echo $value == floor($value) ? number_format($value, 0) : number_format($value, 2);
                    @endphp
                </td>
                <td class="text-right">--</td>
                <td class="text-right">--</td>
                <td class="text-right">--</td>
            </tr>

            <!-- Grand Total Row -->
            @php
                $grandTotalSets = $totalSets + $hoSetsSum + $rfSetsSum;
                $grandTotalPrice = $totalPrice + $hoTotalPriceSum;
                $grandTotalCash = $totalCash + $hoCashSum;
            @endphp
            <tr class="grand-total-row">
                <td colspan="3" class="text-right">GRAND TOTAL</td>
                <td class="text-right">
                    @php
                        $value = $grandTotalSets;
                        echo $value == floor($value) ? number_format($value, 0) : number_format($value, 2);
                    @endphp
                </td>
                <td class="text-right">{{ number_format($grandTotalPrice, 2) }}</td>
                <td class="text-right">{{ number_format($grandTotalCash, 2) }}</td>
                <td class="text-right">{{ number_format($totalDue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
