<div>
    <div class="max-w-6xl p-3 mx-auto space-y-4">

        <!-- Total Balance Card -->
        <div class="p-4 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="text-sm text-green-600 fas fa-balance-scale"></i>
                </div>
                <div>
                    <h2 class="text-sm font-medium text-green-700">Current Balance</h2>
                    <p class="text-xl font-bold text-green-800">@formatNumber($totalBalance)</p>
                </div>
            </div>
        </div>

        <!-- Add Transaction Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-blue-500 fas fa-plus"></i>
                    Add New Transaction
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="saveTransaction" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" id="date" wire:model.live="date"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('date')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block mb-1 text-xs font-medium text-gray-700">Amount</label>
                            <input type="text" id="amount" wire:model.defer="amount" placeholder="Enter amount"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('amount')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block mb-1 text-xs font-medium text-gray-700">Type</label>
                            <select id="type" wire:model.live="type"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="cash_in">Cash In</option>
                                <option value="cash_out">Cash Out</option>
                            </select>
                            @error('type')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Details -->
                        <div>
                            <label for="details" class="block mb-1 text-xs font-medium text-gray-700">Details</label>
                            <input type="text" id="details" wire:model.defer="details"
                                placeholder="Transaction details"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('details')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Save Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Transaction Section -->
        @if ($editingTransactionId)
            <div class="border rounded-lg shadow-sm bg-amber-50 border-amber-200">
                <!-- Header -->
                <div class="px-4 py-3 border-b rounded-t-lg border-amber-200 bg-amber-100">
                    <h2 class="text-sm font-semibold text-amber-900">
                        <i class="mr-2 text-xs fas fa-edit text-amber-600"></i>
                        Edit Transaction
                    </h2>
                </div>

                <!-- Edit Form -->
                <div class="p-4">
                    <form wire:submit.prevent="updateTransaction" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                            <!-- Edit Date -->
                            <div>
                                <label for="editDate" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                                <input type="date" id="editDate" wire:model.defer="editDate"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                @error('editDate')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- Edit Amount -->
                            <div>
                                <label for="editAmount"
                                    class="block mb-1 text-xs font-medium text-gray-700">Amount</label>
                                <input type="text" id="editAmount" wire:model.defer="editAmount"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                @error('editAmount')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- Edit Type -->
                            <div>
                                <label for="editType" class="block mb-1 text-xs font-medium text-gray-700">Type</label>
                                <select id="editType" wire:model.defer="editType"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Select Type</option>
                                    <option value="cash_in">Cash In</option>
                                    <option value="cash_out">Cash Out</option>
                                </select>
                                @error('editType')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- Edit Details -->
                            <div>
                                <label for="editDetails"
                                    class="block mb-1 text-xs font-medium text-gray-700">Details</label>
                                <input type="text" id="editDetails" wire:model.defer="editDetails"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                @error('editDetails')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" wire:click="$set('editingTransactionId', null)"
                                class="px-4 py-2 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-md hover:bg-gray-200">
                                <i class="mr-1 fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                                <i class="mr-1 fas fa-check"></i>
                                Update Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Transaction List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                    Transaction History
                </h2>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Date</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Amount</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Type</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Details</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transactions as $transaction)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-medium {{ $transaction->type === 'cash_in' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $transaction->type === 'cash_in' ? '+' : '-' }}@formatNumber($transaction->amount)
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $transaction->type === 'cash_in' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                        <i
                                            class="fas fa-{{ $transaction->type === 'cash_in' ? 'arrow-up' : 'arrow-down' }} mr-1 text-xs"></i>
                                        {{ $transaction->type === 'cash_in' ? 'Cash In' : 'Cash Out' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $transaction->details ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editTransaction({{ $transaction->id }})"
                                            class="p-1 text-blue-600 transition-colors rounded hover:bg-blue-100">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteTransaction({{ $transaction->id }})"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if ($transactions->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-receipt"></i>
                    <p class="text-sm text-gray-600">No transactions found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first transaction using the form above</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom enhancements */
        .transition-colors {
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        /* Mobile table improvements */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }

            .overflow-x-auto::-webkit-scrollbar {
                height: 3px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }
        }

        /* Enhanced focus states */
        input:focus,
        select:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Responsive grid improvements */
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
    </style>

</div>
