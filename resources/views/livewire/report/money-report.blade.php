<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <div>
                <label for="selectedYear" class="block text-sm font-medium text-gray-700">Year</label>
                <select id="selectedYear" wire:model="selectedYear" wire:change="handleFilterChange"
                    class="block p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Year</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="selectedMonth" class="block text-sm font-medium text-gray-700">Month</label>
                <select id="selectedMonth" wire:model="selectedMonth" wire:change="handleFilterChange"
                    class="block p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Month</option>
                    @foreach ($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <button wire:click="downloadPdf"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md btn btn-primary hover:bg-blue-700">Download
                PDF</button>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700">Opening Balance:
            @php
                $formattedOpeningBalance =
                    $openingBalance == intval($openingBalance)
                        ? number_format($openingBalance, 0)
                        : number_format($openingBalance, 2);
            @endphp
            {{ $formattedOpeningBalance }}
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th
                        class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        Date</th>
                    <th
                        class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        Amount</th>
                    <th
                        class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        Type</th>
                    <th
                        class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        Details</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($transactions as $transaction)
                    @php
                        $formattedAmount =
                            $transaction->amount == intval($transaction->amount)
                                ? number_format($transaction->amount, 0)
                                : number_format($transaction->amount, 2);
                    @endphp
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-200">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2 border-b border-gray-200">{{ $formattedAmount }}</td>
                        <td class="px-4 py-2 border-b border-gray-200">{{ $transaction->type }}</td>
                        <td class="px-4 py-2 border-b border-gray-200">{{ $transaction->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-pdf-viewer-script />
