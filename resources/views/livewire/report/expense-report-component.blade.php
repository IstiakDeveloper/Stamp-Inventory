<div class="mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Expenses Report</h2>

    <div class="flex gap-6 mb-6">
        <div class="w-1/2">
            <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
            <select id="month" wire:model.live="currentMonth" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-1/2">
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
            <select id="year" wire:model.live="currentYear" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                @foreach(range(now()->year - 5, now()->year) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold border-b border-gray-300">
                    <th class="py-3 px-4 text-left">SL</th>
                    <th class="py-3 px-4 text-center">Date</th>
                    <th class="py-3 px-4 text-center">Purpose</th>
                    <th class="py-3 px-4 text-center">Amount</th>
                    <th class="py-3 px-4 text-right">Total Expense</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <tr class="bg-gray-50 font-semibold border-b border-gray-300">
                    <td colspan="4" class="text-right px-4 py-2">Previous Expenses:</td>
                    <td class="px-4 py-2 text-right">{{ rtrim(rtrim(number_format($previousMonthTotalExpenses, 2, '.', ''), '0'), '.') }}</td>
                </tr>

                @php
                    $cumulativeNetExpense = $previousMonthTotalExpenses;
                @endphp

                @foreach ($expensesRecords as $index => $record)
                    @php
                        $cumulativeNetExpense += $record->amount;
                    @endphp
                    <tr class="border-b border-gray-300 hover:bg-gray-50">
                        <td class="py-3 px-4 text-left">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-center">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-center">{{ $record->purpose }}</td>
                        <td class="py-3 px-4 text-center">{{ rtrim(rtrim(number_format($record->amount, 2, '.', ''), '0'), '.') }}</td>
                        <td class="py-3 px-4 text-right">{{ rtrim(rtrim(number_format($cumulativeNetExpense, 2, '.', ''), '0'), '.') }}</td>
                    </tr>
                @endforeach

                <tr class="bg-gray-50 font-semibold border-t border-gray-300">
                    <td colspan="3" class="text-left px-4 py-2">This Month:</td>
                    <td class="px-4 py-2 text-center">{{ rtrim(rtrim(number_format($totalAmount, 2, '.', ''), '0'), '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <button wire:click="downloadPDF" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">Download PDF</button>
    </div>
</div>

<x-pdf-viewer-script />
