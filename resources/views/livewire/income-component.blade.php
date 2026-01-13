<div>
    <div class="max-w-4xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Add Income Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gradient-to-r from-green-50 to-emerald-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-green-600 fas fa-plus-circle"></i>
                    Add New Income
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="saveIncome" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" wire:model="date" id="date"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
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
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            @error('amount')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block mb-1 text-xs font-medium text-gray-700">Income Type</label>
                        <select wire:model="type" id="type"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            <option value="bank_interest">Bank Interest</option>
                            <option value="commission">Commission</option>
                            <option value="bonus">Bonus</option>
                            <option value="refund">Refund</option>
                            <option value="other">Other Income</option>
                        </select>
                        @error('type')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Source -->
                    <div>
                        <label for="source" class="block mb-1 text-xs font-medium text-gray-700">Source/Description</label>
                        <input type="text" wire:model="source" id="source" placeholder="Enter income source"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                        @error('source')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Save Income
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Income List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Income History
                    </h2>
                    <a href="{{ route('income_report') }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors">
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
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                        @if($income->type == 'extra') bg-blue-100 text-blue-700
                                        @elseif($income->type == 'commission') bg-purple-100 text-purple-700
                                        @elseif($income->type == 'bonus') bg-pink-100 text-pink-700
                                        @elseif($income->type == 'refund') bg-orange-100 text-orange-700
                                        @elseif($income->type == 'investment_return') bg-indigo-100 text-indigo-700
                                        @else bg-green-100 text-green-700
                                        @endif">
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
            </div>

            <!-- Empty State -->
            @if ($incomes->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-money-bill-wave"></i>
                    <p class="text-sm text-gray-600">No income found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first income using the form above</p>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="p-4 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="text-sm text-green-600 fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-green-700">Total Income</h3>
                        <p class="text-lg font-bold text-green-800">
                            @php
                                $totalIncome = $incomes->sum('amount');
                            @endphp
                            @formatNumber($totalIncome)
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-1 text-xs text-green-600">This Month</div>
                    <div class="text-sm font-medium text-green-700">
                        @php
                            $thisMonthIncome = $incomes
                                ->filter(function ($income) {
                                    return \Carbon\Carbon::parse($income->date)->isCurrentMonth();
                                })
                                ->sum('amount');
                        @endphp
                        @formatNumber($thisMonthIncome)
                    </div>
                </div>
            </div>
        </div>

        <!-- Income by Type Stats -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @php
                $incomeByType = $incomes->groupBy('type');
            @endphp
            @foreach(['extra' => 'Extra', 'other' => 'Other', 'commission' => 'Commission', 'bonus' => 'Bonus', 'refund' => 'Refund', 'investment_return' => 'Investment'] as $type => $label)
                <div class="p-3 text-center bg-white border border-gray-200 rounded-lg">
                    <div class="text-xs font-medium text-gray-600">{{ $label }}</div>
                    <div class="mt-1 text-sm font-bold text-green-600">
                        @formatNumber($incomeByType->get($type, collect())->sum('amount'))
                    </div>
                </div>
            @endforeach
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
        input:focus, select:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Green theme for income */
        .text-green-600 {
            color: #16a34a;
        }

        .bg-green-100:hover {
            background-color: #dcfce7;
        }

        /* Table hover effects */
        tr:hover {
            background-color: #f9fafb;
        }
    </style>

</div>
