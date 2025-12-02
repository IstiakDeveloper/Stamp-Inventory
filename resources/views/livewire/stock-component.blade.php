<div>
    <div class="max-w-6xl p-3 mx-auto space-y-4">

        <!-- Header with Stock Summary -->
        <div class="p-4 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="text-sm text-blue-600 fas fa-boxes"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-blue-700">Stock Management</h2>
                        <p class="text-xs text-blue-600">Manage your inventory purchases</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-1 text-xs text-blue-600">Available Stock</div>
                    <div class="text-lg font-bold text-blue-800">
                        @php
                            $value = $totalSet;
                            $formattedValue =
                                $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }} sets
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('flash_message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('flash_message') }}
            </div>
        @endif

        <!-- Add/Edit Stock Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-{{ $stockId ? 'edit' : 'plus' }} mr-2 text-blue-500 text-xs"></i>
                    {{ $stockId ? 'Edit Purchase Entry' : 'Add New Purchase' }}
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="{{ $stockId ? 'update' : 'store' }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        <!-- Date -->
                        <div>
                            <label for="date" class="block mb-1 text-xs font-medium text-gray-700">Purchase
                                Date</label>
                            <input type="date" id="date" wire:model.live="date"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('date')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block mb-1 text-xs font-medium text-gray-700">Supplier
                                Address</label>
                            <input type="text" wire:model.live="address" id="address"
                                placeholder="Enter supplier address"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('address')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Sets -->
                        <div>
                            <label for="sets" class="block mb-1 text-xs font-medium text-gray-700">Number of
                                Sets</label>
                            <input type="number" wire:model.live="sets" id="sets"
                                placeholder="Enter sets quantity"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('sets')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Price Per Set -->
                        <div>
                            <label for="price_per_set" class="block mb-1 text-xs font-medium text-gray-700">Price Per
                                Set</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-xs text-gray-400">৳</span>
                                </div>
                                <input type="number" step="0.01" wire:model.live="price_per_set" id="price_per_set"
                                    placeholder="Enter price per set"
                                    class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('price_per_set')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Total Price Display -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Purchase Cost</label>
                            <div
                                class="w-full px-3 py-2 text-xs font-medium text-red-700 border border-red-200 rounded-md bg-red-50">
                                @php
                                    $sets_val = floatval($sets ?? 0);
                                    $price_val = floatval($price_per_set ?? 0);
                                    $total = $sets_val * $price_val;
                                @endphp
                                ৳{{ number_format($total, 2) }}
                            </div>
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                            <textarea wire:model.live="note" id="note" rows="2" placeholder="Enter notes (optional)"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('note')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="resetInputFields"
                            class="px-4 py-2 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="mr-1 fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="fas fa-{{ $stockId ? 'save' : 'plus' }} mr-1"></i>
                            {{ $stockId ? 'Update Purchase' : 'Add Purchase' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Purchase List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Purchase History
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

                        <a href="{{ route('stock_register_report') }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
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

                    <!-- Address Filter -->
                    <div class="flex items-center space-x-2">
                        <label for="searchAddress" class="text-xs font-medium text-gray-600">Supplier:</label>
                        <input type="text" wire:model.live="searchAddress" id="searchAddress"
                            placeholder="Search supplier..."
                            class="px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Clear Filters -->
                    @if ($searchDate || $searchAddress)
                        <button wire:click="clearFilters"
                            class="px-3 py-1 text-xs text-red-600 transition-colors border border-red-200 rounded hover:bg-red-50">
                            <i class="mr-1 fas fa-times"></i>
                            Clear Filters
                        </button>
                    @endif

                    <!-- Search Results Info -->
                    @if ($stocks->total() > 0)
                        <div class="text-xs text-gray-500">
                            {{ $stocks->total() }} total records found
                            @if ($searchDate || $searchAddress)
                                @if ($searchDate && $searchAddress)
                                    for {{ \Carbon\Carbon::parse($searchDate)->format('M d, Y') }}
                                    and supplier "{{ $searchAddress }}"
                                @elseif($searchDate)
                                    for {{ \Carbon\Carbon::parse($searchDate)->format('M d, Y') }}
                                @else
                                    for supplier "{{ $searchAddress }}"
                                @endif
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
                                <button wire:click="sortBy('date')"
                                    class="flex items-center space-x-1 hover:text-blue-600">
                                    <span>Date</span>
                                    @if ($sortBy === 'date')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">
                                <button wire:click="sortBy('address')"
                                    class="flex items-center space-x-1 hover:text-blue-600">
                                    <span>Supplier</span>
                                    @if ($sortBy === 'address')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('sets')"
                                    class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Sets</span>
                                    @if ($sortBy === 'sets')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('price_per_set')"
                                    class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Unit Price</span>
                                    @if ($sortBy === 'price_per_set')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">
                                <button wire:click="sortBy('total_price')"
                                    class="flex items-center justify-end space-x-1 hover:text-blue-600">
                                    <span>Total</span>
                                    @if ($sortBy === 'total_price')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                                    @else
                                        <i class="text-gray-400 fas fa-sort"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Note</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($stocks as $stock)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($stock->date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 truncate max-w-32" title="{{ $stock->address }}">
                                    {{ $stock->address }}
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-gray-900">
                                    @php
                                        $value = $stock->sets;
                                        $formattedValue =
                                            $value - floor($value) > 0
                                                ? number_format($value, 2)
                                                : number_format($value, 0);
                                    @endphp
                                    {{ $formattedValue }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    ৳@formatNumber($stock->price_per_set)
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-red-700">
                                    ৳@formatNumber($stock->total_price)
                                </td>
                                <td class="px-4 py-3 text-gray-600 truncate max-w-24" title="{{ $stock->note }}">
                                    {{ $stock->note ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $stock->id }})"
                                            class="p-1 text-blue-600 transition-colors rounded hover:bg-blue-100"
                                            title="Edit Purchase">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $stock->id }})"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this purchase?')"
                                            title="Delete Purchase">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($stocks->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-between space-y-3 sm:flex-row sm:space-y-0">
                        <!-- Results Info -->
                        <div class="text-xs text-gray-600">
                            Showing {{ $stocks->firstItem() }} to {{ $stocks->lastItem() }}
                            of {{ $stocks->total() }} results
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($stocks->onFirstPage())
                                <span class="px-2 py-1 text-xs text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <button wire:click="previousPage"
                                    class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($stocks->getUrlRange(1, $stocks->lastPage()) as $page => $url)
                                @if ($page == $stocks->currentPage())
                                    <span class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="px-3 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($stocks->hasMorePages())
                                <button wire:click="nextPage"
                                    class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-200 rounded hover:bg-gray-50">
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
            @if ($stocks->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-boxes"></i>
                    <p class="text-sm text-gray-600">
                        @if ($searchDate || $searchAddress)
                            No purchase records found for the selected filters
                        @else
                            No purchase records found
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        @if ($searchDate || $searchAddress)
                            Try adjusting your filters or clear them to see all records
                        @else
                            Add your first purchase using the form above
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <!-- Total Purchases -->
            <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Purchases</span>
                    <i class="text-sm text-red-500 fas fa-shopping-cart opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-red-700">
                    @php
                        $totalPurchases = $allStocks->sum('total_price');
                    @endphp
                    ৳@formatNumber($totalPurchases)
                </div>
                <div class="text-xs text-red-600 opacity-75">
                    All time cost
                </div>
            </div>

            <!-- Total Sets Purchased -->
            <div class="p-3 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-blue-600">Sets Purchased</span>
                    <i class="text-sm text-blue-500 fas fa-boxes opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-blue-700">
                    @php
                        $totalSetsPurchased = $allStocks->sum('sets');
                    @endphp
                    {{ number_format($totalSetsPurchased, 0) }}
                </div>
                <div class="text-xs text-blue-600 opacity-75">
                    Total inventory
                </div>
            </div>

            <!-- Average Purchase Price -->
            <div class="p-3 border border-purple-200 rounded-lg bg-gradient-to-r from-purple-50 to-violet-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-purple-600">Avg Purchase Price</span>
                    <i class="text-sm text-purple-500 fas fa-calculator opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-purple-700">
                    @php
                        $avgPrice = $allStocks->count() > 0 ? $allStocks->avg('price_per_set') : 0;
                    @endphp
                    ৳{{ number_format($avgPrice, 2) }}
                </div>
                <div class="text-xs text-purple-600 opacity-75">
                    Per set
                </div>
            </div>

            <!-- Total Suppliers -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Suppliers</span>
                    <i class="text-sm text-gray-500 fas fa-users opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-gray-900">
                    @php
                        $totalSuppliers = $allStocks->unique('address')->count();
                    @endphp
                    {{ $totalSuppliers }}
                </div>
                <div class="text-xs text-gray-500 opacity-75">
                    Unique addresses
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

        /* Grid improvements for form */
        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Truncate text in table cells */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

        /* Sortable table headers */
        th button {
            width: 100%;
            transition: color 0.15s ease-in-out;
        }

        th button:hover {
            color: #2563eb;
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.25rem;
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

        /* Currency input styling */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Improved tooltip styling */
        [title] {
            position: relative;
        }

        /* Color theme enhancements */
        .text-red-700 {
            color: #b91c1c;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-purple-600 {
            color: #9333ea;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .border-red-200 {
            border-color: #fecaca;
        }
    </style>

</div>
