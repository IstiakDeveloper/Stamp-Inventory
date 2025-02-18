<div class="mx-auto px-4 py-8">
    <div class="flex gap-16 items-center">
        <div>
            <h2 class="text-2xl font-bold mb-4">Manage Stocks</h2>
        </div>
        <div>
            <h2 class="text-lg mb-4 bg-blue-500 text-white rounded-lg py-2 px-4">Stock Available:                             @php
                $value = $totalSet;
                // Format to two decimal places if needed
                $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
            @endphp
            {{ $formattedValue }} sets</h2>
        </div>
        {{-- <div>
            <h2 class="text-lg mb-4 bg-blue-500 text-white rounded-lg py-2 px-4">Stock Available: {{$totalPcs}} pieces</h2>
        </div> --}}
    </div>

    @if (session()->has('flash_message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{ session('flash_message') }}</strong>
        </div>
    @endif

    <form wire:submit.prevent="{{ $stockId ? 'update' : 'store' }}" class="mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" wire:model.live="date" class="mt-1 p-2 border rounded-md w-full">
                @error('date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" wire:model.live="address" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="sets" class="block text-sm font-medium text-gray-700">Sets</label>
                <input type="number" wire:model.live="sets" id="sets" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('sets') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="price_per_set" class="block text-sm font-medium text-gray-700">Price Per Set</label>
                <input type="number" step="0.01" wire:model.live="price_per_set" id="price_per_set" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('price_per_set') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="hidden">
                <label for="price_per_piece" class="block text-sm font-medium text-gray-700">Price Per Piece</label>
                <input type="number" step="0.01" wire:model.live="price_per_piece" id="price_per_piece" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
            </div>

            <div class="hidden">
                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                <input type="number" step="0.01" wire:model.live="total_price" id="total_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                <textarea wire:model.live="note" id="note" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                @error('note') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">{{ $stockId ? 'Update' : 'Save' }}</button>
            <button type="button" wire:click="resetInputFields" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cancel</button>
        </div>
    </form>


    <div class="overflow-x-auto">
        <div class="flex justify-between items-center my-4  ">
            <div>
                <h5 class="font-bold">Purchase List:</h5>
            </div>
            <div>
                <a class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700" href="{{route('stock_register_report')}}">View Reprt</a>
            </div>
        </div>
        <table class="min-w-full bg-white-d border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Date</th>
                    <th class="py-3 px-6 text-left">Address</th>
                    <th class="py-3 px-6 text-left">Sets</th>
                    <th class="py-3 px-6 text-left">Price</th>
                    <th class="py-3 px-6 text-left">Total Price</th>
                    <th class="py-3 px-6 text-left">Note</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($stocks as $stock)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($stock->date)->format('d/m/Y') }}</td>
                        <td class="py-3 px-6 text-left">{{ $stock->address }}</td>
                        <td class="py-3 px-6 text-left">
                            @php
                                $value = $stock->sets;
                                // Format to two decimal places if needed
                                $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                            {{ $formattedValue }}
                        </td>
                        <td class="py-3 px-6 text-left">@formatNumber($stock->price_per_set)</td>
                        <td class="py-3 px-6 text-left">@formatNumber($stock->total_price)</td>
                        <td class="py-3 px-6 text-left">{{ $stock->note }}</td>
                        <td class="py-3 px-6 text-center flex gap-2">
                            <button wire:click="edit({{ $stock->id }})" class="px-2 py-2 bg-blue-500 text-white rounded-md">Edit</button>
                            <button wire:click="delete({{ $stock->id }})" class="px-2 py-2 bg-red-500 text-white rounded-md">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
