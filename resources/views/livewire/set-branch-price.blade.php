<div class="mx-auto p-6 max-w-lg bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Set Unit Price</h1>

    <form wire:submit.prevent="savePrice" class="space-y-6">
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" step="0.01" id="price" wire:model.defer="price" class="mt-1 p-3 border border-gray-300 rounded-lg shadow-sm w-full focus:border-blue-500 focus:ring focus:ring-blue-200">
            @error('price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Price
            </button>
        </div>
    </form>

    @if (session()->has('message'))
        <div class="mt-4 bg-green-600 text-white p-3 rounded-lg shadow-md">
            {{ session('message') }}
        </div>
    @endif
</div>
