<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bank Balance Report</title>
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
            font-size: 8px;
            font-weight: bold;
        }

        table { width: 100%; border-collapse: collapse; font-size: 8px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        th { background-color: #000; color: #fff; font-weight: bold; }
        .total-row { background-color: #d0d0d0; font-weight: bold; border-top: 2px solid #000; }
        .highlight-row { background-color: #f5f5f5; }
        .loan-given { color: #dc2626; font-weight: bold; }
        .loan-received { color: #059669; font-weight: bold; }
        .previous-month { background-color: #e8e8e8; font-weight: bold; }
        .summary { margin-top: 15px; font-size: 8px; }
        .summary-item { margin: 3px 0; }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #333;
            font-size: 7px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ config('app.name', 'Mousumi Publication') }}</h1>
        <h2>Bank Balance Report</h2>
    </div>

    <div class="report-info">
        <strong>Period:</strong> {{ $month }} {{ $year }}
    </div>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>Fund In</th>
                    <th>Fund Out</th>
                    <th>Cash Receive</th>
                    <th>Purchase</th>
                    <th>Expenses</th>
                    <th>Loan Given</th>
                    <th>Loan Receive</th>
                    <th>Bank Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- Previous Month Data as the first row -->
                @if (isset($previousMonthData))
                    <tr class="previous-month">
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
                        <td class="loan-given">
                            @php
                                $previousMonthLoansGiven = $previousMonthData['loans_given'] ?? 0;
                                echo $previousMonthLoansGiven == intval($previousMonthLoansGiven) ? number_format($previousMonthLoansGiven, 0) : number_format($previousMonthLoansGiven, 2);
                            @endphp
                        </td>
                        <td class="loan-received">
                            @php
                                $previousMonthLoanPayments = $previousMonthData['loan_payments_received'] ?? 0;
                                echo $previousMonthLoanPayments == intval($previousMonthLoanPayments) ? number_format($previousMonthLoanPayments, 0) : number_format($previousMonthLoanPayments, 2);
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
                        $highlightRow = (
                            ($entry['cash_in'] ?? 0) ||
                            ($entry['cash_out'] ?? 0) ||
                            ($entry['cash_receive'] ?? 0) ||
                            ($entry['purchase_price'] ?? 0) ||
                            ($entry['expenses'] ?? 0) ||
                            ($entry['loans_given'] ?? 0) ||
                            ($entry['loan_payments_received'] ?? 0)
                        ) ? 'highlight-row' : '';
                    @endphp
                    <tr class="{{ $highlightRow }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($entry['date'])) }}</td>
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
                        <td class="{{ ($entry['loans_given'] ?? 0) > 0 ? 'loan-given' : '' }}">
                            @php
                                $loansGiven = $entry['loans_given'] ?? 0;
                                echo $loansGiven == intval($loansGiven) ? number_format($loansGiven, 0) : number_format($loansGiven, 2);
                            @endphp
                        </td>
                        <td class="{{ ($entry['loan_payments_received'] ?? 0) > 0 ? 'loan-received' : '' }}">
                            @php
                                $loanPayments = $entry['loan_payments_received'] ?? 0;
                                echo $loanPayments == intval($loanPayments) ? number_format($loanPayments, 0) : number_format($loanPayments, 2);
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
                        <td colspan="10">No data available for the selected month.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">Monthly Total</td>
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
                    <td class="loan-given">
                        @php
                            $totalLoansGiven = array_sum(array_column($data, 'loans_given'));
                            echo $totalLoansGiven == intval($totalLoansGiven) ? number_format($totalLoansGiven, 0) : number_format($totalLoansGiven, 2);
                        @endphp
                    </td>
                    <td class="loan-received">
                        @php
                            $totalLoanPayments = array_sum(array_column($data, 'loan_payments_received'));
                            echo $totalLoanPayments == intval($totalLoanPayments) ? number_format($totalLoanPayments, 0) : number_format($totalLoanPayments, 2);
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

        <!-- Summary Section -->
        <div class="summary">
            <h3 style="margin-bottom: 10px; font-size: 12px;">Monthly Summary</h3>

            @if(isset($data) && count($data) > 0)
                @php
                    $monthlyTotalIn = array_sum(array_column($data, 'cash_in')) +
                                     array_sum(array_column($data, 'cash_receive')) +
                                     array_sum(array_column($data, 'loan_payments_received'));
                    $monthlyTotalOut = array_sum(array_column($data, 'cash_out')) +
                                      array_sum(array_column($data, 'purchase_price')) +
                                      array_sum(array_column($data, 'expenses')) +
                                      array_sum(array_column($data, 'loans_given'));
                    $monthlyNet = $monthlyTotalIn - $monthlyTotalOut;

                    $monthlyLoansGiven = array_sum(array_column($data, 'loans_given'));
                    $monthlyPaymentsReceived = array_sum(array_column($data, 'loan_payments_received'));
                    $netLoanImpact = $monthlyLoansGiven - $monthlyPaymentsReceived;
                @endphp

                <div class="summary-item"><strong>Total Money In:</strong> {{ number_format($monthlyTotalIn, 2) }}</div>
                <div class="summary-item"><strong>Total Money Out:</strong> {{ number_format($monthlyTotalOut, 2) }}</div>
                <div class="summary-item"><strong>Net Change:</strong> {{ number_format($monthlyNet, 2) }}</div>

                <div style="margin-top: 10px;">
                    <div class="summary-item"><strong>Loans Given:</strong> <span style="color: #dc2626;">{{ number_format($monthlyLoansGiven, 2) }}</span></div>
                    <div class="summary-item"><strong>Loan Payments Received:</strong> <span style="color: #059669;">{{ number_format($monthlyPaymentsReceived, 2) }}</span></div>
                    <div class="summary-item"><strong>Net Loan Impact:</strong> <span style="color: {{ $netLoanImpact <= 0 ? '#059669' : '#dc2626' }};">{{ number_format($netLoanImpact, 2) }}</span></div>
                </div>

                @if(isset($previousMonthData))
                    @php
                        $startBalance = $previousMonthData['balance'] ?? 0;
                        $endBalance = end($data)['available_balance'] ?? 0;
                        $balanceChange = $endBalance - $startBalance;
                    @endphp
                    <div style="margin-top: 10px;">
                        <div class="summary-item"><strong>Opening Balance:</strong> {{ number_format($startBalance, 2) }}</div>
                        <div class="summary-item"><strong>Closing Balance:</strong> {{ number_format($endBalance, 2) }}</div>
                        <div class="summary-item"><strong>Balance Change:</strong> <span style="color: {{ $balanceChange >= 0 ? '#059669' : '#dc2626' }};">{{ number_format($balanceChange, 2) }}</span></div>
                    </div>
                @endif
            @endif
        </div>

        <div class="footer">
            Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
        </div>
</body>
</html>
