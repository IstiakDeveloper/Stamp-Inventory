<!DOCTYPE html>
<html>
<head>
    <title>Expenses Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Expenses Report</h2>
    <p><strong>Month:</strong> {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }}</p>
    <p><strong>Year:</strong> {{ $currentYear }}</p>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th>Total Expense</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-right font-bold">Previous Expenses:</td>
                <td class="font-bold">{{ rtrim(rtrim(number_format($previousMonthTotalExpenses, 2, '.', ''), '0'), '.') }}</td>
            </tr>

            @php
                $cumulativeNetExpense = $previousMonthTotalExpenses;
            @endphp

            @foreach ($expensesRecords as $index => $record)
                @php
                    $cumulativeNetExpense += $record->amount;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td>{{ $record->purpose }}</td>
                    <td>{{ rtrim(rtrim(number_format($record->amount, 2, '.', ''), '0'), '.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($cumulativeNetExpense, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3" class="text-right font-bold">This Month:</td>
                <td class="font-bold">{{ rtrim(rtrim(number_format($totalAmount, 2, '.', ''), '0'), '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
