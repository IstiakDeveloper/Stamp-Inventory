<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Purchase Report</h1>
    <p><strong>Month:</strong> {{ $monthName }}</p>
    <p><strong>Year:</strong> {{ $year }}</p>
    <p><strong>Total Stock:</strong> {{ number_format($totalStockSum, 2) }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Address</th>
                <th>Sets</th>
                <th>Pieces</th>
                <th>Price Per Set</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->date }}</td>
                    <td>{{ $stock->address }}</td>
                    <td>{{ $stock->sets }}</td>
                    <td>{{ $stock->pieces }}</td>
                    <td>{{ $stock->price_per_set }}</td>
                    <td>{{ $stock->total_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
