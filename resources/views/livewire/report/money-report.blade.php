<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-4">
            <div>
                <label for="selectedYear" class="block text-sm font-medium text-gray-700">Year</label>
                <select id="selectedYear" wire:model="selectedYear" wire:change="handleFilterChange" class="mt-1 p-2 block border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="selectedMonth" class="block text-sm font-medium text-gray-700">Month</label>
                <select id="selectedMonth" wire:model="selectedMonth" wire:change="handleFilterChange" class="mt-1 p-2 block border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Month</option>
                    @foreach($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <button wire:click="downloadPdf" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Download PDF</button>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700">Opening Balance: 
            @php
                $formattedOpeningBalance = ($openingBalance == intval($openingBalance)) 
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
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($transactions as $transaction)
                    @php
                        $formattedAmount = ($transaction->amount == intval($transaction->amount)) 
                            ? number_format($transaction->amount, 0) 
                            : number_format($transaction->amount, 2);
                    @endphp
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $transaction->date }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $formattedAmount }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $transaction->type }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $transaction->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
