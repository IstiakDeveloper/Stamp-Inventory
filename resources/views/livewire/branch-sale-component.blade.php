<div class="mx-auto p-8 mt-8 bg-white shadow-lg rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Branch Management (Sale)</h1>

    <form wire:submit.prevent="saveSale" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="col-span-1">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model.live="date" id="date"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-1">
            <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch:</label>
            <select wire:model.live="branch_id" id="branch_id"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select Branch</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
            @error('branch_id')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-1">
            <label for="sets" class="block text-sm font-medium text-gray-700">Sets:</label>
            <input type="number" wire:model.live="sets" id="sets" step="any"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('sets')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-1">
            <label for="per_set_price" class="block text-sm font-medium text-gray-700">Per Set Price:</label>
            <input type="text" wire:model.live="perSetPrice" id="per_set_price"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                readonly>
        </div>

        <div class="col-span-1">
            <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price:</label>
            <span class="block text-lg font-semibold mt-1">
                @if ($sets > 0)
                    @formatNumber($totalPrice)
                @else
                    @formatNumber(0)
                @endif
            </span>
        </div>

        <div class="col-span-1">
            <label for="cash" class="block text-sm font-medium text-gray-700">Cash Received:</label>
            <input type="text" wire:model.live="cash" id="cash"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('cash')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-1">
            <label for="receiver_name" class="block text-sm font-medium text-gray-700">Receiver Name:</label>
            <input type="text" wire:model.live="receiver_name" id="receiver_name"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('receiver_name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-2 text-sm text-gray-700">
            @if ($cash !== null && is_numeric($cash))
                @if ($extraMoney !== null)
                    @if ($extraMoney > 0)
                        <p class="text-green-500">Extra Money: + @formatNumber($extraMoney)</p>
                    @elseif ($extraMoney < 0)
                        <p class="text-red-500">Due Amount: - @formatNumber(abs($extraMoney))</p>
                    @else
                        <p class="text-gray-500">Exact Amount Received</p>
                    @endif
                @endif
            @endif
        </div>

        <div class="col-span-2">
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Sale
            </button>
        </div>
    </form>


    <div class="mt-8 flex justify-between items-center">
        <h2 class="text-xl font-semibold mb-4">Transaction List</h2>
        <a href="{{ route('branch_sale_report') }}" class="text-blue-600 hover:underline text-sm">View Report</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs leading-4 font-medium">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Date</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Branch</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Sets</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Per Set Price</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Total Price</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Cash Received</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Receiver</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($branchSales as $transaction)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->branch->branch_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $value = $transaction->sets;
                                // Format to two decimal places if needed
                                $formattedValue =
                                    $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                            {{ $formattedValue }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($transaction->per_set_price)</td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($transaction->total_price)</td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($transaction->cash)</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->receiver_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="deleteSale({{ $transaction->id }})"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="confirm('Are you sure you want to delete this sale?') || event.stopImmediatePropagation()">
                                Delete Sale
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
