<div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
    <!-- Date and Year Selection -->
    <div class="flex items-center gap-4">
        <div class="mb-4">
            <label for="month" class="block text-sm font-medium text-gray-700">Select Month:</label>
            <select id="month" wire:model.live="selectedMonth" class="p-2 form-select mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label for="year" class="block text-sm font-medium text-gray-700">Select Year:</label>
            <select id="year" wire:model.live="selectedYear" class="p-2 form-select mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Table -->
    <div id="content-to-pdf" class="overflow-x-auto mt-6">
        <h3 class="text-xl font-bold mb-2">
            Stock Register for 
            @if ($selectedMonth && $selectedYear)
                {{ \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F') }} {{ $selectedYear }}
            @else
                (Please select a month and year)
            @endif
        </h3>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">SL</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Date</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Sets</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Set Price</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Cash Received</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-b border-gray-300">Due</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-100 font-bold">
                    <td></td>
                    <td class="px-6 py-4 border-b border-gray-300"><strong>Previous:</strong></td>
                    <td class="px-6 py-4 text-sm text-gray-900 text-center">
                        @php
                            $value = $soFarSets;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                
                    <td class="text-center">--</td>
                    <td class="text-center">--</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center"><strong>{{ rtrim(rtrim(number_format($soFarCash, 4, '.', ''), '0'), '.') }}</strong></td>
                    <td class="px-6 py-4 border-b border-gray-300 text-right"><strong>{{ rtrim(rtrim(number_format($soFarDue, 4, '.', ''), '0'), '.') }}</strong></td>
                </tr>

                @foreach ($completeMonth as $day)
                    @php
                        $sale = $sales->firstWhere('date', $day->format('Y-m-d'));
                        $hasValue = !empty($sale['sets']) || !empty($sale['set_price']) || !empty($sale['price']) || !empty($sale['cash']) || !empty($sale['due']);
                    @endphp
                    <tr class="{{ $hasValue ? 'bg-gray-100' : ($loop->odd ? 'bg-white' : '') }} border-b border-gray-300">
                        <td class="px-6 py-4 border-b border-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ $day->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ $sale['sets'] ?? 0 }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ rtrim(rtrim(number_format($sale['set_price'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ rtrim(rtrim(number_format($sale['price'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ rtrim(rtrim(number_format($sale['cash'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">{{ rtrim(rtrim(number_format($sale['due'] ?? 0, 4, '.', ''), '0'), '.') }}</td>
                    </tr>
                @endforeach

                <tr class="bg-gray-200 font-bold border-b border-gray-400">
                    <td class="px-6 py-4 border-t border-gray-300 text-center" colspan="2">Current Month Total</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalSetPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center"></td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalCashReceived, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right">{{ rtrim(rtrim(number_format($totalDue, 4, '.', ''), '0'), '.') }}</td>
                </tr>

                <tr class="bg-yellow-200 font-bold">
                    <td class="px-6 py-4 border-t border-gray-300 text-center" colspan="2">Total:</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalSetPrice + $soFarSets, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center"></td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalPrice, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-center">{{ rtrim(rtrim(number_format($totalCashReceived + $soFarCash, 4, '.', ''), '0'), '.') }}</td>
                    <td class="px-6 py-4 border-t border-gray-300 text-right">{{ rtrim(rtrim(number_format($totalDue + $soFarDue, 4, '.', ''), '0'), '.') }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <button id="download-pdf" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Download PDF
    </button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
            var element = document.getElementById('content-to-pdf');
            html2pdf()
                .from(element)
                .set({
                    margin: .3,
                    filename: 'head-office-sale-report',
                    html2canvas: { scale: 4 },
                    jsPDF: { orientation: 'portrait', unit: 'in', format: 'A4', compressPDF: true }
                })
                .save();
        });
    </script>
</div>
