<div class="p-6 bg-white border border-gray-300 rounded-lg shadow-md">
    <div class="flex items-center gap-4 mb-6">
        <div class="mb-4">
            <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date:</label>
            <input type="date" id="fromDate" wire:model.live="fromDate" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="toDate" class="block text-sm font-medium text-gray-700">To Date:</label>
            <input type="date" id="toDate" wire:model.live="toDate" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>


        <div class="mb-4">
            <label for="branchId" class="block text-sm font-medium text-gray-700">Branch:</label>
            <select id="branchId" wire:model.live="branchId" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <div id="content-to-pdf">
            <div class="flex justify-between mb-4 text-lg font-semibold text-gray-700">
                <div>
                    @if ($selectedBranch)
                    <span class="text-blue-600">{{ $selectedBranch->branch_name }} </span>
                    @else
                        No branch selected
                    @endif
                </div>

               <div>
                 <!-- Display From Date and To Date -->
                @if ($fromDate && $toDate)
                    <span class="ml-4">From: <span class="text-blue-600">{{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</span></span>
                    <span class="ml-4">To: <span class="text-blue-600">{{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}</span></span>
                @else
                    <span class="ml-4">Date range not selected</span>
                @endif
               </div>
            </div>
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="text-white bg-blue-600 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase">Serial Number</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Date</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Sets</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Price</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Previous Due</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Receive Cash</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Due</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center uppercase">Receiver</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase">Total Due</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900" colspan="8">Previous Due:</td>
                        <td class="px-6 py-4 text-sm font-medium text-right text-gray-900">{{ $soFarOutstanding % 1 === 0 ? number_format($soFarOutstanding, 0) : number_format($soFarOutstanding, 2) }}</td>
                    </tr>

                    @if($sales && $sales->isNotEmpty())
                        @foreach ($sales as $sale)
                            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">{{ $sale->sets % 1 === 0 ? number_format($sale->sets, 0) : number_format($sale->sets, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">{{ $sale->total_price % 1 === 0 ? number_format($sale->total_price, 0) : number_format($sale->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">{{ $sale->previous_due % 1 === 0 ? number_format($sale->previous_due, 0) : number_format($sale->previous_due, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">{{ $sale->cash % 1 === 0 ? number_format($sale->cash, 0) : number_format($sale->cash, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">
                                    {{ ($sale->total_price - $sale->cash) % 1 === 0 ? number_format($sale->total_price - $sale->cash, 0) : number_format($sale->total_price - $sale->cash, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">{{ $sale->receiver_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-900">{{ $sale->total_due % 1 === 0 ? number_format($sale->total_due, 0) : number_format($sale->total_due, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-sm text-center text-gray-500">No sales data available for the selected period.</td>
                        </tr>
                    @endif

                    <!-- New Total Row -->
                    <tr class="font-bold bg-gray-200 border-t border-gray-300">
                        <td class="px-6 py-4 text-sm text-right" colspan="2">Total: </td>
                        <td class="px-6 py-4 text-sm text-center">{{ $totalSets % 1 === 0 ? number_format($totalSets, 0) : number_format($totalSets, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $totalPrice % 1 === 0 ? number_format($totalPrice, 0) : number_format($totalPrice, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $soFarOutstanding % 1 === 0 ? number_format($soFarOutstanding, 0) : number_format($soFarOutstanding, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $totalCash % 1 === 0 ? number_format($totalCash, 0) : number_format($totalCash, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $totalDue % 1 === 0 ? number_format($totalDue, 0) : number_format($totalDue, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center">-</td>
                        @php
                            $lastSaleTotalDue = $sales->isNotEmpty() ? $sales->last()->total_due : 0;
                        @endphp
                        <td class="px-6 py-4 text-sm text-right">
                            {{ $lastSaleTotalDue % 1 === 0 ? number_format($lastSaleTotalDue, 0) : number_format($lastSaleTotalDue, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-6 text-right">
            <button wire:click="downloadPdf" class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openPdfInNewTab', (event) => {
            const base64 = event.base64;
            const filename = event.filename;

            // Convert base64 to blob
            const byteCharacters = atob(base64);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: 'application/pdf' });

            // Create URL and open in new tab
            const url = URL.createObjectURL(blob);
            const newTab = window.open(url, '_blank');

            // Cleanup URL after a short delay
            setTimeout(() => URL.revokeObjectURL(url), 100);
        });
    });
</script>
