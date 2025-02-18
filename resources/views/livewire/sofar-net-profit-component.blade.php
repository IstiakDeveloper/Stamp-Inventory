<div class="mx-auto p-6 max-w-4xl bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-900">Sofar Net Profit</h2>

    <!-- Form -->
    <div class="mb-8">
        @if (session()->has('message'))
            <div class="bg-green-500 text-white p-3 rounded-md mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" wire:model="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="number" id="amount" wire:model="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">Save</button>
                <button type="button" wire:click="resetForm" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">Cancel</button>
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white divide-y divide-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-50 text-gray-600 text-xs font-medium uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @foreach($sofarNetProfits as $profit)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $profit->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($profit->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <button wire:click="edit({{ $profit->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out">Edit</button>
                            <button wire:click="delete({{ $profit->id }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
