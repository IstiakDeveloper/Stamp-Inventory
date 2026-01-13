<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Income & Expenditure Sheet</title>
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
            padding: 20px;
            line-height: 1.4;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-header h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .report-header .date-info {
            font-size: 11px;
            margin-top: 5px;
        }

        .balance-container {
            width: 100%;
            display: table;
            margin-top: 15px;
        }

        .balance-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .data-table th {
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            border: 2px solid #000;
            font-size: 10px;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            border-top: 3px solid #000 !important;
            border-bottom: 3px solid #000 !important;
        }

        .total-row td {
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
            padding: 8px 6px;
            font-weight: bold;
        }

        .table-header {
            font-weight: bold;
            text-align: center;
            padding: 8px 6px;
            border: 2px solid #000;
            font-size: 12px;
            text-transform: uppercase;
        }

        .highlight-row td {
            font-weight: bold;
            border: 2px solid #000;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #000;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        // Ensure month and year are integers
        $month = intval($month ?? now()->month);
        $year = intval($year ?? now()->year);

        // Helper function to format numbers without trailing zeros
        if (!function_exists('formatNumber')) {
            function formatNumber($number) {
                if (!is_numeric($number)) {
                    return '0';
                }
                return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
            }
        }

        // Convert month and year to a Carbon instance
        $date = \Carbon\Carbon::createFromFormat('!m', $month)->year($year);

        // Format as "Month Year"
        $formattedDate = $date->format('F Y');

        // Initialize variables with defaults
        $rejectOrFreeSumMonth = $rejectOrFreeSumMonth ?? 0;
        $rejectOrFreeSumYear = $rejectOrFreeSumYear ?? 0;
        $expenseSumMonth = $expenseSumMonth ?? 0;
        $expenseSumYear = $expenseSumYear ?? 0;
        $otherIncomeSumMonth = $otherIncomeSumMonth ?? 0;
        $otherIncomeSumYear = $otherIncomeSumYear ?? 0;
        $branchSalePriceSumMonth = $branchSalePriceSumMonth ?? 0;
        $branchSalePriceSumYear = $branchSalePriceSumYear ?? 0;
        $headOfficeSalePriceSumMonth = $headOfficeSalePriceSumMonth ?? 0;
        $headOfficeSalePriceSumYear = $headOfficeSalePriceSumYear ?? 0;
        $totalLossMonth = $totalLossMonth ?? 0;
        $totalLossYear = $totalLossYear ?? 0;
        $totalRevenueMonth = $totalRevenueMonth ?? 0;
        $totalRevenueYear = $totalRevenueYear ?? 0;
        $netProfitMonth = $netProfitMonth ?? 0;
        $netProfitYear = $netProfitYear ?? 0;
    @endphp

    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Income & Expenditure Sheet</h2>
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
                    <tr>
                        <td>&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
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
                    <tr class="highlight-row">
                        <td class="font-bold">Other Income</td>
                        <td class="text-right font-bold">{{ formatNumber($otherIncomeSumMonth) }}</td>
                        <td class="text-right font-bold">{{ formatNumber($otherIncomeSumYear) }}</td>
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
