<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 5px 4px;
            vertical-align: middle;
        }

        th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
            text-transform: uppercase;
        }

        td {
            background-color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Transaction Report</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Date</th>
                <th style="width: 70%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                    <td class="text-right">৳{{ rtrim(rtrim(number_format($transaction->amount, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
