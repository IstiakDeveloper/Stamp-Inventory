<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { width: 100%; padding: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid #dddddd; padding: 5px; text-align: left; }
        th { background-color: #007bff; color: #ffffff; }
        .total-row { background-color: #f2f2f2; font-weight: bold; }
        .highlight-row { background-color: #fff8e1; } /* Similar to bg-yellow-200 */
        h1 { font-size: 14px; margin-bottom: 10px; }
        p { font-size: 10px; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bank Balance Report</h1>
        <p><strong>Month:</strong> {{ $month }}</p>
        <p><strong>Year:</strong> {{ $year }}</p>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>Cash In</th>
                    <th>Cash Out</th>
                    <th>Cash Receive</th>
                    <th>Purchase</th>
                    <th>Expenses</th>
                    <th>Bank Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- Previous Month Data as the first row -->
                @if (isset($previousMonthData))
                    <tr>
                        <td colspan="2"><strong>Previous Month Data</strong></td>
                        <td>
                            @php
                                $previousMonthCashIn = $previousMonthData['cash_in'] ?? 0;
                                echo $previousMonthCashIn == intval($previousMonthCashIn) ? number_format($previousMonthCashIn, 0) : number_format($previousMonthCashIn, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $previousMonthCashOut = $previousMonthData['cash_out'] ?? 0;
                                echo $previousMonthCashOut == intval($previousMonthCashOut) ? number_format($previousMonthCashOut, 0) : number_format($previousMonthCashOut, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $previousMonthCashReceive = $previousMonthData['cash_receive'] ?? 0;
                                echo $previousMonthCashReceive == intval($previousMonthCashReceive) ? number_format($previousMonthCashReceive, 0) : number_format($previousMonthCashReceive, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $previousMonthPurchasePrice = $previousMonthData['purchase_price'] ?? 0;
                                echo $previousMonthPurchasePrice == intval($previousMonthPurchasePrice) ? number_format($previousMonthPurchasePrice, 0) : number_format($previousMonthPurchasePrice, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $previousMonthExpenses = $previousMonthData['expenses'] ?? 0;
                                echo $previousMonthExpenses == intval($previousMonthExpenses) ? number_format($previousMonthExpenses, 0) : number_format($previousMonthExpenses, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $previousMonthBalance = $previousMonthData['balance'] ?? 0;
                                echo $previousMonthBalance == intval($previousMonthBalance) ? number_format($previousMonthBalance, 0) : number_format($previousMonthBalance, 2);
                            @endphp
                        </td>
                    </tr>
                @endif

                <!-- Current Month Data -->
                @forelse ($data as $index => $entry)
                    @php
                        // Determine if the row should be highlighted
                        $highlightRow = ($entry['cash_in'] || $entry['cash_out'] || $entry['cash_receive'] || $entry['purchase_price'] || $entry['expenses']) ? 'highlight-row' : '';
                    @endphp
                    <tr class="{{ $highlightRow }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M, Y') }}</td>
                        <td>
                            @php
                                $cashIn = $entry['cash_in'] ?? 0;
                                echo $cashIn == intval($cashIn) ? number_format($cashIn, 0) : number_format($cashIn, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $cashOut = $entry['cash_out'] ?? 0;
                                echo $cashOut == intval($cashOut) ? number_format($cashOut, 0) : number_format($cashOut, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $cashReceive = $entry['cash_receive'] ?? 0;
                                echo $cashReceive == intval($cashReceive) ? number_format($cashReceive, 0) : number_format($cashReceive, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $purchasePrice = $entry['purchase_price'] ?? 0;
                                echo $purchasePrice == intval($purchasePrice) ? number_format($purchasePrice, 0) : number_format($purchasePrice, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $expenses = $entry['expenses'] ?? 0;
                                echo $expenses == intval($expenses) ? number_format($expenses, 0) : number_format($expenses, 2);
                            @endphp
                        </td>
                        <td>
                            @php
                                $availableBalance = $entry['available_balance'] ?? 0;
                                echo $availableBalance == intval($availableBalance) ? number_format($availableBalance, 0) : number_format($availableBalance, 2);
                            @endphp
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No data available for the selected month.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td>
                        @php
                            $totalCashIn = array_sum(array_column($data, 'cash_in'));
                            echo $totalCashIn == intval($totalCashIn) ? number_format($totalCashIn, 0) : number_format($totalCashIn, 2);
                        @endphp
                    </td>
                    <td>
                        @php
                            $totalCashOut = array_sum(array_column($data, 'cash_out'));
                            echo $totalCashOut == intval($totalCashOut) ? number_format($totalCashOut, 0) : number_format($totalCashOut, 2);
                        @endphp
                    </td>
                    <td>
                        @php
                            $totalCashReceive = array_sum(array_column($data, 'cash_receive'));
                            echo $totalCashReceive == intval($totalCashReceive) ? number_format($totalCashReceive, 0) : number_format($totalCashReceive, 2);
                        @endphp
                    </td>
                    <td>
                        @php
                            $totalPurchasePrice = array_sum(array_column($data, 'purchase_price'));
                            echo $totalPurchasePrice == intval($totalPurchasePrice) ? number_format($totalPurchasePrice, 0) : number_format($totalPurchasePrice, 2);
                        @endphp
                    </td>
                    <td>
                        @php
                            $totalExpenses = array_sum(array_column($data, 'expenses'));
                            echo $totalExpenses == intval($totalExpenses) ? number_format($totalExpenses, 0) : number_format($totalExpenses, 2);
                        @endphp
                    </td>
                    <td>
                        @php
                            $lastEntry = end($data);
                            $totalAvailableBalance = $lastEntry['available_balance'] ?? 0;
                            echo $totalAvailableBalance == intval($totalAvailableBalance) ? number_format($totalAvailableBalance, 0) : number_format($totalAvailableBalance, 2);
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
