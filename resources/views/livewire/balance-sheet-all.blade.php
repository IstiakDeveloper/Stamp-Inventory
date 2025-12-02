<div class="p-6 mx-auto bg-white rounded-lg shadow-md">
    <div class="mt-4" id="content-to-pdf">
        <h2 class="mb-2 text-5xl font-bold text-center text-gray-900">Mousumi Publication</h2>
        <h2 class="mb-8 text-3xl font-bold text-center text-gray-900">Balance Sheet</h2>

        <!-- Report Period Display -->
        <div class="mb-6 text-center">
            <h3 class="text-xl font-semibold text-gray-800">
                As on
                {{ date('F d, Y', mktime(0, 0, 0, $selectedMonth, date('t', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)), $selectedYear)) }}
            </h3>
        </div>

        <div class="flex items-center gap-4 mb-6">
            <div class="mb-4">
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <select wire:model.live="selectedYear" id="year"
                    class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                <select wire:model.live="selectedMonth" id="month"
                    class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach ($availableMonths as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex border-l border-r border-gray-300">
            <!-- Left Column -->
            <div class="w-1/2 border-r border-gray-300">
                <table class="min-w-full border border-gray-300 divide-y divide-gray-300">
                    <caption class="px-6 py-3 text-lg font-bold text-center border border-gray-300 bg-gray-50">Funds &
                        Liabilities</caption>
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-3 text-xs font-bold tracking-wider text-left uppercase border border-gray-300">
                                Particulars</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-bold tracking-wider text-right uppercase border border-gray-300">
                                Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap">Funds</td>
                            <td class="px-6 py-4 text-sm text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($totalCashIn)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap">Net Profit</td>
                            <td class="px-6 py-4 text-sm text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($netProfit)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap"></td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap"></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold border-r border-gray-300 whitespace-nowrap">Total
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-right whitespace-nowrap">@formatNumber($totalCashIn + $netProfit)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Right Column -->
            <div class="w-1/2">
                <table class="min-w-full border border-gray-300 divide-y divide-gray-300">
                    <caption class="px-6 py-3 text-lg font-bold text-center border border-gray-300 bg-gray-50">Property
                        & Assets</caption>
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col"
                                class="px-6 py-3 text-xs font-bold tracking-wider text-left uppercase border border-gray-300">
                                Particulars</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-bold tracking-wider text-right uppercase border border-gray-300">
                                Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap">Bank Balance</td>
                            <td class="px-6 py-4 text-sm text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($totalBankOrHandBalance)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap">Branches Due</td>
                            <td class="px-6 py-4 text-sm text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($outstandingTotal)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 text-sm border-r border-gray-300 whitespace-nowrap">Stock Stamp Buy
                                Price</td>
                            <td class="px-6 py-4 text-sm text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($stockStampBuyPrice)</td>
                        </tr>
                        <tr class="hover:bg-gray-100 bg-purple-50">
                            <td
                                class="px-6 py-4 text-sm font-medium text-purple-700 border-r border-gray-300 whitespace-nowrap">
                                Loan Receivables</td>
                            <td
                                class="px-6 py-4 text-sm font-medium text-right text-purple-700 border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($loanReceivables)</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold border-r border-gray-300 whitespace-nowrap">Total
                            </td>
                            <td
                                class="px-6 py-4 text-sm font-bold text-right border-r border-gray-300 whitespace-nowrap">
                                @formatNumber($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice + $loanReceivables)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Balance Verification -->
        <div class="p-4 mt-6 border border-gray-300 rounded-lg bg-gray-50">
            <div class="text-center">
                @php
                    $totalLiabilities = $totalCashIn + $netProfit;
                    $totalAssets = $outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice + $loanReceivables;
                    $balanceDifference = abs($totalLiabilities - $totalAssets);
                @endphp

                <h4 class="mb-2 text-lg font-semibold text-gray-800">Balance Sheet Verification</h4>
                <div class="flex justify-center space-x-8 text-sm">
                    <div>
                        <span class="font-medium">Total Liabilities:</span>
                        <span class="ml-2">৳@formatNumber($totalLiabilities)</span>
                    </div>
                    <div>
                        <span class="font-medium">Total Assets:</span>
                        <span class="ml-2">৳@formatNumber($totalAssets)</span>
                    </div>
                </div>

                <div class="mt-2">
                    @if ($balanceDifference < 0.01)
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                            <i class="mr-1 fas fa-check-circle"></i>
                            Balance Sheet is Balanced
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                            <i class="mr-1 fas fa-exclamation-triangle"></i>
                            Difference: ৳{{ number_format($balanceDifference, 2) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Notes -->
        <div class="mt-4 text-xs text-center text-gray-600">
            <p>
                <strong>Note:</strong> Loan Receivables represent the outstanding amount from loans given to
                individuals/entities.
                This amount is recovered as borrowers make payments.
            </p>
            <p class="mt-1">
                Generated on: {{ now()->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 mt-6">
        <button wire:click="downloadPdf"
            class="px-4 py-2 font-bold text-white transition-colors bg-blue-500 rounded hover:bg-blue-700">
            <i class="mr-2 fas fa-download"></i>
            Download PDF
        </button>

        <button onclick="window.print()"
            class="px-4 py-2 font-bold text-white transition-colors bg-gray-500 rounded hover:bg-gray-700">
            <i class="mr-2 fas fa-print"></i>
            Print Balance Sheet
        </button>

        @if ($loanReceivables > 0)
            <a href="{{ route('loan.management') }}"
                class="px-4 py-2 font-bold text-white transition-colors bg-purple-500 rounded hover:bg-purple-700">
                <i class="mr-2 fas fa-hand-holding-usd"></i>
                Manage Loans (৳{{ number_format($loanReceivables, 0) }})
            </a>
        @endif
    </div>

    <x-pdf-viewer-script />

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #content-to-pdf,
            #content-to-pdf * {
                visibility: visible;
            }

            #content-to-pdf {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* Hide buttons and other non-essential elements during print */
            button,
            .no-print {
                display: none !important;
            }
        }

        /* Enhanced styling for loan receivables */
        .bg-purple-50 {
            background-color: #faf5ff;
        }

        .text-purple-700 {
            color: #7c3aed;
        }

        /* Balance verification styling */
        .fas {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }
    </style>
</div>
