<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Balance Sheet</title>
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

        .report-header .date-info {
            font-size: 9px;
            margin-top: 5px;
        }

        .balance-container {
            width: 100%;
            display: table;
            margin-top: 10px;
        }

        .balance-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .data-table th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            padding: 5px 4px;
            text-align: left;
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

        .text-right {
            text-align: right;
        }

        .font-bold {
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

        .table-header {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #000;
            font-size: 9px;
            text-transform: uppercase;
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
        <h2>Expenditure Sheet</h2>

        @php
            // Ensure month and year are integers
            $month = intval($month);
            $year = intval($year);

            // Convert month and year to a Carbon instance
            $date = \Carbon\Carbon::createFromFormat('!m', $month)->year($year);

            // Format as "Month Year"
            $formattedDate = $date->format('F Y');

            // Helper function to format numbers without trailing zeros
            function formatNumber($number) {
                return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
            }
        @endphp
        <div class="date-info">
            <strong>Period:</strong> {{ $formattedDate }}
        </div>
    </div>

    <!-- Table with two columns -->
    <div class="balance-container">
        <!-- Left Column -->
        <div class="balance-column" style="padding-right: 5px;">
            <div class="table-header">Expenditure</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Title</th>
                        <th style="width: 25%;" class="text-right">For the Month</th>
                        <th style="width: 25%;" class="text-right">For the Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reject or Free (Total Purchase Price)</td>
                        <td class="text-right">{{ formatNumber($rejectOrFreeSumMonth) }}</td>
                        <td class="text-right">{{ formatNumber($rejectOrFreeSumYear) }}</td>
                    </tr>
                    <tr>
                        <td>Expenses</td>
                        <td class="text-right">{{ formatNumber($expenseSumMonth) }}</td>
                        <td class="text-right">{{ formatNumber($expenseSumYear) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">Total Loss</td>
                        <td class="text-right font-bold">{{ formatNumber($totalLossMonth) }}</td>
                        <td class="text-right font-bold">{{ formatNumber($totalLossYear) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Right Column -->
        <div class="balance-column" style="padding-left: 5px;">
            <div class="table-header">Revenue</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Title</th>
                        <th style="width: 25%;" class="text-right">For the Month</th>
                        <th style="width: 25%;" class="text-right">For the Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Branch Sales (Total Price)</td>
                        <td class="text-right">{{ formatNumber($branchSalePriceSumMonth) }}</td>
                        <td class="text-right">{{ formatNumber($branchSalePriceSumYear) }}</td>
                    </tr>
                    <tr>
                        <td>Head Office Sales (Total Price)</td>
                        <td class="text-right">{{ formatNumber($headOfficeSalePriceSumMonth) }}</td>
                        <td class="text-right">{{ formatNumber($headOfficeSalePriceSumYear) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">Total Sale</td>
                        <td class="text-right font-bold">{{ formatNumber($totalRevenueMonth) }}</td>
                        <td class="text-right font-bold">{{ formatNumber($totalRevenueYear) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Net Profit Table -->
    <div style="margin-top: 10px;">
        <div class="table-header">Net Profit</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Title</th>
                    <th style="width: 25%;" class="text-right">For the Month</th>
                    <th style="width: 25%;" class="text-right">For the Year</th>
                </tr>
            </thead>
            <tbody>
                <tr class="total-row">
                    <td class="font-bold">Net Profit</td>
                    <td class="text-right font-bold">{{ formatNumber($netProfitMonth) }}</td>
                    <td class="text-right font-bold">{{ formatNumber($netProfitYear) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
