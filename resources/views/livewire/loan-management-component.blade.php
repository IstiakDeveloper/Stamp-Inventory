<div x-data="{ activeTab: 'borrower' }">
    <div class="p-3 mx-auto space-y-4 max-w-7xl">

        <!-- Header Section -->
        <div class="p-4 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="text-sm text-blue-600 fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-blue-700">Loan Management System</h2>
                        <p class="text-xs text-blue-600">Manage loans and payments</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-1 text-xs text-blue-600">Total Outstanding</div>
                    <div class="text-lg font-bold text-red-700">
                        ৳{{ number_format($totalOutstanding, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Total Loans Given -->
            <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Loans Given</span>
                    <i class="text-sm text-red-500 fas fa-arrow-down opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-red-700">
                    ৳{{ number_format($totalLoanAmount, 2) }}
                </div>
            </div>

            <!-- Total Payments Received -->
            <div class="p-3 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Total Payments Received</span>
                    <i class="text-sm text-green-500 fas fa-arrow-up opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-green-700">
                    ৳{{ number_format($totalPaidAmount, 2) }}
                </div>
            </div>

            <!-- Outstanding Balance -->
            <div class="p-3 border border-orange-200 rounded-lg bg-gradient-to-r from-orange-50 to-amber-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-orange-600">Outstanding Balance</span>
                    <i class="text-sm text-orange-500 fas fa-exclamation-triangle opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-orange-700">
                    ৳{{ number_format($totalOutstanding, 2) }}
                </div>
            </div>
        </div>

        <!-- Action Tabs -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="border-b border-gray-100">
                <nav class="flex px-4 space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'borrower'"
                        :class="activeTab === 'borrower' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-1 py-2 text-xs font-medium border-b-2 whitespace-nowrap">
                        <i class="mr-1 fas fa-user-plus"></i>
                        Add Borrower
                    </button>
                    <button @click="activeTab = 'loan'"
                        :class="activeTab === 'loan' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-1 py-2 text-xs font-medium border-b-2 whitespace-nowrap">
                        <i class="mr-1 fas fa-hand-holding-usd"></i>
                        Give Loan
                    </button>
                    <button @click="activeTab = 'payment'"
                        :class="activeTab === 'payment' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-1 py-2 text-xs font-medium border-b-2 whitespace-nowrap">
                        <i class="mr-1 fas fa-money-bill-wave"></i>
                        Receive Payment
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-4">
                <!-- Add Borrower Tab -->
                <div x-show="activeTab === 'borrower'" x-transition>
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        {{ $editingBorrowerId ? 'Edit Borrower' : 'Add New Borrower' }}
                    </h3>
                    <form wire:submit.prevent="{{ $editingBorrowerId ? 'updateBorrower' : 'addBorrower' }}">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="borrower_name" class="block mb-1 text-xs font-medium text-gray-700">Name
                                    *</label>
                                <input type="text" wire:model.defer="borrower_name" id="borrower_name"
                                    placeholder="Enter borrower name"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                @error('borrower_name')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="borrower_phone"
                                    class="block mb-1 text-xs font-medium text-gray-700">Phone</label>
                                <input type="text" wire:model.defer="borrower_phone" id="borrower_phone"
                                    placeholder="Enter phone number"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                @error('borrower_phone')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="borrower_address"
                                    class="block mb-1 text-xs font-medium text-gray-700">Address</label>
                                <textarea wire:model.defer="borrower_address" id="borrower_address" rows="2" placeholder="Enter address"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('borrower_address')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="borrower_note"
                                    class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                                <textarea wire:model.defer="borrower_note" id="borrower_note" rows="2" placeholder="Enter note (optional)"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('borrower_note')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            @if ($editingBorrowerId)
                                <button type="button" wire:click="resetBorrowerForm"
                                    class="px-4 py-2 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-md hover:bg-gray-200">
                                    Cancel
                                </button>
                            @endif
                            <button type="submit"
                                class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">
                                <i class="fas fa-{{ $editingBorrowerId ? 'save' : 'plus' }} mr-1"></i>
                                {{ $editingBorrowerId ? 'Update Borrower' : 'Add Borrower' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Give Loan Tab -->
                <div x-show="activeTab === 'loan'" x-transition>
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Give Loan</h3>
                    <form wire:submit.prevent="giveLoan">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="selected_borrower_id"
                                    class="block mb-1 text-xs font-medium text-gray-700">Select Borrower *</label>
                                <select wire:model.defer="selected_borrower_id" id="selected_borrower_id"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a borrower</option>
                                    @foreach ($allBorrowers as $borrower)
                                        <option value="{{ $borrower->id }}">
                                            {{ $borrower->name }}
                                            @if ($borrower->remaining_balance > 0)
                                                (Due: ৳{{ number_format($borrower->remaining_balance, 2) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('selected_borrower_id')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="loan_date" class="block mb-1 text-xs font-medium text-gray-700">Loan Date
                                    *</label>
                                <input type="date" wire:model.defer="loan_date" id="loan_date"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                @error('loan_date')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="loan_amount" class="block mb-1 text-xs font-medium text-gray-700">Loan
                                    Amount *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-xs text-gray-400">৳</span>
                                    </div>
                                    <input type="number" step="0.01" wire:model.defer="loan_amount"
                                        id="loan_amount" placeholder="Enter amount"
                                        class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('loan_amount')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label for="loan_purpose"
                                    class="block mb-1 text-xs font-medium text-gray-700">Purpose</label>
                                <input type="text" wire:model.defer="loan_purpose" id="loan_purpose"
                                    placeholder="What is this loan for?"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                @error('loan_purpose')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label for="loan_note"
                                    class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                                <textarea wire:model.defer="loan_note" id="loan_note" rows="2" placeholder="Enter note (optional)"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('loan_note')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 text-xs font-medium text-white transition-colors bg-red-600 rounded-md hover:bg-red-700">
                                <i class="mr-1 fas fa-hand-holding-usd"></i>
                                Give Loan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Receive Payment Tab -->
                <div x-show="activeTab === 'payment'" x-transition>
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Receive Payment</h3>
                    <form wire:submit.prevent="receivePayment">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="payment_borrower_id"
                                    class="block mb-1 text-xs font-medium text-gray-700">Select Borrower *</label>
                                <select wire:model.defer="payment_borrower_id" id="payment_borrower_id"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a borrower</option>
                                    @foreach ($allBorrowers->where('remaining_balance', '>', 0) as $borrower)
                                        <option value="{{ $borrower->id }}">
                                            {{ $borrower->name }} (Due:
                                            ৳{{ number_format($borrower->remaining_balance, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_borrower_id')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="payment_date" class="block mb-1 text-xs font-medium text-gray-700">Payment
                                    Date *</label>
                                <input type="date" wire:model.defer="payment_date" id="payment_date"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                @error('payment_date')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="payment_amount"
                                    class="block mb-1 text-xs font-medium text-gray-700">Payment Amount *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-xs text-gray-400">৳</span>
                                    </div>
                                    <input type="number" step="0.01" wire:model.defer="payment_amount"
                                        id="payment_amount" placeholder="Enter amount"
                                        class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('payment_amount')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label for="payment_note"
                                    class="block mb-1 text-xs font-medium text-gray-700">Note</label>
                                <textarea wire:model.defer="payment_note" id="payment_note" rows="2" placeholder="Enter note (optional)"
                                    class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('payment_note')
                                    <span class="flex items-center mt-1 text-xs text-red-600">
                                        <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 text-xs font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700">
                                <i class="mr-1 fas fa-money-bill-wave"></i>
                                Receive Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Borrowers List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-users"></i>
                        Borrowers List
                    </h2>

                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <div class="flex items-center space-x-2">
                            <label for="searchName" class="text-xs text-gray-600">Search:</label>
                            <input type="text" wire:model.live.debounce.500ms="searchName" id="searchName"
                                placeholder="Search by name..."
                                class="px-2 py-1 text-xs border border-gray-200 rounded focus:ring-1 focus:ring-blue-500">
                        </div>

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

                        <!-- Clear Search -->
                        @if ($searchName)
                            <button wire:click="clearSearch"
                                class="px-3 py-1 text-xs text-red-600 transition-colors border border-red-200 rounded hover:bg-red-50">
                                <i class="mr-1 fas fa-times"></i>
                                Clear
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Name</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Phone</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Total Loan</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Total Paid</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Outstanding</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Status</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($borrowers as $borrower)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $borrower->name }}</div>
                                    @if ($borrower->address)
                                        <div class="text-xs text-gray-500 truncate max-w-32"
                                            title="{{ $borrower->address }}">
                                            {{ $borrower->address }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $borrower->phone ?: '-' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-right text-red-700">
                                    ৳{{ number_format($borrower->total_loan_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-green-600">
                                    ৳{{ number_format($borrower->total_paid_amount, 2) }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-right {{ $borrower->remaining_balance > 0 ? 'text-red-600' : 'text-gray-500' }}">
                                    ৳{{ number_format($borrower->remaining_balance, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($borrower->remaining_balance <= 0)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                            Clear
                                        </span>
                                    @elseif($borrower->total_paid_amount > 0)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">
                                            Partial
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editBorrower({{ $borrower->id }})"
                                            @click="activeTab = 'borrower'"
                                            class="p-1 text-blue-600 transition-colors rounded hover:bg-blue-100"
                                            title="Edit Borrower">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteBorrower({{ $borrower->id }})"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this borrower?')"
                                            title="Delete Borrower">
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
            @if ($borrowers->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-between space-y-3 sm:flex-row sm:space-y-0">
                        <!-- Results Info -->
                        <div class="text-xs text-gray-600">
                            Showing {{ $borrowers->firstItem() }} to {{ $borrowers->lastItem() }}
                            of {{ $borrowers->total() }} results
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-1">
                            {{ $borrowers->links() }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if ($borrowers->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-users"></i>
                    <p class="text-sm text-gray-600">
                        @if ($searchName)
                            No borrowers found for "{{ $searchName }}"
                        @else
                            No borrowers found
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        @if ($searchName)
                            Try adjusting your search or clear the filter
                        @else
                            Add your first borrower using the form above
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <!-- Recent Loans -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-red-600 fas fa-hand-holding-usd"></i>
                        Recent Loans Given
                    </h3>
                </div>
                <div class="p-4">
                    @if ($recentLoans->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentLoans as $loan)
                                <div class="flex items-center justify-between p-2 border border-gray-100 rounded">
                                    <div>
                                        <div class="text-xs font-medium text-gray-900">{{ $loan->borrower->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $loan->date->format('M d, Y') }}</div>
                                        @if ($loan->purpose)
                                            <div class="text-xs text-gray-400">{{ Str::limit($loan->purpose, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-xs font-bold text-red-600">
                                        ৳{{ number_format($loan->amount, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-4 text-xs text-center text-gray-500">
                            No recent loans
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-green-600 fas fa-money-bill-wave"></i>
                        Recent Payments Received
                    </h3>
                </div>
                <div class="p-4">
                    @if ($recentPayments->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentPayments as $payment)
                                <div class="flex items-center justify-between p-2 border border-gray-100 rounded">
                                    <div>
                                        <div class="text-xs font-medium text-gray-900">{{ $payment->borrower->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $payment->date->format('M d, Y') }}
                                        </div>
                                        @if ($payment->note)
                                            <div class="text-xs text-gray-400">{{ Str::limit($payment->note, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-xs font-bold text-green-600">
                                        ৳{{ number_format($payment->amount, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-4 text-xs text-center text-gray-500">
                            No recent payments
                        </div>
                    @endif
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

        /* Status badges */
        .rounded-full {
            border-radius: 9999px;
        }

        /* Grid responsive */
        @media (min-width: 1024px) {
            .lg\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Color enhancements */
        .text-red-700 {
            color: #b91c1c;
        }

        .text-green-600 {
            color: #059669;
        }

        .text-orange-700 {
            color: #c2410c;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .bg-green-100 {
            background-color: #dcfce7;
        }

        .bg-orange-100 {
            background-color: #fed7aa;
        }

        /* Alpine.js transitions */
        [x-cloak] {
            display: none !important;
        }
    </style>

</div>
