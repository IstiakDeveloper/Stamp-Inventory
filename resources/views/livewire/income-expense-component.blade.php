<div>
    <div class="max-w-6xl p-3 mx-auto space-y-4">

        <!-- Tab Navigation -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex border-b border-gray-200">
                <button wire:click="switchTab('expense')"
                    class="flex-1 px-4 py-3 text-sm font-medium transition-colors
                        {{ $activeTab === 'expense' ? 'text-red-600 border-b-2 border-red-600 bg-red-50' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="mr-2 fas fa-minus-circle"></i>
                    Expenses
                </button>
                <button wire:click="switchTab('income')"
                    class="flex-1 px-4 py-3 text-sm font-medium transition-colors
                        {{ $activeTab === 'income' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="mr-2 fas fa-plus-circle"></i>
                    Income
                </button>
            </div>

            <!-- Expense Tab Content -->
            @if ($activeTab === 'expense')
                <div class="p-4">
                    <!-- Add Expense Form -->
                    <div class="mb-6">
                        <h3 class="mb-4 text-sm font-semibold text-gray-900">
                            <i class="mr-2 text-xs text-red-500 fas fa-plus"></i>
                            Add New Expense
                        </h3>
                        <form wire:submit.prevent="saveExpense" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="expenseDate"
                                        class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                                    <input type="date" wire:model="expenseDate" id="expenseDate"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                    @error('expenseDate')
                                        <span class="flex items-center mt-1 text-xs text-red-600">
                                            <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="expenseAmount"
                                        class="block mb-1 text-xs font-medium text-gray-700">Amount</label>
                                    <input type="number" step="0.01" wire:model="expenseAmount" id="expenseAmount"
                                        placeholder="Enter amount"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                    @error('expenseAmount')
                                        <span class="flex items-center mt-1 text-xs text-red-600">
                                            <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="expensePurpose"
                                    class="block mb-1 text-xs font-medium text-gray-700">Purpose</label>
                                <input type="text" wire:model="expensePurpose" id="expensePurpose"
                                    placeholder="Enter expense purpose"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                @error('expensePurpose')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="px-4 py-2 text-xs font-medium text-white transition-colors bg-red-600 rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                    <i class="mr-1 fas fa-save"></i>
                                    Save Expense
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Expense List -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900">
                                <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                                Expense History
                            </h3>
                            <a href="{{ route('expense_report') }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                                <i class="mr-1 fas fa-chart-line"></i>
                                View Report
                            </a>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
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

                            @if ($expenses->isEmpty())
                                <div class="p-8 text-center">
                                    <i class="mb-3 text-3xl text-gray-400 fas fa-receipt"></i>
                                    <p class="text-sm text-gray-600">No expenses found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Expense Stats -->
                        <div class="p-4 mt-4 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-pink-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <i class="text-sm text-red-600 fas fa-chart-pie"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-red-700">Total Expenses</h3>
                                        <p class="text-lg font-bold text-red-800">
                                            @formatNumber($expenses->sum('amount'))
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="mb-1 text-xs text-red-600">This Month</div>
                                    <div class="text-sm font-medium text-red-700">
                                        @formatNumber($expenses->filter(fn($e) => \Carbon\Carbon::parse($e->date)->isCurrentMonth())->sum('amount'))
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Income Tab Content -->
            @if ($activeTab === 'income')
                <div class="p-4">
                    <!-- Add Income Form -->
                    <div class="mb-6">
                        <h3 class="mb-4 text-sm font-semibold text-gray-900">
                            <i class="mr-2 text-xs text-green-600 fas fa-plus-circle"></i>
                            Add New Income
                        </h3>
                        <form wire:submit.prevent="saveIncome" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="incomeDate"
                                        class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                                    <input type="date" wire:model="incomeDate" id="incomeDate"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    @error('incomeDate')
                                        <span class="flex items-center mt-1 text-xs text-red-600">
                                            <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="incomeAmount"
                                        class="block mb-1 text-xs font-medium text-gray-700">Amount</label>
                                    <input type="number" step="0.01" wire:model="incomeAmount" id="incomeAmount"
                                        placeholder="Enter amount"
                                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    @error('incomeAmount')
                                        <span class="flex items-center mt-1 text-xs text-red-600">
                                            <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="incomeType" class="block mb-1 text-xs font-medium text-gray-700">Income
                                    Type</label>
                                <select wire:model="incomeType" id="incomeType"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="bank_interest">Bank Interest</option>
                                    <option value="commission">Commission</option>
                                    <option value="bonus">Bonus</option>
                                    <option value="refund">Refund</option>
                                    <option value="other">Other Income</option>
                                </select>
                                @error('incomeType')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="incomeSource"
                                    class="block mb-1 text-xs font-medium text-gray-700">Source/Description</label>
                                <input type="text" wire:model="incomeSource" id="incomeSource"
                                    placeholder="Enter income source"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                @error('incomeSource')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                                    <i class="mr-1 fas fa-save"></i>
                                    Save Income
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Income List -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900">
                                <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                                Income History
                            </h3>
                            <a href="{{ route('income_report') }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors">
                                <i class="mr-1 fas fa-chart-line"></i>
                                View Report
                            </a>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-gray-100 bg-gray-50">
                                        <th class="px-4 py-3 font-medium text-left text-gray-700">Date</th>
                                        <th class="px-4 py-3 font-medium text-left text-gray-700">Type</th>
                                        <th class="px-4 py-3 font-medium text-right text-gray-700">Amount</th>
                                        <th class="px-4 py-3 font-medium text-left text-gray-700">Source</th>
                                        <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($incomes as $income)
                                        <tr class="transition-colors hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-900">
                                                {{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                    @if ($income->type == 'extra') bg-blue-100 text-blue-700
                                                    @elseif($income->type == 'commission') bg-purple-100 text-purple-700
                                                    @elseif($income->type == 'bonus') bg-pink-100 text-pink-700
                                                    @elseif($income->type == 'refund') bg-orange-100 text-orange-700
                                                    @elseif($income->type == 'investment_return') bg-indigo-100 text-indigo-700
                                                    @else bg-green-100 text-green-700 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $income->type)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 font-medium text-right text-green-600">
                                                +@formatNumber($income->amount)
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $income->source }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button wire:click="deleteIncome({{ $income->id }})"
                                                    class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                                    onclick="return confirm('Are you sure you want to delete this income?')">
                                                    <i class="text-xs fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($incomes->isEmpty())
                                <div class="p-8 text-center">
                                    <i class="mb-3 text-3xl text-gray-400 fas fa-money-bill-wave"></i>
                                    <p class="text-sm text-gray-600">No income found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Income Stats -->
                        <div
                            class="p-4 mt-4 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="text-sm text-green-600 fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-green-700">Total Income</h3>
                                        <p class="text-lg font-bold text-green-800">
                                            @formatNumber($incomes->sum('amount'))
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="mb-1 text-xs text-green-600">This Month</div>
                                    <div class="text-sm font-medium text-green-700">
                                        @formatNumber($incomes->filter(fn($i) => \Carbon\Carbon::parse($i->date)->isCurrentMonth())->sum('amount'))
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Income by Type -->
                        <div class="grid grid-cols-2 gap-3 mt-4 sm:grid-cols-3 lg:grid-cols-6">
                            @php
                                $incomeByType = $incomes->groupBy('type');
                            @endphp
                            @foreach (['extra' => 'Extra', 'other' => 'Other', 'commission' => 'Commission', 'bonus' => 'Bonus', 'refund' => 'Refund', 'investment_return' => 'Investment'] as $type => $label)
                                <div class="p-3 text-center bg-white border border-gray-200 rounded-lg">
                                    <div class="text-xs font-medium text-gray-600">{{ $label }}</div>
                                    <div class="mt-1 text-sm font-bold text-green-600">
                                        @formatNumber($incomeByType->get($type, collect())->sum('amount'))
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <style>
        .transition-colors {
            transition: all 0.15s ease-in-out;
        }

        button:hover {
            transform: translateY(-0.5px);
        }

        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</div>
