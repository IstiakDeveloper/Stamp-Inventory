<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Branch Sale Report</title>
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
            margin-bottom: 12px;
            font-size: 8px;
        }

        .report-info table {
            width: 100%;
            border: none;
        }

        .report-info td {
            padding: 2px 5px;
            border: none;
        }

        .report-info .label {
            font-weight: bold;
            width: 80px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 8px;
        }

        .data-table th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            padding: 5px 4px;
            text-align: center;
            border: 1px solid #000;
            font-size: 8px;
        }

        .data-table td {
            padding: 4px;
            border: 1px solid #333;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .data-table tbody tr:hover {
            background-color: #e8e8e8;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .outstanding-row {
            background-color: #e0e0e0 !important;
            font-weight: bold;
        }

        .total-row {
            background-color: #d0d0d0 !important;
            font-weight: bold;
            border-top: 2px solid #000 !important;
        }

        .total-row td {
            border-top: 2px solid #000;
            padding: 5px 4px;
        }

        .col-serial { width: 6%; }
        .col-date { width: 12%; }
        .col-sets { width: 10%; }
        .col-price { width: 14%; }
        .col-cash { width: 14%; }
        .col-due { width: 14%; }
        .col-total-due { width: 14%; }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }

        @media print {
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Branch Sale Report</h2>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td class="label">Branch:</td>
                <td>{{ $branchName }}</td>
                <td class="label" style="text-align: right;">Period:</td>
                <td>{{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'N/A' }} to {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-serial">SL</th>
                <th class="col-date">Date</th>
                <th class="col-sets">Sets</th>
                <th class="col-price">Price (Tk)</th>
                <th class="col-cash">Cash Received (Tk)</th>
                <th class="col-due">Due (Tk)</th>
                <th class="col-total-due">Total Due (Tk)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="outstanding-row">
                <td colspan="6" class="text-left">Outstanding Balance Before Period</td>
                <td class="text-right font-bold">{{ number_format($soFarOutstanding, 2) }}</td>
            </tr>

            @forelse ($sales as $sale)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y') }}</td>
                    <td class="text-right">
                        @php
                            $sets = $sale->sets;
                            echo $sets == floor($sets) ? number_format($sets, 0) : number_format($sets, 2);
                        @endphp
                    </td>
                    <td class="text-right">{{ number_format($sale->total_price, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->cash, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->total_price - $sale->cash, 2) }}</td>
                    <td class="text-right font-bold">{{ number_format($sale->total_due, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">No sales data available for the selected period</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="2" class="text-right font-bold">TOTAL</td>
                <td class="text-right font-bold">
                    @php
                        echo $totalSets == floor($totalSets) ? number_format($totalSets, 0) : number_format($totalSets, 2);
                    @endphp
                </td>
                <td class="text-right font-bold">{{ number_format($totalPrice, 2) }}</td>
                <td class="text-right font-bold">{{ number_format($totalCash, 2) }}</td>
                <td class="text-right font-bold">{{ number_format(max($totalPrice - $totalCash, 0), 2) }}</td>
                <td class="text-right font-bold">{{ number_format($totalDue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
