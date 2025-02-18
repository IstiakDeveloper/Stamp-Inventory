<!DOCTYPE html>
<html>
<head>
    <title>Transaction Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Transaction Report</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date->format('Y-m-d') }}</td>
                    <td>{{ $transaction->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
