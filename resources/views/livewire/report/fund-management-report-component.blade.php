@php
    use \Carbon\Carbon;
@endphp

<div class="p-6 bg-white shadow-md rounded-lg">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="mr-4">
                <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                <select wire:model.live="month" id="month" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                <select wire:model.live="year" id="year" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach (range(Carbon::now()->year - 10, Carbon::now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button wire:click="downloadPdf" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
            Download PDF
        </button>
    </div>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fund In Amount</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fund Out Amount</th>
                <th class="border px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Balance</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr class="bg-gray-100">
                <td class="border px-4 py-2 text-sm text-gray-700">1</td>
                <td class="border px-4 py-2 text-sm text-gray-700">Previous: </td>
                <td class="border px-4 py-2 text-sm text-gray-700"></td>
                <td class="border px-4 py-2 text-sm text-gray-700"></td>
                <td class="border px-4 py-2 text-sm text-gray-700"></td>
                <td class="border px-4 py-2 text-sm text-gray-700"></td>
                <td class="border px-4 py-2 text-sm font-semibold text-gray-900">
                    @php
                        $formattedPreviousBalance = ($previousBalance == intval($previousBalance))
                            ? number_format($previousBalance, 0)
                            : number_format($previousBalance, 2);
                    @endphp
                    {{ $formattedPreviousBalance }}
                </td>
            </tr>

            @php
                $availableBalance = $previousBalance; // Start with the previous balance
                $index = 2;
            @endphp

            @foreach($data as $entry)
                @php
                    $fundInAmount = $entry->type === 'fund_in' ? $entry->amount : 0;
                    $fundOutAmount = $entry->type === 'fund_out' ? $entry->amount : 0;
                    $availableBalance += $fundInAmount - $fundOutAmount;

                    // Format the amounts conditionally
                    $formattedFundInAmount = ($fundInAmount == intval($fundInAmount))
                        ? number_format($fundInAmount, 0)
                        : number_format($fundInAmount, 2);

                    $formattedFundOutAmount = ($fundOutAmount == intval($fundOutAmount))
                        ? number_format($fundOutAmount, 0)
                        : number_format($fundOutAmount, 2);

                    $formattedAvailableBalance = ($availableBalance == intval($availableBalance))
                        ? number_format($availableBalance, 0)
                        : number_format($availableBalance, 2);
                @endphp
                <tr>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ $index++ }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ $entry->note }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ $formattedFundInAmount }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-700">{{ $formattedFundOutAmount }}</td>
                    <td class="border px-4 py-2 text-sm font-semibold text-gray-900">{{ $formattedAvailableBalance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
