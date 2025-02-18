<div class="mx-auto p-8 mt-8 bg-white shadow-lg rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Head Office Sale Management</h1>

    <form wire:submit.prevent="saveSale" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model.live="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="sets" class="block text-sm font-medium text-gray-700">Sets:</label>
            <input type="number" wire:model.live="sets" id="sets" step="any" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('sets') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="per_stamp_price" class="block text-sm font-medium text-gray-700">Per Stamp Price:</label>
            <input type="text" wire:model.live="per_stamp_price" id="per_stamp_price" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('per_stamp_price') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Total Price:</label>
            <span class="block text-lg font-semibold mt-1">
                @if (!is_null($sets) && !is_null($per_stamp_price))
                    @formatNumber($totalPrice)
                @endif
            </span>
        </div>

        <!-- Note Input -->
        <div>
            <label for="note" class="block text-sm font-medium text-gray-700">Note:</label>
            <textarea id="note" wire:model="note" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('note') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="cash" class="block text-sm font-medium text-gray-700">Cash Received:</label>
            <input type="text" wire:model.live="cash" id="cash" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('cash') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>



        <div class="col-span-2 mt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Sale
            </button>
        </div>
    </form>

    <div class="mt-8 flex justify-between items-center">
        <h2 class="text-xl font-semibold mb-4">Transaction List</h2>
        <a href="{{ route('ho_sale_report') }}" class="text-blue-600 hover:underline text-sm">View Report</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs leading-4 font-medium">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Date</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Sets</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Set Price</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Total Price</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Cash Received</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Due</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($transactions as $transaction)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-no-wrap">{{ $transaction->date }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            @php
                                $value = $transaction->sets;
                                // Format to two decimal places if needed
                                $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">@formatNumber($transaction->per_set_price)</td>
                        <td class="px-6 py-4 whitespace-no-wrap">@formatNumber($transaction->total_price)</td>
                        <td class="px-6 py-4 whitespace-no-wrap">@formatNumber($transaction->cash)</td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            @php
                                $due = $transaction->headOfficeDue;
                            @endphp
                            {{ $due ? number_format($due->due_amount, 0) : '0.00' }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <!-- Delete button -->
                            <button wire:click="deleteSale({{ $transaction->id }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"onclick="confirm('Are you sure you want to delete this sale?') || event.stopImmediatePropagation()"
                                    >
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
