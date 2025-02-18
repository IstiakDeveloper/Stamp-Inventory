<div class="mx-auto p-8 max-w-4xl bg-white shadow-lg rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Expense Management</h1>

    <form wire:submit.prevent="saveExpense" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" wire:model="date" id="date" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
            <input type="number" step="0.01" wire:model="amount" id="amount" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('amount') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="col-span-2">
            <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose:</label>
            <input type="text" wire:model="purpose" id="purpose" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('purpose') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="col-span-2 mt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Expense
            </button>
        </div>
    </form>

    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Expenses</h2>
            <a href="{{ route('expense_report') }}" class="text-blue-600 hover:underline text-sm">View Report</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white divide-y divide-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-50 text-gray-600 text-xs font-medium uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Date</th>
                        <th scope="col" class="px-6 py-3 text-left">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left">Purpose</th>
                        <th scope="col" class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm font-light">
                    @foreach ($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $expense->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">@formatNumber($expense->amount)</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $expense->purpose }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="deleteExpense({{ $expense->id }})" 
                                    class="bg-red-500 py-2 px-4 hover:bg-red-700 text-white rounded" onclick="confirm('Are you sure you want to delete this expense?') || event.stopImmediatePropagation()"
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
</div>
