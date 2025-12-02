@php
    use \Carbon\Carbon;
@endphp

<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="mr-4">
                <label for="month" class="block text-sm font-medium text-gray-700">Month:</label>
                <select wire:model.live="month" id="month" class="block w-full py-2 pl-3 pr-10 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year:</label>
                <select wire:model.live="year" id="year" class="block w-full py-2 pl-3 pr-10 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach (range(Carbon::now()->year - 10, Carbon::now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button wire:click="downloadPdf" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25">
            Download PDF
        </button>
    </div>

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">SL</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Date</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Note</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Type</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Fund In Amount</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Fund Out Amount</th>
                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Available Balance</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr class="bg-gray-100">
                <td class="px-4 py-2 text-sm text-gray-700 border">1</td>
                <td class="px-4 py-2 text-sm text-gray-700 border">Previous: </td>
                <td class="px-4 py-2 text-sm text-gray-700 border"></td>
                <td class="px-4 py-2 text-sm text-gray-700 border"></td>
                <td class="px-4 py-2 text-sm text-gray-700 border"></td>
                <td class="px-4 py-2 text-sm text-gray-700 border"></td>
                <td class="px-4 py-2 text-sm font-semibold text-gray-900 border">
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
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ $index++ }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ $entry->note }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ $formattedFundInAmount }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700 border">{{ $formattedFundOutAmount }}</td>
                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 border">{{ $formattedAvailableBalance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<x-pdf-viewer-script />
