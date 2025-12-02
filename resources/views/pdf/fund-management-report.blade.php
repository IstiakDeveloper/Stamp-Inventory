<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fund Management Report</title>
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
            font-weight: bold;
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

        .previous-row {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }

        .fund-in { color: #006400; font-weight: bold; }
        .fund-out { color: #8B0000; font-weight: bold; }

        .col-sl { width: 6%; }
        .col-date { width: 12%; }
        .col-note { width: 30%; }
        .col-type { width: 12%; }
        .col-fund-in { width: 13%; }
        .col-fund-out { width: 13%; }
        .col-balance { width: 14%; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Fund Management Report</h2>
    </div>

    <div class="report-info">
        <strong>Period:</strong> {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-sl">SL</th>
                <th class="col-date">Date</th>
                <th class="col-note">Note</th>
                <th class="col-type">Type</th>
                <th class="col-fund-in">Fund In</th>
                <th class="col-fund-out">Fund Out</th>
                <th class="col-balance">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr class="previous-row">
                <td class="text-center">1</td>
                <td class="text-center"><strong>Previous:</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">
                    @php
                        $formattedPreviousBalance = ($previousBalance == intval($previousBalance))
                            ? number_format($previousBalance, 0)
                            : rtrim(rtrim(number_format($previousBalance, 2, '.', ''), '0'), '.');
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

                    $formattedFundInAmount = ($fundInAmount == intval($fundInAmount))
                        ? number_format($fundInAmount, 0)
                        : rtrim(rtrim(number_format($fundInAmount, 2, '.', ''), '0'), '.');

                    $formattedFundOutAmount = ($fundOutAmount == intval($fundOutAmount))
                        ? number_format($fundOutAmount, 0)
                        : rtrim(rtrim(number_format($fundOutAmount, 2, '.', ''), '0'), '.');

                    $formattedAvailableBalance = ($availableBalance == intval($availableBalance))
                        ? number_format($availableBalance, 0)
                        : rtrim(rtrim(number_format($availableBalance, 2, '.', ''), '0'), '.');
                @endphp
                <tr>
                    <td class="text-center">{{ $index++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</td>
                    <td class="text-left">{{ $entry->note }}</td>
                    <td class="text-center">{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                    <td class="text-right {{ $fundInAmount > 0 ? 'fund-in' : '' }}">{{ $formattedFundInAmount }}</td>
                    <td class="text-right {{ $fundOutAmount > 0 ? 'fund-out' : '' }}">{{ $formattedFundOutAmount }}</td>
                    <td class="text-right">{{ $formattedAvailableBalance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
