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
        .text-center {
            text-align: center;
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

        .highlight-row {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .highlight-row td {
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

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #000;
            font-size: 9px;
            text-align: center;
        }

        .empty-row td {
            padding: 6px;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Balance Sheet</h2>
        <div class="date-info">
            <strong>As on:</strong> {{ date('F d, Y', mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, $year)), $year)) }}
        </div>
    </div>

    <div class="balance-container">
        <!-- Left Column: Funds & Liabilities -->
        <div class="balance-column" style="padding-right: 5px;">
            <div class="table-header">Funds & Liabilities</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65%;">Particulars</th>
                        <th style="width: 35%;" class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Funds</td>
                        <td class="text-right">{{ number_format($totalCashIn, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Net Profit</td>
                        <td class="text-right">{{ number_format($netProfit, 2) }}</td>
                    </tr>
                    <tr class="highlight-row">
                        <td class="font-bold">Other Income</td>
                        <td class="text-right font-bold">{{ number_format($totalOtherIncome ?? 0, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">TOTAL</td>
                        <td class="text-right font-bold">{{ number_format($totalCashIn + $netProfit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Right Column: Property & Assets -->
        <div class="balance-column" style="padding-left: 5px;">
            <div class="table-header">Property & Assets</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65%;">Particulars</th>
                        <th style="width: 35%;" class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bank Balance</td>
                        <td class="text-right">{{ number_format($totalBankOrHandBalance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Branches Due</td>
                        <td class="text-right">{{ number_format($outstandingTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Stock Stamp Buy Price</td>
                        <td class="text-right">{{ number_format($stockStampBuyPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Loan Receivables</td>
                        <td class="text-right">{{ number_format($loanReceivables, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">TOTAL</td>
                        <td class="text-right font-bold">{{ number_format($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice + $loanReceivables, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
