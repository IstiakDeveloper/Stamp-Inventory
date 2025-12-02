<div class="mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Reject or Free Report</h2>

    <div class="flex gap-4 mb-4">
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
            <select id="month" wire:model.live="currentMonth" class="mt-1 p-2 border rounded-md shadow-sm">
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <select id="year" wire:model.live="currentYear" class="mt-1 p-2 border rounded-md shadow-sm">
                @foreach (range(now()->year - 5, now()->year) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="ml-auto flex items-center">
            <button wire:click="downloadPDF"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Download
                PDF</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs font-medium">
                    <th class="py-4 px-6 text-left">SL</th>
                    <th class="py-4 px-6 text-center">Date</th>
                    <th class="py-4 px-6 text-center">Note</th>
                    <th class="py-4 px-6 text-center">Sets</th>
                    <th class="py-4 px-6 text-center">Purchase Price</th>
                    <th class="py-4 px-6 text-center">Loss</th>
                    <th class="py-4 px-6 text-right">Total Loss</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                    <td class="text-right py-3 px-6 font-bold" colspan="6">Previous Loss:</td>
                    <td class="text-right py-3 px-6">
                        {{ rtrim(rtrim(number_format($previousMonthNetLoss, 2, '.', ''), '0'), '.') }}
                    </td>
                </tr>

                @php
                    $cumulativeNetLoss = $previousMonthNetLoss;
                    $totalSetsThisMonth = 0;
                    $totalMonthlyLoss = 0; // Initialize the total monthly loss
                @endphp

                @foreach ($rejectOrFreeRecords as $index => $record)
                    @php
                        $totalPrice = $record->sets * $averageStampPricePerSet;
                        $cumulativeNetLoss += $totalPrice;
                        $totalSetsThisMonth += $record->sets;
                        $totalMonthlyLoss += $totalPrice; // Add to monthly loss total
                    @endphp
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 text-center">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-6 text-center">{{ $record->note }}</td>
                        <td class="py-3 px-6 text-center">
                            @php
                                $value = $record->sets;
                                // Format to two decimal places if needed
                                $formattedValue =
                                    $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                            {{ $formattedValue }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            {{ rtrim(rtrim(number_format($averageStampPricePerSet, 2, '.', ''), '0'), '.') }}</td>
                        <td class="py-3 px-6 text-center">
                            {{ rtrim(rtrim(number_format($totalPrice, 2, '.', ''), '0'), '.') }}</td>
                        <td class="py-3 px-6 text-right">
                            {{ rtrim(rtrim(number_format($cumulativeNetLoss, 2, '.', ''), '0'), '.') }}</td>
                    </tr>
                @endforeach


                <tr class="bg-gray-50 font-semibold">
                    <td colspan="3" class="text-right py-3 px-6 font-bold">This Month:</td>
                    <td class="text-center py-3 px-6">
                        @php
                            $value = $totalSetsThisMonth;
                            // Format to two decimal places if needed
                            $formattedValue =
                                $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td></td>
                    <td class="text-right py-3 px-6">{{ rtrim(rtrim(number_format($totalMonthlyLoss, 2, '.', ''), '0'), '.') }}</td>
                    <td class="text-right py-3 px-6">{{ rtrim(rtrim(number_format($cumulativeNetLoss, 2, '.', ''), '0'), '.') }}</td>
                </tr>

                <!-- New Cumulative Row with data from the controller -->
                <tr class="bg-blue-50 font-semibold text-blue-800">
                    <td colspan="3" class="text-right py-3 px-6 font-bold">Cumulative:</td>
                    <td class="text-center py-3 px-6">
                        @php
                            $value = $cumulativeSets;
                            // Format to two decimal places if needed
                            $formattedValue =
                                $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td></td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($cumulativeLoss, 2, '.', ''), '0'), '.') }}</td>
                    <td class="text-right py-3 px-6">{{ rtrim(rtrim(number_format($cumulativeLoss, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<x-pdf-viewer-script />
