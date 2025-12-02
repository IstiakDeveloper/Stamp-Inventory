<div>
    <div class="p-4 mx-auto">
        <!-- Month and Year Selector -->
        <div class="flex items-center mb-6 space-x-4">
            <div class="flex items-center">
                <label for="month" class="mr-2 text-lg font-semibold">Month:</label>
                <select id="month" wire:model.live="month"
                    class="p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($monthOptions as $monthValue => $monthName)
                        <option value="{{ $monthValue }}" {{ $monthValue == $month ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <label for="year" class="mr-2 text-lg font-semibold">Year:</label>
                <select id="year" wire:model.live="year"
                    class="p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($yearOptions as $yearOption)
                        <option value="{{ $yearOption }}" {{ $yearOption == $year ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Report Header -->
        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Bank Balance Report</h2>
            <p class="text-gray-600">{{ $monthName }} {{ $year }}</p>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white divide-y divide-gray-200 rounded-lg shadow-md">
                <thead class="text-xs font-medium text-white uppercase bg-blue-500">
                    <tr>
                        <th class="px-3 py-3 text-left">SL</th>
                        <th class="px-3 py-3 text-left">Date</th>
                        <th class="px-3 py-3 text-left">Fund In</th>
                        <th class="px-3 py-3 text-left">Fund Out</th>
                        <th class="px-3 py-3 text-left">Cash Receive</th>
                        <th class="px-3 py-3 text-left">Purchase</th>
                        <th class="px-3 py-3 text-left">Expenses</th>
                        <th class="px-3 py-3 text-left">Loan Given</th>
                        <th class="px-3 py-3 text-left">Loan Receive</th>
                        <th class="px-3 py-3 text-left">Bank Balance</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 bg-white divide-y divide-gray-200">
                    @if (isset($previousMonthData))
                        <tr class="bg-green-200">
                            <td class="px-3 py-4 text-sm font-medium text-gray-900" colspan="2">Previous Month Data
                            </td>
                            <td class="px-3 py-4 text-gray-500">
                                {{ number_format($previousMonthData['cash_in'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 text-gray-500">
                                {{ number_format($previousMonthData['cash_out'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 text-gray-500">
                                {{ number_format($previousMonthData['cash_receive'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 text-gray-500">
                                {{ number_format($previousMonthData['purchase_price'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 text-gray-500">
                                {{ number_format($previousMonthData['expenses'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 font-medium text-red-600">
                                {{ number_format($previousMonthData['loans_given'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 font-medium text-green-600">
                                {{ number_format($previousMonthData['loan_payments_received'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 font-semibold text-gray-900">
                                {{ number_format($previousMonthData['balance'] ?? 0, 2) }}
                            </td>
                        </tr>
                    @endif

                    @foreach ($data as $index => $entry)
                        @php
                            $hasActivity =
                                $entry['cash_in'] ||
                                $entry['cash_out'] ||
                                $entry['cash_receive'] ||
                                $entry['purchase_price'] ||
                                $entry['expenses'] ||
                                $entry['loans_given'] ||
                                $entry['loan_payments_received'];
                            $rowClass = $hasActivity ? 'bg-yellow-100' : '';
                        @endphp
                        <tr class="{{ $rowClass }} hover:bg-gray-50">
                            <td class="px-3 py-3 text-sm font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-3 py-3 text-gray-700">
                                {{ \Carbon\Carbon::parse($entry['date'])->format('d-m-Y') }}
                            </td>
                            <td class="px-3 py-3 text-gray-600">
                                {{ number_format($entry['cash_in'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3 text-gray-600">
                                {{ number_format($entry['cash_out'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3 text-gray-600">
                                {{ number_format($entry['cash_receive'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3 text-gray-600">
                                {{ number_format($entry['purchase_price'] ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3 text-gray-600">
                                {{ number_format($entry['expenses'] ?? 0, 2) }}
                            </td>
                            <td
                                class="px-3 py-3 {{ $entry['loans_given'] > 0 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ number_format($entry['loans_given'] ?? 0, 2) }}
                            </td>
                            <td
                                class="px-3 py-3 {{ $entry['loan_payments_received'] > 0 ? 'text-green-600 font-semibold' : 'text-gray-600' }}">
                                {{ number_format($entry['loan_payments_received'] ?? 0, 2) }}
                            </td>
                            <td
                                class="px-3 py-3 font-bold {{ $entry['available_balance'] >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                                {{ number_format($entry['available_balance'] ?? 0, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="font-semibold text-gray-700 bg-gray-100">
                    <tr>
                        <td colspan="2" class="px-3 py-3 font-bold text-left">Monthly Total</td>
                        <td class="px-3 py-3 text-left">
                            {{ number_format(array_sum(array_column($data, 'cash_in')), 2) }}
                        </td>
                        <td class="px-3 py-3 text-left">
                            {{ number_format(array_sum(array_column($data, 'cash_out')), 2) }}
                        </td>
                        <td class="px-3 py-3 text-left">
                            {{ number_format(array_sum(array_column($data, 'cash_receive')), 2) }}
                        </td>
                        <td class="px-3 py-3 text-left">
                            {{ number_format(array_sum(array_column($data, 'purchase_price')), 2) }}
                        </td>
                        <td class="px-3 py-3 text-left">
                            {{ number_format(array_sum(array_column($data, 'expenses')), 2) }}
                        </td>
                        <td class="px-3 py-3 font-bold text-left text-red-600">
                            {{ number_format(array_sum(array_column($data, 'loans_given')), 2) }}
                        </td>
                        <td class="px-3 py-3 font-bold text-left text-green-600">
                            {{ number_format(array_sum(array_column($data, 'loan_payments_received')), 2) }}
                        </td>
                        <td class="px-3 py-3 font-bold text-left text-blue-600">
                            @php
                                $lastEntry = end($data);
                            @endphp
                            {{ number_format($lastEntry['available_balance'] ?? 0, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3">
            <!-- Monthly Summary -->
            <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                <h3 class="mb-3 text-lg font-semibold text-blue-800">
                    <i class="mr-2 fas fa-calendar-month"></i>
                    Monthly Summary
                </h3>
                <div class="space-y-2 text-sm">
                    @php
                        $monthlyTotalIn =
                            array_sum(array_column($data, 'cash_in')) +
                            array_sum(array_column($data, 'cash_receive')) +
                            array_sum(array_column($data, 'loan_payments_received'));
                        $monthlyTotalOut =
                            array_sum(array_column($data, 'cash_out')) +
                            array_sum(array_column($data, 'purchase_price')) +
                            array_sum(array_column($data, 'expenses')) +
                            array_sum(array_column($data, 'loans_given'));
                        $monthlyNet = $monthlyTotalIn - $monthlyTotalOut;
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-blue-700">Total In:</span>
                        <span class="font-semibold text-green-600">৳{{ number_format($monthlyTotalIn, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-700">Total Out:</span>
                        <span class="font-semibold text-red-600">৳{{ number_format($monthlyTotalOut, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-medium text-blue-700">Net Change:</span>
                        <span class="font-bold {{ $monthlyNet >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ৳{{ number_format($monthlyNet, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Loan Summary -->
            <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                <h3 class="mb-3 text-lg font-semibold text-purple-800">
                    <i class="mr-2 fas fa-hand-holding-usd"></i>
                    Loan Summary
                </h3>
                <div class="space-y-2 text-sm">
                    @php
                        $monthlyLoansGiven = array_sum(array_column($data, 'loans_given'));
                        $monthlyPaymentsReceived = array_sum(array_column($data, 'loan_payments_received'));
                        $netLoanImpact = $monthlyLoansGiven - $monthlyPaymentsReceived;
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-purple-700">Loans Given:</span>
                        <span class="font-semibold text-red-600">৳{{ number_format($monthlyLoansGiven, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-700">Payments Received:</span>
                        <span
                            class="font-semibold text-green-600">৳{{ number_format($monthlyPaymentsReceived, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-medium text-purple-700">Net Impact:</span>
                        <span class="font-bold {{ $netLoanImpact <= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ৳{{ number_format($netLoanImpact, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Balance Info -->
            <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                <h3 class="mb-3 text-lg font-semibold text-green-800">
                    <i class="mr-2 fas fa-wallet"></i>
                    Balance Info
                </h3>
                <div class="space-y-2 text-sm">
                    @php
                        $startBalance = $previousMonthData['balance'] ?? 0;
                        $endBalance = end($data)['available_balance'] ?? 0;
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-green-700">Start Balance:</span>
                        <span class="font-semibold">৳{{ number_format($startBalance, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-green-700">End Balance:</span>
                        <span class="font-semibold">৳{{ number_format($endBalance, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-medium text-green-700">Change:</span>
                        <span
                            class="font-bold {{ $endBalance - $startBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ৳{{ number_format($endBalance - $startBalance, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Button -->
        <div class="mt-6 text-center">
            <button wire:click="downloadPdf"
                class="px-6 py-3 text-white transition-colors bg-blue-500 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="mr-2 fas fa-download"></i>
                Download PDF Report
            </button>
        </div>
    </div>

    <style>
        /* Custom styling for better appearance */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        /* Responsive table improvements */
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }

            .overflow-x-auto::-webkit-scrollbar {
                height: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            th,
            td {
                white-space: nowrap;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none;
            }

            table {
                font-size: 12px;
            }
        }
    </style>

</div>

<x-pdf-viewer-script />
