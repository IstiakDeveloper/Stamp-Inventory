<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Branch Sales Report</h1>
    @if($fromDate && $toDate)
        <p>Report Period: {{ $fromDate }} to {{ $toDate }}</p>
    @else
        <p>All Data</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Branch Name</th>
                <th>Previous Due</th>
                <th>Sets</th>
                <th>Price</th>
                <th>Cash Receive</th>
                <th>Total Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data['branch_name'] }}</td>
                <td>{{ $data['previous_outstanding'] }}</td>
                <td>
                    @php
                        $value = $data['sets'];
                        // Format to two decimal places if needed
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>{{ $data['price'] }}</td>
                <td>{{ $data['cash'] }}</td>
                <td>{{ $data['total_due'] }}</td>
            </tr>
            @endforeach
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSets }}</td>
                <td>{{ $totalPrice }}</td>
                <td>{{ $totalCash }}</td>
                <td>{{ $totalDue }}</td>
            </tr>
            <!-- Head Office Total Row -->
            <tr class="total-row">
                <td colspan="3">Head Office Total</td>
                <td>
                    @php
                        $value = $hoSetsSum;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>
                    @php
                        $value = $hoTotalPriceSum;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>
                    @php
                        $value = $hoCashSum;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>0</td>
            </tr>
            <!-- Reject or Free Total Row -->
            <tr class="total-row">
                <td colspan="3">Reject or Free Total</td>
                <td>
                    @php
                        $value = $rfSetsSum;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>--</td>
                <td>--</td>
                <td>--</td>
            </tr>
            <!-- Grand Total Row -->

            @php
                $grandTotalSets =  $totalSets + $hoSetsSum + $rfSetsSum;
                $grandTotalPrice = $totalPrice + $hoTotalPriceSum;
                $grandTotalCash = $totalCash + $hoCashSum;
            @endphp
            <tr class="total-row">
                <td colspan="3">Grand Total</td>
                <td>
                    @php
                        $value = $grandTotalSets;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>
                    @php
                        $value = $grandTotalPrice;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>
                    @php
                        $value = $grandTotalCash;
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </td>
                <td>--</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
