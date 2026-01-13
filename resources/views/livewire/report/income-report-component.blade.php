<div class="mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Income Report</h2>

    <div class="flex gap-6 mb-6">
        <div class="w-1/2">
            <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
            <select id="month" wire:model.live="currentMonth" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-1/2">
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
            <select id="year" wire:model.live="currentYear" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out">
                @foreach(range(now()->year - 5, now()->year) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Income by Type Summary -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach(['extra' => 'Extra Income', 'other' => 'Other Income', 'commission' => 'Commission', 'bonus' => 'Bonus', 'refund' => 'Refund', 'investment_return' => 'Investment Return'] as $type => $label)
            <div class="bg-white border border-green-200 rounded-lg p-4 shadow-sm">
                <div class="text-xs font-medium text-gray-600">{{ $label }}</div>
                <div class="mt-1 text-lg font-bold text-green-600">
                    {{ rtrim(rtrim(number_format($incomeByType[$type] ?? 0, 2, '.', ''), '0'), '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-green-50 text-gray-700 uppercase text-xs font-semibold border-b border-gray-300">
                    <th class="py-3 px-4 text-left">SL</th>
                    <th class="py-3 px-4 text-center">Date</th>
                    <th class="py-3 px-4 text-center">Type</th>
                    <th class="py-3 px-4 text-center">Source</th>
                    <th class="py-3 px-4 text-center">Amount</th>
                    <th class="py-3 px-4 text-right">Total Income</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <tr class="bg-green-50 font-semibold border-b border-gray-300">
                    <td colspan="5" class="text-right px-4 py-2">Previous Income:</td>
                    <td class="px-4 py-2 text-right">{{ rtrim(rtrim(number_format($previousMonthTotalIncome, 2, '.', ''), '0'), '.') }}</td>
                </tr>

                @php
                    $cumulativeIncome = $previousMonthTotalIncome;
                @endphp

                @foreach ($incomesRecords as $index => $record)
                    @php
                        $cumulativeIncome += $record->amount;
                    @endphp
                    <tr class="border-b border-gray-300 hover:bg-green-50">
                        <td class="py-3 px-4 text-left">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-center">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                @if($record->type == 'extra') bg-blue-100 text-blue-700
                                @elseif($record->type == 'commission') bg-purple-100 text-purple-700
                                @elseif($record->type == 'bonus') bg-pink-100 text-pink-700
                                @elseif($record->type == 'refund') bg-orange-100 text-orange-700
                                @elseif($record->type == 'investment_return') bg-indigo-100 text-indigo-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $record->type)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">{{ $record->source }}</td>
                        <td class="py-3 px-4 text-center text-green-600 font-medium">{{ rtrim(rtrim(number_format($record->amount, 2, '.', ''), '0'), '.') }}</td>
                        <td class="py-3 px-4 text-right font-semibold">{{ rtrim(rtrim(number_format($cumulativeIncome, 2, '.', ''), '0'), '.') }}</td>
                    </tr>
                @endforeach

                <tr class="bg-green-50 font-semibold border-t border-gray-300">
                    <td colspan="4" class="text-left px-4 py-2">This Month:</td>
                    <td class="px-4 py-2 text-center text-green-600">{{ rtrim(rtrim(number_format($totalAmount, 2, '.', ''), '0'), '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <button wire:click="downloadPDF" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">Download PDF</button>
    </div>
</div>

<x-pdf-viewer-script />
