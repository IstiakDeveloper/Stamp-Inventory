<div class="mx-auto p-4">
    <!-- Month and Year Selector -->
    <div class="flex items-center mb-6 space-x-4">
        <div class="flex items-center">
            <label for="month" class="mr-2 text-lg font-semibold">Month:</label>
            <select id="month" wire:model.live="month" class="border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="flex items-center">
            <label for="year" class="mr-2 text-lg font-semibold">Year:</label>
            <select id="year" wire:model.live="year" class="border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                @for ($i = \Carbon\Carbon::now()->year - 5; $i <= \Carbon\Carbon::now()->year; $i++)
                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
            <thead class="bg-blue-500 text-white uppercase text-xs font-medium">
                <tr>
                    <th class="px-6 py-3 text-left">SL</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Fund In</th>
                    <th class="px-6 py-3 text-left">Fund Out</th>
                    <th class="px-6 py-3 text-left">Cash Receive</th>
                    <th class="px-6 py-3 text-left">Purchase</th>
                    <th class="px-6 py-3 text-left">Expenses</th>
                    <th class="px-6 py-3 text-left">Bank Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @if (isset($previousMonthData))
                    <tr class="bg-green-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900" colspan="2">Previous Month Data</td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $previousMonthCashIn = $previousMonthData['cash_in'] ?? 0;
                                $formattedPreviousMonthCashIn = $previousMonthCashIn == intval($previousMonthCashIn) ? number_format($previousMonthCashIn, 0) : number_format($previousMonthCashIn, 2);
                            @endphp
                            {{ $formattedPreviousMonthCashIn }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $previousMonthCashOut = $previousMonthData['cash_out'] ?? 0;
                                $formattedPreviousMonthCashOut = $previousMonthCashOut == intval($previousMonthCashOut) ? number_format($previousMonthCashOut, 0) : number_format($previousMonthCashOut, 2);
                            @endphp
                            {{ $formattedPreviousMonthCashOut }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $previousMonthCashReceive = $previousMonthData['cash_receive'] ?? 0;
                                $formattedPreviousMonthCashReceive = $previousMonthCashReceive == intval($previousMonthCashReceive) ? number_format($previousMonthCashReceive, 0) : number_format($previousMonthCashReceive, 2);
                            @endphp
                            {{ $formattedPreviousMonthCashReceive }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $previousMonthPurchasePrice = $previousMonthData['purchase_price'] ?? 0;
                                $formattedPreviousMonthPurchasePrice = $previousMonthPurchasePrice == intval($previousMonthPurchasePrice) ? number_format($previousMonthPurchasePrice, 0) : number_format($previousMonthPurchasePrice, 2);
                            @endphp
                            {{ $formattedPreviousMonthPurchasePrice }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $previousMonthExpenses = $previousMonthData['expenses'] ?? 0;
                                $formattedPreviousMonthExpenses = $previousMonthExpenses == intval($previousMonthExpenses) ? number_format($previousMonthExpenses, 0) : number_format($previousMonthExpenses, 2);
                            @endphp
                            {{ $formattedPreviousMonthExpenses }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            @php
                                $previousMonthBalance = $previousMonthData['balance'] ?? 0;
                                $formattedPreviousMonthBalance = $previousMonthBalance == intval($previousMonthBalance) ? number_format($previousMonthBalance, 0) : number_format($previousMonthBalance, 2);
                            @endphp
                            {{ $formattedPreviousMonthBalance }}
                        </td>
                    </tr>
                @endif

                @foreach ($data as $index => $entry)
                    @php
                        $rowClass = ($entry['cash_in'] || $entry['cash_out'] || $entry['cash_receive'] || $entry['purchase_price'] || $entry['expenses']) ? 'bg-yellow-200' : '';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($entry['date'])->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $cashIn = $entry['cash_in'] ?? 0;
                                $formattedCashIn = $cashIn == intval($cashIn) ? number_format($cashIn, 0) : number_format($cashIn, 2);
                            @endphp
                            {{ $formattedCashIn }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $cashOut = $entry['cash_out'] ?? 0;
                                $formattedCashOut = $cashOut == intval($cashOut) ? number_format($cashOut, 0) : number_format($cashOut, 2);
                            @endphp
                            {{ $formattedCashOut }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $cashReceive = $entry['cash_receive'] ?? 0;
                                $formattedCashReceive = $cashReceive == intval($cashReceive) ? number_format($cashReceive, 0) : number_format($cashReceive, 2);
                            @endphp
                            {{ $formattedCashReceive }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $purchasePrice = $entry['purchase_price'] ?? 0;
                                $formattedPurchasePrice = $purchasePrice == intval($purchasePrice) ? number_format($purchasePrice, 0) : number_format($purchasePrice, 2);
                            @endphp
                            {{ $formattedPurchasePrice }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $expenses = $entry['expenses'] ?? 0;
                                $formattedExpenses = $expenses == intval($expenses) ? number_format($expenses, 0) : number_format($expenses, 2);
                            @endphp
                            {{ $formattedExpenses }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            @php
                                $availableBalance = $entry['available_balance'] ?? 0;
                                $formattedAvailableBalance = $availableBalance == intval($availableBalance) ? number_format($availableBalance, 0) : number_format($availableBalance, 2);
                            @endphp
                            {{ $formattedAvailableBalance }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-300 text-gray-600 font-semibold">
                <tr>
                    <td colspan="2" class="px-6 py-3 text-left">Total</td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $totalCashIn = array_sum(array_column($data, 'cash_in'));
                            $formattedTotalCashIn = $totalCashIn == intval($totalCashIn) ? number_format($totalCashIn, 0) : number_format($totalCashIn, 2);
                        @endphp
                        {{ $formattedTotalCashIn }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $totalCashOut = array_sum(array_column($data, 'cash_out'));
                            $formattedTotalCashOut = $totalCashOut == intval($totalCashOut) ? number_format($totalCashOut, 0) : number_format($totalCashOut, 2);
                        @endphp
                        {{ $formattedTotalCashOut }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $totalCashReceive = array_sum(array_column($data, 'cash_receive'));
                            $formattedTotalCashReceive = $totalCashReceive == intval($totalCashReceive) ? number_format($totalCashReceive, 0) : number_format($totalCashReceive, 2);
                        @endphp
                        {{ $formattedTotalCashReceive }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $totalPurchasePrice = array_sum(array_column($data, 'purchase_price'));
                            $formattedTotalPurchasePrice = $totalPurchasePrice == intval($totalPurchasePrice) ? number_format($totalPurchasePrice, 0) : number_format($totalPurchasePrice, 2);
                        @endphp
                        {{ $formattedTotalPurchasePrice }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $totalExpenses = array_sum(array_column($data, 'expenses'));
                            $formattedTotalExpenses = $totalExpenses == intval($totalExpenses) ? number_format($totalExpenses, 0) : number_format($totalExpenses, 2);
                        @endphp
                        {{ $formattedTotalExpenses }}
                    </td>
                    <td class="px-6 py-3 text-left">
                        @php
                            $lastEntry = end($data);
                            $totalAvailableBalance = $lastEntry['available_balance'] ?? 0;
                            $formattedTotalAvailableBalance = $totalAvailableBalance == intval($totalAvailableBalance) ? number_format($totalAvailableBalance, 0) : number_format($totalAvailableBalance, 2);
                        @endphp
                        {{ $formattedTotalAvailableBalance }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="mt-4 text-right">
            <button wire:click="downloadPdf" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
        </div>
    </div>
</div>
