<div class="p-6 max-w-6xl mx-auto bg-white shadow-lg rounded-lg">
    <!-- Total Balance -->
    <div class="mb-6">
        <p class="text-2xl font-bold text-gray-800">Total Balance: @formatNumber($totalBalance)</p>
    </div>

    <!-- Add Transaction Form -->
    <form wire:submit.prevent="saveTransaction" class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-6">
        <div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" wire:model.live="date" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="text" id="amount" wire:model.defer="amount" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type" wire:model.live="type" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                    <option>Select In or Out</option>
                    <option value="cash_in">Cash In</option>
                    <option value="cash_out">Cash Out</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                <input type="text" id="details" wire:model.defer="details" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                @error('details') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-span-2">
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150 ease-in-out">Save Transaction</button>
            </div>
        </div>
    </form>

    <!-- Edit Transaction Section -->
    @if($editingTransactionId)
    <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Transaction</h2>
        <form wire:submit.prevent="updateTransaction" class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <div class="mb-4">
                    <label for="editDate" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="editDate" wire:model.defer="editDate" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                    @error('editDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="editAmount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="text" id="editAmount" wire:model.defer="editAmount" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                    @error('editAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="editType" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="editType" wire:model.defer="editType" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                        <option>Select In or Out</option>
                        <option value="cash_in">Cash In</option>
                        <option value="cash_out">Cash Out</option>
                    </select>
                    @error('editType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="editDetails" class="block text-sm font-medium text-gray-700">Details</label>
                    <input type="text" id="editDetails" wire:model.defer="editDetails" class="mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full">
                    @error('editDetails') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col-span-2 flex space-x-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150 ease-in-out">Update Transaction</button>
                <button type="button" wire:click="$set('editingTransactionId', null)" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150 ease-in-out">Cancel</button>
            </div>
        </form>
    </div>
    @endif

    <!-- Transaction List -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Transaction List</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600 text-xs font-medium uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Details</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">@formatNumber($transaction->amount)</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->details }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="deleteTransaction({{ $transaction->id }})" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-md transition duration-150 ease-in-out">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
