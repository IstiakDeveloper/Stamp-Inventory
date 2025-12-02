<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <div>
                <select id="selectedYear" wire:model="selectedYear" wire:change="handleFilterChange" class="block p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="selectedMonth" wire:model="selectedMonth" wire:change="handleFilterChange" class="block p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="all">All Month</option>
                    @foreach($months as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <button wire:click="downloadPdf" class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md btn btn-primary hover:bg-blue-700">Download PDF</button>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700">Total: @php
            $value = $totalStockSum;
            // Format to two decimal places if needed
            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
        @endphp
        {{ $formattedValue }}</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Date</th>
                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Address</th>
                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Sets</th>
                    {{-- <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Pieces</th> --}}
                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Price Per Set</th>
                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Total Price</th>
                </tr>
            </thead>
                @foreach($stocks as $stock)
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-200">{{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2 border-b border-gray-200">{{ $stock->address }}</td>
                        <td class="px-4 py-2 border-b border-gray-200">
                            @php
                            $value = $stock->sets ;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
            </td>
                        {{-- <td class="px-4 py-2 border-b border-gray-200">{{ $stock->pieces }}</td> --}}
                        <td class="px-4 py-2 border-b border-gray-200">@formatNumber($stock->price_per_set)</td>
                        <td class="px-4 py-2 border-b border-gray-200">@formatNumber($stock->total_price)</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-pdf-viewer-script />
