<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaeaea;
        }
        .bold-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .table-container {
            display: flex;
        }
        .table-container .table {
            width: 100%;
            border-right: 1px solid #ddd;
        }
        .table-container .table:last-child {
            border-right: none;
        }
        .table-container .table td, .table-container .table th {
            border-right: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Expenditure Sheet</h1>

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

    <p>{{ $formattedDate }}</p>

    <!-- Table with two columns -->
    <div class="table-container">
        <!-- Left Column -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>For the Month</th>
                    <th>For the Year</th>
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
                {{-- <tr>
                    <td></td>
                    <td class="text-right">{{ formatNumber($saleStampBuyPriceMonth) }}</td>
                    <td class="text-right">{{ formatNumber($saleStampBuyPriceYear) }}</td>
                </tr> --}}
                <tr class="bold-row">
                    <td>Total Loss</td>
                    <td class="text-right">{{ formatNumber($totalLossMonth) }}</td>
                    <td class="text-right">{{ formatNumber($totalLossYear) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Right Column -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>For the Month Amount</th>
                    <th>For the Year Amount</th>
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
                <tr class="bold-row">
                    <td>Total Sale</td>
                    <td class="text-right">{{ formatNumber($totalRevenueMonth) }}</td>
                    <td class="text-right">{{ formatNumber($totalRevenueYear) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Net Profit Table -->
    <h2>Net Profit</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>For the Month Amount</th>
                <th>For the Year Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bold-row">
                <td>Net Profit</td>
                <td class="text-right">{{ formatNumber($netProfitMonth) }}</td>
                <td class="text-right">{{ formatNumber($netProfitYear) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
