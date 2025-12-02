<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipts and Payments Statement</title>
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

        .text-center {
            text-align: center;
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

        .loan-received {
            background-color: #f0fdf4 !important;
        }

        .loan-received td {
            color: #15803d;
            font-weight: bold;
        }

        .loan-given {
            background-color: #fef2f2 !important;
        }

        .loan-given td {
            color: #b91c1c;
            font-weight: bold;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #333;
            font-size: 8px;
        }

        .summary h4 {
            font-size: 10px;
            margin-bottom: 8px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-column {
            display: table-cell;
            width: 50%;
            padding: 0 5px;
        }

        .summary-item {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .summary-label {
            display: table-cell;
            width: 60%;
        }

        .summary-value {
            display: table-cell;
            width: 40%;
            text-align: right;
            font-weight: bold;
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
        <h2>Receipts and Payments Statement</h2>
        <div class="date-info">
            <strong>For the month of:</strong> {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
        </div>
    </div>

    <div class="balance-container">
        <!-- Left Column: Receipts -->
        <div class="balance-column" style="padding-right: 5px;">
            <div class="table-header">Receipts</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65%;">Description</th>
                        <th style="width: 35%;" class="text-right">Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Opening Cash at Bank</td>
                        <td class="text-right">{{ number_format($data['opening_balance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Stamp Sale Collection</td>
                        <td class="text-right">{{ number_format($data['stamp_sale_collection'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Fund Receive</td>
                        <td class="text-right">{{ number_format($data['fund_receive'], 2) }}</td>
                    </tr>
                    <tr class="loan-received">
                        <td>Loan Payments Received</td>
                        <td class="text-right">{{ number_format($data['loan_payments_received'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">TOTAL</td>
                        <td class="text-right font-bold">{{ number_format($data['total_receipt'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Right Column: Payments -->
        <div class="balance-column" style="padding-left: 5px;">
            <div class="table-header">Payments</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 65%;">Particular</th>
                        <th style="width: 35%;" class="text-right">Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Purchase of Stamps</td>
                        <td class="text-right">{{ number_format($data['purchase_of_stamps'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Fund Refund</td>
                        <td class="text-right">{{ number_format($data['fund_refund'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Other Expenses</td>
                        <td class="text-right">{{ number_format($data['other_expenses'], 2) }}</td>
                    </tr>
                    <tr class="loan-given">
                        <td>Loans Given</td>
                        <td class="text-right">{{ number_format($data['loans_given'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Closing Cash at Bank</td>
                        <td class="text-right">{{ number_format($data['closing_balance'], 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="font-bold">TOTAL</td>
                        <td class="text-right font-bold">{{ number_format($data['total_payment'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary">
        <h4>Monthly Summary</h4>
        <div class="summary-grid">
            <div class="summary-column">
                <div class="summary-item">
                    <span class="summary-label">Opening Balance:</span>
                    <span class="summary-value">৳{{ number_format($data['opening_balance'], 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Closing Balance:</span>
                    <span class="summary-value">৳{{ number_format($data['closing_balance'], 2) }}</span>
                </div>
                <div class="summary-item" style="border-top: 1px solid #333; padding-top: 4px; margin-top: 4px;">
                    <span class="summary-label">Net Change:</span>
                    @php
                        $netChange = $data['closing_balance'] - $data['opening_balance'];
                    @endphp
                    <span class="summary-value" style="color: {{ $netChange >= 0 ? '#15803d' : '#b91c1c' }};">
                        {{ $netChange >= 0 ? '+' : '' }}৳{{ number_format($netChange, 2) }}
                    </span>
                </div>
            </div>
            <div class="summary-column">
                <div class="summary-item">
                    <span class="summary-label">Loans Given:</span>
                    <span class="summary-value" style="color: #b91c1c;">৳{{ number_format($data['loans_given'], 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Loan Payments Received:</span>
                    <span class="summary-value" style="color: #15803d;">৳{{ number_format($data['loan_payments_received'], 2) }}</span>
                </div>
                <div class="summary-item" style="border-top: 1px solid #333; padding-top: 4px; margin-top: 4px;">
                    <span class="summary-label">Net Loan Impact:</span>
                    @php
                        $netLoanImpact = $data['loans_given'] - $data['loan_payments_received'];
                    @endphp
                    <span class="summary-value" style="color: {{ $netLoanImpact <= 0 ? '#15803d' : '#b91c1c' }};">
                        {{ $netLoanImpact <= 0 ? '+' : '-' }}৳{{ number_format(abs($netLoanImpact), 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p style="font-style: italic; margin-bottom: 5px;">
            Note: This statement shows receipts and payments for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }} including loan transactions.
        </p>
        <p>
            Total Receipts: ৳{{ number_format($data['total_receipt'], 2) }} = Total Payments: ৳{{ number_format($data['total_payment'], 2) }}
            @if (abs($data['total_receipt'] - $data['total_payment']) < 0.01)
                <span style="font-weight: bold; color: #15803d;">✓ Balanced</span>
            @else
                <span style="font-weight: bold; color: #b91c1c;">⚠ Not Balanced</span>
            @endif
        </p>
        <p style="margin-top: 5px;">
            Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
        </p>
    </div>
</body>
</html>
