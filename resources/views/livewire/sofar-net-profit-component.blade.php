<div>
    <!-- Here form hided becuse this entry only one time given -->
    <div class="max-w-4xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Add Net Profit Form -->
        {{-- <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-green-500 fas fa-chart-line"></i>
                    Record Net Profit
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" id="date" wire:model="date"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            @error('date')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block mb-1 text-xs font-medium text-gray-700">Net Profit
                                Amount</label>
                            <input type="number" step="0.01" id="amount" wire:model="amount"
                                placeholder="Enter profit amount"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            @error('amount')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="mr-1 fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Save Profit
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        <!-- Net Profit Summary -->
        <div class="p-4 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="text-sm text-green-600 fas fa-trophy"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-green-700">Total Net Profit</h3>
                        <p class="text-lg font-bold text-green-800">
                            @php
                                $totalProfit = $sofarNetProfits->sum('amount');
                            @endphp
                            {{ number_format($totalProfit, 2) }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-1 text-xs text-green-600">Latest Entry</div>
                    <div class="text-sm font-medium text-green-700">
                        @if ($sofarNetProfits->first())
                            {{ \Carbon\Carbon::parse($sofarNetProfits->first()->date)->format('M d, Y') }}
                        @else
                            No entries yet
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Profit List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                    Net Profit History
                </h2>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Date</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Amount</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($sofarNetProfits as $profit)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($profit->date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-green-700">
                                    +{{ number_format($profit->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- <button wire:click="edit({{ $profit->id }})"
                                            class="p-1 text-blue-600 transition-colors rounded hover:bg-blue-100">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $profit->id }})"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this profit entry?')">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if ($sofarNetProfits->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-chart-line"></i>
                    <p class="text-sm text-gray-600">No profit entries found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first profit entry using the form above</p>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Average Profit -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Average Profit</span>
                    <i class="text-sm text-blue-500 fas fa-calculator opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $avgProfit = $sofarNetProfits->count() > 0 ? $sofarNetProfits->avg('amount') : 0;
                    @endphp
                    {{ number_format($avgProfit, 2) }}
                </div>
            </div>

            <!-- Total Entries -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Total Entries</span>
                    <i class="text-sm text-purple-500 fas fa-counter opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    {{ $sofarNetProfits->count() }}
                </div>
            </div>

            <!-- Best Month -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Best Performance</span>
                    <i class="text-sm text-yellow-500 fas fa-star opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $maxProfit = $sofarNetProfits->max('amount') ?? 0;
                    @endphp
                    {{ number_format($maxProfit, 2) }}
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

        /* Green theme focus states */
        input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Enhanced number display */
        .text-green-700 {
            color: #15803d;
        }

        /* Stats cards hover effect */
        .bg-white:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
    </style>

</div>
