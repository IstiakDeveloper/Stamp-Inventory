<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Branch Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
            background-color: #f2f2f2;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .metric-header {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>All Branch Sale Report</h1>
        <p>Report Date: {{ $month }} {{ $year }}</p>
    </div>
    <div style="display: flex;">
        <div style="width: 100%; margin-bottom: 10px">
            <table>
                <thead>
                    <tr>
                        <th class="metric-header">Metric</th>
                        <th class="metric-header">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Funds</td>
                        <td> {{ number_format($totalCashIn, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Net Profit</td>
                        <td> {{ number_format($netProfit, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td>Total</td>
                        <td> {{ number_format($totalCashIn + $netProfit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 100%;">
            <table>
                <thead>
                    <tr>
                        <th class="metric-header">Metric</th>
                        <th class="metric-header">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Bank or Hand Balance</td>
                        <td> {{ number_format($totalBankOrHandBalance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>So Far Branch Outstanding</td>
                        <td> {{ number_format($outstandingTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Stock Stamp Buy Price</td>
                        <td> {{ number_format($stockStampBuyPrice, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td>Total</td>
                        <td> {{ number_format($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
 