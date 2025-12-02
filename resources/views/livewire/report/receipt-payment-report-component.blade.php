<div class="p-8 bg-white rounded-md shadow-md">


    <div id="content-to-pdf">
        <h2 class="mb-2 text-5xl font-bold text-center text-gray-900">Mousumi Publication</h2>
        <h2 class="mb-8 text-3xl font-bold text-center text-gray-900">Receipts and Payments Statement</h2>
        <div class="flex mb-6 space-x-6">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <select wire:model.live="year" id="year"
                    class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    @for ($i = \Carbon\Carbon::now()->year; $i >= 2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Month Selector -->
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                <select wire:model.live="month" id="month"
                    class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Report Period Display -->
        <div class="mb-6 text-center">
            <h3 class="text-xl font-semibold text-gray-800">
                For the month of {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
            </h3>
        </div>

        <div class="flex space-x-2">
            <!-- Receipts Table -->
            <div class="flex-1 overflow-hidden bg-white rounded-md shadow-md">
                <h3 class="px-4 py-2 text-lg font-semibold text-center bg-gray-200">Receipts</h3>
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-sm font-medium text-left text-gray-700 border border-gray-300">
                                Description</th>
                            <th class="px-4 py-2 text-sm font-medium text-right text-gray-700 border border-gray-300">
                                Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">Opening Cash at Bank</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['opening_balance'])</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Stamp Sale Collection</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['stamp_sale_collection'])</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Fund Receive</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['fund_receive'])</td>
                        </tr>
                        <tr class="bg-green-50">
                            <td class="px-4 py-2 font-medium text-green-700 border">Loan Payments Received</td>
                            <td class="px-4 py-2 font-medium text-right text-green-700 border">@formatNumber($data['loan_payments_received'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 h-[2.4rem]"></td>
                            <td class="px-4 py-2 text-right border"></td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 font-bold border">Total Receipts</td>
                            <td class="px-4 py-2 font-bold text-right border">@formatNumber($data['total_receipt'])</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payments Table -->
            <div class="flex-1 overflow-hidden bg-white rounded-md shadow-md">
                <h3 class="px-4 py-2 text-lg font-semibold text-center bg-gray-200">Payments</h3>
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-sm font-bold text-center text-gray-700 border border-gray-300">
                                Particular</th>
                            <th class="px-4 py-2 text-sm font-bold text-center text-gray-700 border border-gray-300">
                                Amount (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">Purchase of Stamps</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['purchase_of_stamps'])</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Fund Refund</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['fund_refund'])</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Other Expenses</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['other_expenses'])</td>
                        </tr>
                        <tr class="bg-red-50">
                            <td class="px-4 py-2 font-medium text-red-700 border">Loans Given</td>
                            <td class="px-4 py-2 font-medium text-right text-red-700 border">@formatNumber($data['loans_given'])</td>
                        </tr>
                        <tr class="">
                            <td class="px-4 py-2 border">Closing Cash at Bank</td>
                            <td class="px-4 py-2 text-right border">@formatNumber($data['closing_balance'])</td>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2 font-bold border">Total Payments</td>
                            <td class="px-4 py-2 font-bold text-right border">@formatNumber($data['total_payment'])</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="p-4 mt-8 rounded-lg bg-gray-50">
            <h4 class="mb-3 text-lg font-semibold text-gray-800">Monthly Summary</h4>
            <div class="grid grid-cols-2 gap-4">
                <!-- Left Column -->
                <div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Opening Balance:</span>
                            <span class="font-medium">৳@formatNumber($data['opening_balance'])</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Closing Balance:</span>
                            <span class="font-medium">৳@formatNumber($data['closing_balance'])</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t">
                            <span class="font-medium">Net Change:</span>
                            @php
                                $netChange = $data['closing_balance'] - $data['opening_balance'];
                            @endphp
                            <span class="font-bold {{ $netChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $netChange >= 0 ? '+' : '' }}৳{{ number_format($netChange, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Loans Given:</span>
                            <span class="font-medium text-red-600">৳@formatNumber($data['loans_given'])</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Loan Payments Received:</span>
                            <span class="font-medium text-green-600">৳@formatNumber($data['loan_payments_received'])</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t">
                            <span class="font-medium">Net Loan Impact:</span>
                            @php
                                $netLoanImpact = $data['loans_given'] - $data['loan_payments_received'];
                            @endphp
                            <span class="font-bold {{ $netLoanImpact <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $netLoanImpact <= 0 ? '+' : '-' }}৳{{ number_format(abs($netLoanImpact), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Note -->
        <div class="mt-6 text-sm text-center text-gray-600">
            <p class="italic">
                Note: This statement shows receipts and payments for
                {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }} including loan transactions.
            </p>
            <p class="mt-2">
                Total Receipts: ৳@formatNumber($data['total_receipt']) = Total Payments: ৳@formatNumber($data['total_payment'])
                @if (abs($data['total_receipt'] - $data['total_payment']) < 0.01)
                    <span class="font-medium text-green-600">✓ Balanced</span>
                @else
                    <span class="font-medium text-red-600">⚠ Not Balanced</span>
                @endif
            </p>
        </div>
    </div>

    <div class="flex gap-4 mt-6">
        <button wire:click="downloadPdf"
            class="px-4 py-2 font-bold text-white transition-colors bg-blue-500 rounded hover:bg-blue-700">
            <i class="mr-2 fas fa-download"></i>
            Download PDF
        </button>

        <button onclick="window.print()"
            class="px-4 py-2 font-bold text-white transition-colors bg-gray-500 rounded hover:bg-gray-700">
            <i class="mr-2 fas fa-print"></i>
            Print Report
        </button>

        @if (
            (isset($data['loans_given']) && $data['loans_given'] > 0) ||
                (isset($data['loan_payments_received']) && $data['loan_payments_received'] > 0))
            <a href="{{ route('loan.management') }}"
                class="px-4 py-2 font-bold text-white transition-colors bg-purple-500 rounded hover:bg-purple-700">
                <i class="mr-2 fas fa-hand-holding-usd"></i>
                Manage Loans
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

            /* Hide buttons during print */
            button,
            .no-print {
                display: none !important;
            }
        }

        /* Enhanced styling for better PDF output */
        #content-to-pdf table {
            border-collapse: collapse;
        }

        #content-to-pdf th,
        #content-to-pdf td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        /* Loan highlight colors */
        .bg-green-50 {
            background-color: #f0fdf4;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .text-green-700 {
            color: #15803d;
        }

        .text-red-700 {
            color: #b91c1c;
        }
    </style>
</div>
