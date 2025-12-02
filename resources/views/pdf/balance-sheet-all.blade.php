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
            padding: 5px 4px;
            border: 1px solid #333;
            vertical-align: middle;
        }

        .data-table tbody tr {
            height: 24px;
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

        .empty-row td {
            padding: 5px 4px;
        }

        .data-table tbody tr {
            height: 24px;
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
                        <th style="width: 35%;" class="text-right">Amount (৳)</th>
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
                    <tr class="empty-row">
                        <td>&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="empty-row">
                        <td>&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
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
                        <th style="width: 35%;" class="text-right">Amount (৳)</th>
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
