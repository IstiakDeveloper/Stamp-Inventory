<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fund Management Report - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</title>
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
            background-color: #f4f4f4;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h1 class="text-center">Fund Management Report</h1>
    <p class="text-center">Month: {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Note</th>
                <th>Type</th>
                <th>Fund In Amount</th>
                <th>Fund Out Amount</th>
                <th>Fund Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Previous:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    @php
                        $formattedPreviousBalance = ($previousBalance == intval($previousBalance)) 
                            ? number_format($previousBalance, 0) 
                            : number_format($previousBalance, 2);
                    @endphp
                    {{ $formattedPreviousBalance }}
                </td>
            </tr>

            @php
                $availableBalance = $previousBalance;
                $index = 2;
            @endphp

            @foreach($data as $entry)
                @php
                    $fundInAmount = $entry->type === 'fund_in' ? $entry->amount : 0;
                    $fundOutAmount = $entry->type === 'fund_out' ? $entry->amount : 0;
                    $availableBalance += $fundInAmount - $fundOutAmount;

                    // Format the amounts conditionally
                    $formattedFundInAmount = ($fundInAmount == intval($fundInAmount)) 
                        ? number_format($fundInAmount, 0) 
                        : number_format($fundInAmount, 2);
                    
                    $formattedFundOutAmount = ($fundOutAmount == intval($fundOutAmount)) 
                        ? number_format($fundOutAmount, 0) 
                        : number_format($fundOutAmount, 2);

                    $formattedAvailableBalance = ($availableBalance == intval($availableBalance)) 
                        ? number_format($availableBalance, 0) 
                        : number_format($availableBalance, 2);
                @endphp
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                    <td>{{ $entry->note }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                    <td>{{ $formattedFundInAmount }}</td>
                    <td>{{ $formattedFundOutAmount }}</td>
                    <td>{{ $formattedAvailableBalance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
