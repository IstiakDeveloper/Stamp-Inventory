<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Expenses Report</title>
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .previous-row {
            background-color: #e8e8e8 !important;
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
        <h2>Expenses Report</h2>
    </div>

    <div class="report-info">
        <strong>Period:</strong> {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">SL</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 47%;">Purpose</th>
                <th style="width: 15%;" class="text-right">Amount</th>
                <th style="width: 15%;" class="text-right">Total Expense</th>
            </tr>
        </thead>
        <tbody>
            <tr class="previous-row">
                <td colspan="4" class="text-right font-bold">Previous Expenses:</td>
                <td class="text-right font-bold">{{ rtrim(rtrim(number_format($previousMonthTotalExpenses, 2, '.', ''), '0'), '.') }}</td>
            </tr>

            @php
                $cumulativeNetExpense = $previousMonthTotalExpenses;
            @endphp

            @foreach ($expensesRecords as $index => $record)
                @php
                    $cumulativeNetExpense += $record->amount;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td>{{ $record->purpose }}</td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($record->amount, 2, '.', ''), '0'), '.') }}</td>
                    <td class="text-right font-bold">{{ rtrim(rtrim(number_format($cumulativeNetExpense, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3" class="text-right font-bold">This Month Total:</td>
                <td class="text-right font-bold">{{ rtrim(rtrim(number_format($totalAmount, 2, '.', ''), '0'), '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
