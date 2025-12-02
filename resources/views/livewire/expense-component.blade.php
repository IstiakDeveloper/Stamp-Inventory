<div>
    <div class="max-w-4xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Add Expense Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-blue-500 fas fa-plus"></i>
                    Add New Expense
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="saveExpense" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" wire:model="date" id="date"
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
                            <input type="number" step="0.01" wire:model="amount" id="amount"
                                placeholder="Enter amount"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('amount')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label for="purpose" class="block mb-1 text-xs font-medium text-gray-700">Purpose</label>
                        <input type="text" wire:model="purpose" id="purpose" placeholder="Enter expense purpose"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @error('purpose')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Save Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Expense List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Expense History
                    </h2>
                    <a href="{{ route('expense_report') }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                        <i class="mr-1 fas fa-chart-line"></i>
                        View Report
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Date</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Amount</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Purpose</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($expenses as $expense)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-red-600">
                                    -@formatNumber($expense->amount)
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $expense->purpose }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="deleteExpense({{ $expense->id }})"
                                        class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                        onclick="return confirm('Are you sure you want to delete this expense?')">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if ($expenses->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-receipt"></i>
                    <p class="text-sm text-gray-600">No expenses found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first expense using the form above</p>
                </div>
            @endif
        </div>

        <!-- Quick Stats (Optional) -->
        <div class="p-4 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-pink-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="text-sm text-red-600 fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-700">Total Expenses</h3>
                        <p class="text-lg font-bold text-red-800">
                            @php
                                $totalExpenses = $expenses->sum('amount');
                            @endphp
                            @formatNumber($totalExpenses)
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-1 text-xs text-red-600">This Month</div>
                    <div class="text-sm font-medium text-red-700">
                        @php
                            $thisMonthExpenses = $expenses
                                ->filter(function ($expense) {
                                    return \Carbon\Carbon::parse($expense->date)->isCurrentMonth();
                                })
                                ->sum('amount');
                        @endphp
                        @formatNumber($thisMonthExpenses)
                    </div>
                </div>
            </div>
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
        input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Red theme for expenses */
        .text-red-600 {
            color: #dc2626;
        }

        .bg-red-100:hover {
            background-color: #fee2e2;
        }

        /* Table hover effects */
        tr:hover {
            background-color: #f9fafb;
        }
    </style>

</div>
