<div class="p-6">
    <div class="flex justify-between border-b shadow-sm px-2">
        <div class="flex items-center gap-4">
            <div class="mb-6">
                <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                <select id="year" wire:model.live="year" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(Carbon\Carbon::now()->year, 2020) as $yearOption)
                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                <select id="month" wire:model.live="month" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(range(1, 12) as $monthOption)
                        <option value="{{ sprintf('%02d', $monthOption) }}">{{ DateTime::createFromFormat('!m', $monthOption)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="mb-6">
                <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date:</label>
                <input type="date" id="fromDate" wire:model.live="fromDate" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="toDate" class="block text-sm font-medium text-gray-700">To Date:</label>
                <input type="date" id="toDate" wire:model.live="toDate" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div class="flex items-center">
            <button wire:click="downloadPdf" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">Download PDF</button>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Report</h2>
        <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($transaction->date instanceof \Carbon\Carbon)
                                {{ $transaction->date->format('Y-m-d') }}
                            @else
                                Invalid Date
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->amount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-center">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
