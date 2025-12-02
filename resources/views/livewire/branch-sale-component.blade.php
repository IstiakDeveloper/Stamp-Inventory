<div>
    <div class="p-3 mx-auto space-y-4 max-w-7xl">

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
            <i class="mr-1 fas fa-check-circle"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Branch Sale Form -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
            <h2 class="text-sm font-semibold text-gray-900">
                <i class="mr-2 text-xs text-blue-500 fas fa-shopping-cart"></i>
                Branch Sale Entry
            </h2>
        </div>

        <!-- Form -->
        <div class="p-4">
            <form wire:submit.prevent="saveSale" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

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

                    <!-- Branch -->
                    <div>
                        <label for="branch_id" class="block mb-1 text-xs font-medium text-gray-700">Branch</label>
                        <select wire:model.live="branch_id" id="branch_id"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')
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
                            placeholder="Enter sets"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @error('sets')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Per Set Price (Auto-filled) -->
                    <div>
                        <label for="per_set_price" class="block mb-1 text-xs font-medium text-gray-700">Price Per
                            Set</label>
                        <div
                            class="w-full px-3 py-2 text-xs text-gray-600 border border-gray-200 rounded-md bg-gray-50">
                            ৳{{ $perSetPrice ? number_format($perSetPrice, 2) : '0.00' }}
                        </div>
                    </div>

                    <!-- Cash Received -->
                    <div>
                        <label for="cash" class="block mb-1 text-xs font-medium text-gray-700">Cash Received</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-xs text-gray-400">৳</span>
                            </div>
                            <input type="text" wire:model.live="cash" id="cash" placeholder="Enter cash amount"
                                class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('cash')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Receiver Name -->
                    <div>
                        <label for="receiver_name" class="block mb-1 text-xs font-medium text-gray-700">Receiver
                            Name</label>
                        <input type="text" wire:model.live="receiver_name" id="receiver_name"
                            placeholder="Enter receiver name"
                            class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @error('receiver_name')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Total Price -->
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Total Sale Amount</label>
                        <div
                            class="w-full px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-md bg-green-50">
                            ৳@if ($sets > 0)
                                @formatNumber($totalPrice)
                            @else
                                0.00
                            @endif
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Payment Status</label>
                        <div class="w-full px-3 py-2 text-xs border rounded-md">
                            @if ($cash !== null && is_numeric($cash))
                                @if ($extraMoney !== null)
                                    @if ($extraMoney > 0)
                                        <div class="text-green-600 border-green-200 bg-green-50">
                                            Extra: +৳@formatNumber($extraMoney)
                                        </div>
                                    @elseif ($extraMoney < 0)
                                        <div class="text-red-600 border-red-200 bg-red-50">
                                            Due: -৳@formatNumber(abs($extraMoney))
                                        </div>
                                    @else
                                        <div class="text-gray-600 border-gray-200 bg-gray-50">
                                            Exact Amount
                                        </div>
                                    @endif
                                @endif
                            @else
                                <div class="text-gray-400 border-gray-200 bg-gray-50">
                                    Enter cash amount
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
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
                    Branch Sales History
                </h2>
                <a href="{{ route('branch_sale_report') }}"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                    <i class="mr-1 fas fa-chart-line"></i>
                    View Report
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <!-- Branch Filter -->
                <div>
                    <input type="text" wire:model.live="searchBranch" placeholder="Search branch..."
                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Date Filter -->
                <div>
                    <input type="date" wire:model.live="searchDate"
                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Per Page -->
                <div>
                    <select wire:model.live="perPage"
                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <div>
                    <button wire:click="clearFilters"
                        class="w-full px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                        <i class="mr-1 fas fa-times"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-4 py-3 font-medium text-left text-gray-700">
                            <button wire:click="sortBy('date')" class="flex items-center hover:text-blue-600">
                                Date
                                @if ($sortBy === 'date')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                    <i class="ml-1 text-xs opacity-50 fas fa-sort"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-medium text-left text-gray-700">Branch</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-700">
                            <button wire:click="sortBy('sets')" class="flex items-center ml-auto hover:text-blue-600">
                                Sets
                                @if ($sortBy === 'sets')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                    <i class="ml-1 text-xs opacity-50 fas fa-sort"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-medium text-right text-gray-700">Unit Price</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-700">
                            <button wire:click="sortBy('total_price')"
                                class="flex items-center ml-auto hover:text-blue-600">
                                Total
                                @if ($sortBy === 'total_price')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                    <i class="ml-1 text-xs opacity-50 fas fa-sort"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-medium text-right text-gray-700">
                            <button wire:click="sortBy('cash')" class="flex items-center ml-auto hover:text-blue-600">
                                Cash
                                @if ($sortBy === 'cash')
                                    <i
                                        class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-xs"></i>
                                @else
                                    <i class="ml-1 text-xs opacity-50 fas fa-sort"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 font-medium text-left text-gray-700">Receiver</th>
                        <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($branchSales as $transaction)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 truncate max-w-24">
                                {{ $transaction->branch->branch_name }}
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
                            <td class="px-4 py-3 text-gray-600 truncate max-w-20">
                                {{ $transaction->receiver_name }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="deleteSale({{ $transaction->id }})"
                                    class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                    onclick="return confirm('Are you sure you want to delete this sale?')">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
            <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                <!-- Results Info -->
                <div class="order-2 text-xs text-gray-600 sm:order-1">
                    Showing {{ $branchSales->firstItem() ?? 0 }} to {{ $branchSales->lastItem() ?? 0 }}
                    of {{ $branchSales->total() }} results
                </div>

                <!-- Pagination Links -->
                <div class="order-1 sm:order-2">
                    @if ($branchSales->hasPages())
                        <nav class="flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($branchSales->onFirstPage())
                                <span class="px-2 py-1 text-xs text-gray-400 bg-white border border-gray-200 rounded cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $branchSales->previousPageUrl() }}"
                                   class="px-2 py-1 text-xs text-gray-600 transition-colors bg-white border border-gray-200 rounded hover:bg-gray-50">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($branchSales->getUrlRange(1, $branchSales->lastPage()) as $page => $url)
                                @if ($page == $branchSales->currentPage())
                                    <span class="px-3 py-1 text-xs font-medium text-white bg-blue-600 border border-blue-600 rounded">
                                        {{ $page }}
                                    </span>
                                @else
                                    @if ($page == 1 || $page == $branchSales->lastPage() ||
                                        ($page >= $branchSales->currentPage() - 2 && $page <= $branchSales->currentPage() + 2))
                                        <a href="{{ $url }}"
                                           class="px-3 py-1 text-xs text-gray-600 transition-colors bg-white border border-gray-200 rounded hover:bg-gray-50">
                                            {{ $page }}
                                        </a>
                                    @elseif ($page == $branchSales->currentPage() - 3 || $page == $branchSales->currentPage() + 3)
                                        <span class="px-2 py-1 text-xs text-gray-400">...</span>
                                    @endif
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($branchSales->hasMorePages())
                                <a href="{{ $branchSales->nextPageUrl() }}"
                                   class="px-2 py-1 text-xs text-gray-600 transition-colors bg-white border border-gray-200 rounded hover:bg-gray-50">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-2 py-1 text-xs text-gray-400 bg-white border border-gray-200 rounded cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        </div>

        <!-- Empty State -->
        @if ($branchSales->isEmpty())
            <div class="p-8 text-center">
                <i class="mb-3 text-3xl text-gray-400 fas fa-shopping-cart"></i>
                <p class="text-sm text-gray-600">No branch sales found</p>
                <p class="mt-1 text-xs text-gray-500">
                    @if ($searchBranch || $searchDate)
                        Try adjusting your filters or
                        <button wire:click="clearFilters" class="text-blue-600 hover:text-blue-800">clear all
                            filters</button>
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
                    $totalSales = $branchSales->sum('total_price');
                @endphp
                ৳@formatNumber($totalSales)
            </div>
        </div>

        <!-- Total Cash -->
        <div class="p-3 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-blue-600">Cash Collected</span>
                <i class="text-sm text-blue-500 fas fa-money-bill opacity-70"></i>
            </div>
            <div class="text-sm font-bold text-blue-700">
                @php
                    $totalCash = $branchSales->sum('cash');
                @endphp
                ৳@formatNumber($totalCash)
            </div>
        </div>

        <!-- Total Outstanding -->
        <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-red-600">Outstanding</span>
                <i class="text-sm text-red-500 fas fa-exclamation-triangle opacity-70"></i>
            </div>
            <div class="text-sm font-bold text-red-700">
                @php
                    $totalOutstanding = $totalSales - $totalCash;
                @endphp
                ৳{{ number_format($totalOutstanding, 2) }}
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="p-3 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">Transactions</span>
                <i class="text-sm text-purple-500 fas fa-receipt opacity-70"></i>
            </div>
            <div class="text-sm font-bold text-gray-900">
                {{ $branchSales->count() }}
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
    select:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Button hover effects */
    button:hover {
        transform: translateY(-0.5px);
    }

    /* Payment status styling */
    .bg-green-50 {
        background-color: #f0fdf4;
    }

    .border-green-200 {
        border-color: #bbf7d0;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .bg-red-50 {
        background-color: #fef2f2;
    }

    .border-red-200 {
        border-color: #fecaca;
    }

    .text-red-600 {
        color: #dc2626;
    }

    .bg-gray-50 {
        background-color: #f9fafb;
    }

    .border-gray-200 {
        border-color: #e5e7eb;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    /* Summary cards hover */
    .rounded-lg:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>

</div>
