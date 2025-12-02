<div class="p-6">
    <!-- Date Selection -->
    <div class="mb-6">
        <label for="month" class="mr-2 font-bold">Select Month:</label>
        <select wire:model.live="selectedMonth" id="month" class="border rounded p-2">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}</option>
            @endfor
        </select>

        <label for="year" class="ml-4 mr-2 font-bold">Select Year:</label>
        <select wire:model.live="selectedYear" id="year" class="border rounded p-2">
            @for ($y = \Carbon\Carbon::now()->year; $y >= 2000; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

    <!-- Combined Stock In/Out Table -->
    <div id="content-to-pdf" class="bg-white rounded-lg shadow-md p-4">
        <h3 class="text-xl font-bold mb-2">
            Stock Register for
            @if ($selectedMonth && $selectedYear)
                {{ \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F') }} {{ $selectedYear }}
            @else
                (Please select a month and year)
            @endif
        </h3>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/8 px-6 py-3 text-left">Date</th>
                    <th class="w-1/8 px-6 py-3 text-center">Stock In Sets</th>
                    <th class="w-1/8 px-6 py-3 text-center">Stock Out Sets</th>
                    <th class="w-1/8 px-6 py-3 text-right">Available Sets</th>
                </tr>
            </thead>
            <tbody>
                <!-- So Far Totals Before Selected Month -->
                <tr class="bg-green-200 font-bold">
                    <td class="px-6 py-4 border-b border-gray-300">Previous {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth)->format('F') }}:</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($soFarStockIn->sum('sets'))</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($soFarStockOut->sum('sets'))</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-right">@formatNumber($soFarStockIn->sum('sets') - $soFarStockOut->sum('sets'))</td>
                </tr>

                <!-- Daily Stock In/Out Data -->
                @php
                    $cumulativeStockIn = $soFarStockIn->sum('sets'); // Cumulative Stock In starting from previous months
                    $cumulativeStockOut = $soFarStockOut->sum('sets'); // Cumulative Stock Out starting from previous months
                @endphp
                @foreach ($completeMonth as $day)
                    @php
                        $formattedDate = $day->format('Y-m-d');
                        $dayStockInData = $stockInData->firstWhere('date', $formattedDate) ?? ['sets' => 0, 'purchase_price' => 0, 'total_purchase_price' => 0];
                        $dayStockOutData = $stockOutData->get($formattedDate, ['sets' => 0, 'price' => 0, 'total_price' => 0]);

                        // Update cumulative totals
                        $cumulativeStockIn += $dayStockInData['sets'];
                        $cumulativeStockOut += $dayStockOutData['sets'];

                        // Calculate available sets
                        $availableSets = $cumulativeStockIn - $cumulativeStockOut;

                        // Determine if there's any data for this day
                        $isDataAvailable = $dayStockInData['sets'] > 0 || $dayStockInData['purchase_price'] > 0 || $dayStockInData['total_purchase_price'] > 0 || $dayStockOutData['sets'] > 0 || $dayStockOutData['price'] > 0 || $dayStockOutData['total_price'] > 0;
                    @endphp
                    <tr class="{{ $isDataAvailable ? 'bg-gray-200' : '' }}">
                        <td class="px-6 py-4 border-b border-gray-300">{{ $day->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">{{ ($dayStockInData['sets']) % 1 === 0 ? number_format(($dayStockInData['sets']), 0) : number_format(($dayStockInData['sets']), 2) }}</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($dayStockOutData['sets'])</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-right">@formatNumber($availableSets)</td>
                    </tr>
                @endforeach

                <!-- Monthly Totals -->
                <tr class="bg-blue-200 font-bold">
                    <td class="py-4 px-6 border-b border-gray-300">This Month Total:</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($totalStockIn)</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($totalStockOut)</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-right">@formatNumber($availableSets)</td>
                </tr>

                <!-- Cumulative Totals -->
                <tr class="bg-green-200 font-bold">
                    <td class="py-4 px-6 border-b border-gray-300">Total Cummulative:</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($soFarStockIn->sum('sets') + $totalStockIn)</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-center">@formatNumber($soFarStockOut->sum('sets') + $totalStockOut)</td>
                    <td class="px-6 py-4 border-b border-gray-300 text-right">@formatNumber($availableSets)</td>
                </tr>

                <tr class="bg-green-200 font-bold">
                    <td class="py-4 px-6 border-b border-gray-300">Available Stock Price:</td>
                    <td></td>
                    <td></td>
                    <td class="px-6 py-4 border-b bg-yellow-300 border-gray-300 text-right">@formatNumber($availableSets * $averageStampPricePerSet)</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-6">
            <button wire:click="downloadPdf" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">Download PDF</button>
        </div>
    </div>
</div>

<x-pdf-viewer-script />
