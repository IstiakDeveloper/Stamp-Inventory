<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md">
    <!-- Date and Year Selection -->
    <div class="flex items-center gap-4">
        <div class="mb-4">
            <label for="month" class="block text-sm font-medium text-gray-700">Select Month:</label>
            <select id="month" wire:model.live="selectedMonth" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm form-select focus:ring-blue-500 focus:border-blue-500">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label for="year" class="block text-sm font-medium text-gray-700">Select Year:</label>
            <select id="year" wire:model.live="selectedYear" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm form-select focus:ring-blue-500 focus:border-blue-500">
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Table -->
    <div id="content-to-pdf" class="mt-6 overflow-x-auto">
        <h3 class="mb-2 text-xl font-bold">
            Stock Register for
            @if ($selectedMonth && $selectedYear)
                {{ \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F') }} {{ $selectedYear }}
            @else
                (Please select a month and year)
            @endif
        </h3>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="text-white bg-blue-600">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase border-b border-gray-300">SL</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase border-b border-gray-300">Date</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase border-b border-gray-300">Sets</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase border-b border-gray-300">Set Price</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase border-b border-gray-300">Price</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase border-b border-gray-300">Cash Received</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right uppercase border-b border-gray-300">Due</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold bg-gray-100">
                    <td></td>
                    <td class="px-6 py-4 border-b border-gray-300"><strong>Previous:</strong></td>
                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                        @php
                            $value = $soFarSets;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>

                    <td class="text-center">--</td>
                    <td class="text-center">--</td>
                    <td class="px-6 py-4 text-center border-b border-gray-300"><strong>{{ rtrim(rtrim(number_format($soFarCash, 4, '.', ''), '0'), '.') }}</strong></td>
                    <td class="px-6 py-4 text-right border-b border-gray-300"><strong>{{ rtrim(rtrim(number_format($soFarDue, 4, '.', ''), '0'), '.') }}</strong></td>
                </tr>

                @foreach ($completeMonth as $day)
                    @php
                        $sale = $sales->firstWhere('date', $day->format('Y-m-d'));
                        $hasValue = !empty($sale['sets']) || !empty($sale['set_price']) || !empty($sale['price']) || !empty($sale['cash']) || !empty($sale['due']);
                    @endphp
                    <tr class="{{ $hasValue ? 'bg-gray-100' : ($loop->odd ? 'bg-white' : '') }} border-b border-gray-300">
                        <td class="px-6 py-4 border-b border-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">{{ $day->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">{{ $sale['sets'] ?? 0 }}</td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">{{ rtrim(rtrim(number_format($sale['set_price'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">{{ rtrim(rtrim(number_format($sale['price'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">{{ rtrim(rtrim(number_format($sale['cash'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 text-right border-b border-gray-300">{{ rtrim(rtrim(number_format($sale['due'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                    </tr>
                @endforeach

                <tr class="font-bold bg-gray-200 border-b border-gray-400">
                    <td class="px-6 py-4 text-center border-t border-gray-300" colspan="2">Current Month Total</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalSetPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300"></td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalCashReceived, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-right border-t border-gray-300">{{ rtrim(rtrim(number_format($totalDue, 4, '.', ''), '0'), '.') }}</td>
                </tr>

                <tr class="font-bold bg-yellow-200">
                    <td class="px-6 py-4 text-center border-t border-gray-300" colspan="2">Total:</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalSetPrice + $soFarSets, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300"></td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-center border-t border-gray-300">{{ rtrim(rtrim(number_format($totalCashReceived + $soFarCash, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 text-right border-t border-gray-300">{{ rtrim(rtrim(number_format($totalDue + $soFarDue, 4, '.', ''), '0'), '.') }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <button wire:click="downloadPdf" class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
        Download PDF
    </button>
</div>

<x-pdf-viewer-script />
