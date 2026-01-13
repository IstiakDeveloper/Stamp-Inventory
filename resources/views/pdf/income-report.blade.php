<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Income Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #000;
            padding: 20px;
            line-height: 1.4;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-header h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .report-info {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: bold;
        }

        .summary-section {
            margin-bottom: 20px;
            padding: 12px;
            border: 2px solid #000;
        }

        .summary-section h3 {
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 16.66%;
            text-align: center;
            padding: 6px;
            font-size: 9px;
        }

        .summary-label {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 11px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .data-table th {
            font-weight: bold;
            border: 2px solid #000;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .previous-row {
            font-weight: bold;
            border: 2px solid #000;
        }

        .previous-row td {
            font-weight: bold;
            border: 2px solid #000;
        }

        .total-row {
            font-weight: bold;
            border-top: 3px solid #000 !important;
            border-bottom: 3px solid #000 !important;
        }

        .total-row td {
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
            padding: 8px 6px;
            font-weight: bold;
        }

        .income-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #000;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #000;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Income Report</h2>
    </div>

    <div class="report-info">
        <strong>Period:</strong> {{ date('F', mktime(0, 0, 0, $currentMonth, 1)) }} {{ $currentYear }}
    </div>

    <!-- Income Summary by Type -->
    <div class="summary-section">
        <h3>Income Summary by Type</h3>
        <div class="summary-grid">
            @foreach(['extra' => 'Extra Income', 'other' => 'Other Income', 'commission' => 'Commission', 'bonus' => 'Bonus', 'refund' => 'Refund', 'investment_return' => 'Investment Return'] as $type => $label)
                <div class="summary-item">
                    <div class="summary-label">{{ $label }}</div>
                    <div class="summary-value">{{ rtrim(rtrim(number_format($incomeByType[$type] ?? 0, 2, '.', ''), '0'), '.') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">SL</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 15%;">Type</th>
                <th style="width: 37%;">Source</th>
                <th style="width: 15%;" class="text-right">Amount</th>
                <th style="width: 15%;" class="text-right">Total Income</th>
            </tr>
        </thead>
        <tbody>
            <tr class="previous-row">
                <td colspan="5" class="text-right font-bold">Previous Income:</td>
                <td class="text-right font-bold">{{ rtrim(rtrim(number_format($previousMonthTotalIncome, 2, '.', ''), '0'), '.') }}</td>
            </tr>

            @php
                $cumulativeIncome = $previousMonthTotalIncome;
            @endphp

            @foreach ($incomesRecords as $index => $record)
                @php
                    $cumulativeIncome += $record->amount;
                    $badgeClass = match($record->type) {
                        'extra' => 'badge-extra',
                        'commission' => 'badge-commission',
                        'bonus' => 'badge-bonus',
                        'refund' => 'badge-refund',
                        'investment_return' => 'badge-investment',
                        default => 'badge-other',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="income-badge {{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $record->type)) }}
                        </span>
                    </td>
                    <td>{{ $record->source }}</td>
                    <td class="text-right font-bold" style="color: #16a34a;">{{ rtrim(rtrim(number_format($record->amount, 2, '.', ''), '0'), '.') }}</td>
                    <td class="text-right font-bold">{{ rtrim(rtrim(number_format($cumulativeIncome, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="4" class="text-right font-bold">This Month Total:</td>
                <td class="text-right font-bold" style="color: #16a34a;">{{ rtrim(rtrim(number_format($totalAmount, 2, '.', ''), '0'), '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
