<div>
    <div class="max-w-6xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Add Reject/Free Transaction Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-red-500 fas fa-times-circle"></i>
                    Add Reject or Free Entry
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="saveTransaction" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Date</label>
                            <input type="date" wire:model.live="date" id="date"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('date')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Sets -->
                        <div>
                            <label for="sets" class="block mb-1 text-xs font-medium text-gray-700">Sets</label>
                            <input type="number" step="0.001" wire:model.live="sets" id="sets"
                                placeholder="Enter sets"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('sets')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Purchase Price Per Set (Auto-calculated) -->
                        <div>
                            <label for="purchase_price_per_set"
                                class="block mb-1 text-xs font-medium text-gray-700">Price
                                Per Set</label>
                            <div
                                class="w-full px-3 py-2 text-xs text-gray-600 border border-gray-200 rounded-md bg-gray-50">
                                ৳{{ $this->formatted_purchase_price_per_set ?? '0.00' }}
                            </div>
                        </div>

                        <!-- Purchase Price Total (Auto-calculated) -->
                        <div>
                            <label for="purchase_price_total" class="block mb-1 text-xs font-medium text-gray-700">Total
                                Loss</label>
                            <div
                                class="w-full px-3 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-md bg-red-50">
                                ৳{{ $this->formatted_purchase_price_total ?? '0.00' }}
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div>
                        <label for="note" class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                        <textarea wire:model.live="note" id="note" rows="2"
                            placeholder="Enter reason for rejection or free distribution"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('note')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-red-600 rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Record Loss Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject/Free List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Reject & Free History
                    </h2>
                    <a href="{{ route('reject_free_report') }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
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
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Sets</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Unit Price</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Total Loss</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Note</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($rejectOrFrees as $rejectOrFree)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($rejectOrFree->date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-gray-900">
                                    @php
                                        $value = $rejectOrFree->sets;
                                        $formattedValue =
                                            $value - floor($value) > 0
                                                ? number_format($value, 3)
                                                : number_format($value, 0);
                                    @endphp
                                    {{ $formattedValue }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    ৳@formatNumber($rejectOrFree->purchase_price_per_set)
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-red-600">
                                    -৳@formatNumber($rejectOrFree->purchase_price_total)
                                </td>
                                <td class="px-4 py-3 text-gray-600 truncate max-w-32">
                                    {{ $rejectOrFree->note ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="deleteTransaction({{ $rejectOrFree->id }})"
                                        class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                        onclick="return confirm('Are you sure you want to delete this entry?')">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if ($rejectOrFrees->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-times-circle"></i>
                    <p class="text-sm text-gray-600">No reject or free entries found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first entry using the form above</p>
                </div>
            @endif
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Total Loss -->
            <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Loss</span>
                    <i class="text-sm text-red-500 fas fa-arrow-down opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-red-700">
                    @php
                        $totalLoss = $rejectOrFrees->sum('purchase_price_total');
                    @endphp
                    ৳@formatNumber($totalLoss)
                </div>
            </div>

            <!-- Total Sets -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Total Sets Lost</span>
                    <i class="text-sm text-orange-500 fas fa-box opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $totalSets = $rejectOrFrees->sum('sets');
                    @endphp
                    {{ number_format($totalSets, 0) }}
                </div>
            </div>

            <!-- Average Loss -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Avg Loss/Entry</span>
                    <i class="text-sm text-purple-500 fas fa-calculator opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $avgLoss = $rejectOrFrees->count() > 0 ? $totalLoss / $rejectOrFrees->count() : 0;
                    @endphp
                    ৳{{ number_format($avgLoss, 0) }}
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

        /* Grid responsive */
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Truncate text */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Focus states */
        input:focus,
        textarea:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Red theme colors */
        .text-red-600 {
            color: #dc2626;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .border-red-200 {
            border-color: #fecaca;
        }
    </style>

</div>
