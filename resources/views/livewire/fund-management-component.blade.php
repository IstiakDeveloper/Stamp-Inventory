<div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <!-- Display Edit Form or Add New Form -->
    @if ($editMode)
        <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Fund</h2>
    @else
        <h2 class="text-lg font-medium text-gray-900 mb-4">Add New Fund</h2>
    @endif

    <form wire:submit.prevent="{{ $editMode ? 'update' : 'submit' }}">
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" id="date" wire:model="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            @error('date') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" step="0.01" id="amount" wire:model="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
            <select id="type" wire:model="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">Select Type</option>
                <option value="fund_in">Fund In</option>
                <option value="fund_out">Fund Out</option>
            </select>
            @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
            <textarea id="note" wire:model="note" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
            @error('note') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
            {{ $editMode ? 'Update' : 'Submit' }}
        </button>
    </form>

    <div class="mt-6 p-4 bg-gray-50 rounded flex justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Total Fund: @formatNumber($totalFund)</h3>
        </div>
        <div>
            <a class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700" href="{{ route('fund_management_report') }}">View Report</a>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Fund Management List</h2>

        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Date</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Note</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($funds as $fund)
                <tr class="border-b">
                    <td class="py-2">{{ $fund->date }}</td>
                    <td class="py-2">@formatNumber($fund->amount)</td>
                    <td class="py-2">{{ ucfirst(str_replace('_', ' ', $fund->type)) }}</td>
                    <td class="py-2">{{ $fund->note }}</td>
                    <td class="py-2">
                        <button wire:click="edit('{{ $fund->id }}')" class="text-blue-600 hover:underline">Edit</button>
                        <button wire:click="delete('{{ $fund->id }}')" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
