<div>
    <div class="max-w-6xl p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Head Office Sale Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-green-500 fas fa-home"></i>
                    Head Office Sale Entry
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="saveSale" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Sale Date</label>
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
                            <label for="sets" class="block mb-1 text-xs font-medium text-gray-700">Number of
                                Sets</label>
                            <input type="number" wire:model.live="sets" id="sets" step="any"
                                placeholder="Enter sets sold"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('sets')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Per Stamp Price -->
                        <div>
                            <label for="per_stamp_price" class="block mb-1 text-xs font-medium text-gray-700">Price Per
                                Set</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-xs text-gray-400">৳</span>
                                </div>
                                <input type="text" wire:model.live="per_stamp_price" id="per_stamp_price"
                                    placeholder="Enter price per set"
                                    class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('per_stamp_price')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Total Price (Auto-calculated) -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Sale Amount</label>
                            <div
                                class="w-full px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-md bg-green-50">
                                ৳@if (!is_null($sets) && !is_null($per_stamp_price))
                                    @formatNumber($totalPrice)
                                @else
                                    0.00
                                @endif
                            </div>
                        </div>

                        <!-- Cash Received -->
                        <div>
                            <label for="cash" class="block mb-1 text-xs font-medium text-gray-700">Cash
                                Received</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-xs text-gray-400">৳</span>
                                </div>
                                <input type="text" wire:model.live="cash" id="cash"
                                    placeholder="Enter cash received"
                                    class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('cash')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                            <textarea id="note" wire:model="note" rows="2" placeholder="Enter sale notes (optional)"
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
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Record Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sales List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Head Office Sales History
                    </h2>

                    <div class="flex items-center space-x-3">
                        <!-- Per Page Selector -->
                        <div class="flex items-center space-x-2">
                            <label for="perPage" class="text-xs text-gray-600">Show:</label>
                            <select wire:model.live="perPage" id="perPage"
                                class="px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-blue-500">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        <a href="{{ route('ho_sale_report') }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors">
                            <i class="mr-1 fas fa-chart-line"></i>
                            View Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4 sm:items-center">
                    <!-- Date Filter -->
                    <div class="flex items-center space-x-2">
                        <label for="searchDate" class="text-xs font-medium text-gray-600">Filter by Date:</label>
                        <input type="date" wire:model.live="searchDate" id="searchDate"
                            class="px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Clear Filters -->
                    @if($searchDate)
                        <button wire:click="clearFilters"
                            class="px-3 py-1 text-xs text-red-600 transition-colors border border-red-200 rounded hover:bg-red-50">
                            <i class="mr-1 fas fa-times"></i>
                            Clear Filters
                        </button>
                    @endif

                    <!-- Search Results Info -->
                    @if($transactions->total() > 0)
                        <div class="text-xs text-gray-500">
                            {{ $transactions->total() }} total records found
                            @if($searchDate)
                                for {{ \Carbon\Carbon::parse($searchDate)->format('M d, Y') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-700">
                                <button wire:click="sortBy('date')" class="flex items-center space-x-1 hover:text-blue-600">
                                    <span>Date</span>
                                    @if($sortBy === 'date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('sets')" class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Sets</span>
                                    @if($sortBy === 'sets')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Unit Price</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('total_price')" class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Total</span>
                                    @if($sortBy === 'total_price')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('cash')" class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Cash</span>
                                    @if($sortBy === 'cash')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Due</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transactions as $transaction)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}
                                    @if($transaction->note)
                                        <div class="text-xs text-gray-500 truncate" title="{{ $transaction->note }}">
                                            {{ Str::limit($transaction->note, 30) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-gray-900">
                                    @php
                                        $value = $transaction->sets;
                                        $formattedValue =
                                            $value - floor($value) > 0
                                                ? number_format($value, 2)
                                                : number_format($value, 0);
                                    @endphp
                                    {{ $formattedValue }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    ৳@formatNumber($transaction->per_set_price)
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-green-700">
                                    ৳@formatNumber($transaction->total_price)
                                </td>
                                <td class="px-4 py-3 text-right text-blue-600">
                                    ৳@formatNumber($transaction->cash)
                                </td>
                                <td class="px-4 py-3 text-right text-red-600">
                                    @php
                                        $due = $transaction->headOfficeDue;
                                        $dueAmount = $due ? $due->due_amount : 0;
                                    @endphp
                                    ৳{{ number_format($dueAmount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="deleteSale({{ $transaction->id }})"
                                        class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                        onclick="return confirm('Are you sure you want to delete this sale?')"
                                        title="Delete Sale">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-between space-y-3 sm:flex-row sm:space-y-0">
                        <!-- Results Info -->
                        <div class="text-xs text-gray-600">
                            Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }}
                            of {{ $transactions->total() }} results
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($transactions->onFirstPage())
                                <span class="px-2 py-1 text-xs text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <button wire:click="previousPage" class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if ($page == $transactions->currentPage())
                                    <span class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="px-3 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($transactions->hasMorePages())
                                <button wire:click="nextPage" class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @else
                                <span class="px-2 py-1 text-xs text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if ($transactions->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-home"></i>
                    <p class="text-sm text-gray-600">
                        @if($searchDate)
                            No sales found for {{ \Carbon\Carbon::parse($searchDate)->format('M d, Y') }}
                        @else
                            No HO sales found
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        @if($searchDate)
                            Try selecting a different date or clear the filter
                        @else
                            Record your first sale using the form above
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Sales Summary -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <!-- Total Sales -->
            <div class="p-3 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Total Sales</span>
                    <i class="text-sm text-green-500 fas fa-arrow-up opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-green-700">
                    @php
                        $totalSales = $allTransactions->sum('total_price');
                    @endphp
                    ৳@formatNumber($totalSales)
                </div>
                <div class="text-xs text-green-600 opacity-75">
                    All time total
                </div>
            </div>

            <!-- Total Cash -->
            <div class="p-3 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-blue-600">Cash Received</span>
                    <i class="text-sm text-blue-500 fas fa-money-bill opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-blue-700">
                    @php
                        $totalCash = $allTransactions->sum('cash');
                    @endphp
                    ৳@formatNumber($totalCash)
                </div>
                <div class="text-xs text-blue-600 opacity-75">
                    All time received
                </div>
            </div>

            <!-- Total Due -->
            <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Due</span>
                    <i class="text-sm text-red-500 fas fa-exclamation-triangle opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-red-700">
                    @php
                        $totalDue = $allTransactions->sum('total_price') - $allTransactions->sum('cash');
                    @endphp
                    ৳{{ number_format($totalDue, 2) }}
                </div>
                <div class="text-xs text-red-600 opacity-75">
                    Outstanding amount
                </div>
            </div>

            <!-- Sets Sold -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Sets Sold</span>
                    <i class="text-sm text-purple-500 fas fa-box opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $totalSets = $allTransactions->sum('sets');
                    @endphp
                    {{ number_format($totalSets, 0) }}
                </div>
                <div class="text-xs text-gray-500 opacity-75">
                    Total units
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
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Focus states */
        input:focus,
        textarea:focus,
        select:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Currency input styling */
        input[type="text"]::-webkit-outer-spin-button,
        input[type="text"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Color theme enhancements */
        .text-green-700 {
            color: #15803d;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .bg-green-50 {
            background-color: #f0fdf4;
        }

        .border-green-200 {
            border-color: #bbf7d0;
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Sortable table headers */
        th button {
            width: 100%;
            transition: color 0.15s ease-in-out;
        }

        th button:hover {
            color: #2563eb;
        }

        /* Loading states */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Responsive summary cards */
        @media (max-width: 640px) {
            .grid.sm\:grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.75rem;
            }
        }
    </style>

</div>
