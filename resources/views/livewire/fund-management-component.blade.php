<div>
    <div class="max-w-4xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-{{ $editMode ? 'edit' : 'plus' }} mr-2 text-blue-500 text-xs"></i>
                    {{ $editMode ? 'Edit Fund Entry' : 'Add New Fund Entry' }}
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="{{ $editMode ? 'update' : 'submit' }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" id="date" wire:model="date"
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
                            <input type="number" step="0.01" id="amount" wire:model="amount"
                                placeholder="Enter amount"
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
                            <select id="type" wire:model="type"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="fund_in">Fund In</option>
                                <option value="fund_out">Fund Out</option>
                            </select>
                            @error('type')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                            <textarea id="note" wire:model="note" rows="2" placeholder="Enter note (optional)"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('note')
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
                            <i class="fas fa-{{ $editMode ? 'save' : 'plus' }} mr-1"></i>
                            {{ $editMode ? 'Update Entry' : 'Add Entry' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary & Report Card -->
        <div class="p-4 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="text-sm text-blue-600 fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">Total Fund Balance</h3>
                        <p class="text-lg font-bold text-blue-800">@formatNumber($totalFund)</p>
                    </div>
                </div>
                <a href="{{ route('fund_management_report') }}"
                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 transition-colors bg-white border border-blue-200 rounded-md hover:bg-blue-50">
                    <i class="mr-1 fas fa-chart-line"></i>
                    View Report
                </a>
            </div>
        </div>

        <!-- Fund List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                    Fund Management History
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
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Note</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($funds as $fund)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($fund->date)->format('M d, Y') }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-medium {{ $fund->type === 'fund_in' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $fund->type === 'fund_in' ? '+' : '-' }}@formatNumber($fund->amount)
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $fund->type === 'fund_in' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                        <i
                                            class="fas fa-{{ $fund->type === 'fund_in' ? 'arrow-up' : 'arrow-down' }} mr-1 text-xs"></i>
                                        {{ ucfirst(str_replace('_', ' ', $fund->type)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $fund->note ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit('{{ $fund->id }}')"
                                            class="p-1 text-blue-600 transition-colors rounded hover:bg-blue-100">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete('{{ $fund->id }}')"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this fund entry?')">
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
            @if ($funds->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-wallet"></i>
                    <p class="text-sm text-gray-600">No fund entries found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first fund entry using the form above</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom enhancements */
        .transition-colors {
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
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

        /* Focus ring improvements */
        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }
    </style>

</div>
