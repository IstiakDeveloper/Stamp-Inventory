<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Report</title>
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
            font-size: 9px;
        }

        .total-section {
            text-align: right;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f5f5f5;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
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

        .col-date { width: 12%; }
        .col-address { width: 30%; }
        .col-sets { width: 12%; }
        .col-price-per-set { width: 15%; }
        .col-total-price { width: 18%; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Purchase Report</h2>
    </div>

    <div class="report-info">
        @if($monthName && $year)
            <strong>Period:</strong> {{ $monthName }} {{ $year }}
        @else
            <strong>All Time Report</strong>
        @endif
    </div>

    <div class="total-section">
        Total Stock Value: ৳{{ rtrim(rtrim(number_format($totalStockSum, 2, '.', ''), '0'), '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-address">Address</th>
                <th class="col-sets">Sets</th>
                <th class="col-price-per-set">Price Per Set</th>
                <th class="col-total-price">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}</td>
                    <td class="text-left">{{ $stock->address }}</td>
                    <td class="text-center">
                        @php
                            $value = $stock->sets;
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($stock->price_per_set, 2, '.', ''), '0'), '.') }}</td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($stock->total_price, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
