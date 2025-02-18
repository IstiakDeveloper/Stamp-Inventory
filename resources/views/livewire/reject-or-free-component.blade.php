<div class="mx-auto p-8 max-w-5xl bg-white shadow-lg rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Reject or Free Management</h1>

    <form wire:submit.prevent="saveTransaction" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model.live="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="sets" class="block text-sm font-medium text-gray-700">Sets:</label>
            <input type="number" step="0.001" wire:model.live="sets" id="sets" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('sets') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="purchase_price_per_set" class="block text-sm font-medium text-gray-700">Purchase Price Per Set:</label>
            <input type="text" readonly id="purchase_price_per_set" 
                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                value="{{ $this->formatted_purchase_price_per_set }}">
            @error('purchase_price_per_set') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label for="purchase_price_total" class="block text-sm font-medium text-gray-700">Purchase Price Total:</label>
            <input type="text" readonly id="purchase_price_total" 
                class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                value="{{ $this->formatted_purchase_price_total }}">
            @error('purchase_price_total') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>
        
        

        <div class="col-span-2">
            <label for="note" class="block text-sm font-medium text-gray-700">Note:</label>
            <textarea wire:model.live="note" id="note" rows="3" class="form-textarea mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('note') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="col-span-2 mt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Transaction
            </button>
        </div>
    </form>

    <div class="mt-8 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Reject or Free Transactions</h2>
        <a href="{{ route('reject_free_report') }}" class="text-blue-600 hover:underline text-sm">View Report</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md divide-y divide-gray-200">
            <thead class="bg-gray-50 text-gray-600 text-xs font-medium uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Sets</th>
                    <th class="px-6 py-3 text-left">Purchase Price Per Set</th>
                    <th class="px-6 py-3 text-left">Reject Price Total</th>
                    <th class="px-6 py-3 text-left">Note</th>
                    <th class="px-6 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white text-sm font-light divide-y divide-gray-200">
                @foreach ($rejectOrFrees as $rejectOrFree)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $value = $rejectOrFree->sets;
                                // Format to two decimal places if needed
                                $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                            {{ $formattedValue }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($rejectOrFree->purchase_price_per_set)</td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($rejectOrFree->purchase_price_total)</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rejectOrFree->note }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="bg-red-500 py-2 px-4 hover:bg-red-700 text-white rounded" wire:click="deleteTransaction({{ $rejectOrFree->id }})"                             onclick="confirm('Are you sure you want to delete this entry?') || event.stopImmediatePropagation()"
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
