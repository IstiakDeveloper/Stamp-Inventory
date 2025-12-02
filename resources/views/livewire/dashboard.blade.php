<div>
    <div class="px-3 mx-auto max-w-7xl">
        <!-- Quick Navigation Section -->
        <div class="flex flex-wrap gap-3 p-3 mb-4 bg-white border border-gray-100 rounded-lg shadow-sm">
            <a href="{{ route('branch_sale') }}"
                class="flex items-center px-3 py-2 text-xs text-gray-700 transition-all duration-200 border border-transparent rounded-md bg-gray-50 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200">
                <i class="w-3 h-3 mr-2 fas fa-shopping-cart"></i>
                <span class="font-medium">Sell (Branch)</span>
            </a>
            <a href="{{ route('office_sale') }}"
                class="flex items-center px-3 py-2 text-xs text-gray-700 transition-all duration-200 border border-transparent rounded-md bg-gray-50 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200">
                <i class="w-3 h-3 mr-2 fas fa-store"></i>
                <span class="font-medium">Sell (HO)</span>
            </a>
            <a href="{{ route('expences') }}"
                class="flex items-center px-3 py-2 text-xs text-gray-700 transition-all duration-200 border border-transparent rounded-md bg-gray-50 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200">
                <i class="w-3 h-3 mr-2 fas fa-wallet"></i>
                <span class="font-medium">Expenses</span>
            </a>
            <a href="{{ route('loan.management') }}"
                class="flex items-center px-3 py-2 text-xs text-gray-700 transition-all duration-200 border border-transparent rounded-md bg-gray-50 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200">
                <i class="w-3 h-3 mr-2 fas fa-hand-holding-usd"></i>
                <span class="font-medium">Loan Management</span>
            </a>
        </div>

        <!-- Filter Section (Hidden by default) -->
        <div class="hidden mb-3">
            <form
                class="grid grid-cols-1 gap-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm sm:grid-cols-2">
                <div>
                    <select wire:model.live="selectedYear" id="year"
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="selectedMonth" id="month"
                        class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($months as $month)
                            <option value="{{ $month }}">
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Metrics Dashboard -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">

            <!-- Bank Balance -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Bank Balance</span>
                    <i class="text-sm text-blue-500 fas fa-university opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    {{ $totalBalance == intval($totalBalance) ? number_format($totalBalance, 0) : number_format($totalBalance, 2) }}
                </div>
            </div>

            <!-- Branch Total Due -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Branch Due</span>
                    <i class="text-sm text-orange-500 fas fa-store opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    {{ $totalOutstandingBalance == intval($totalOutstandingBalance) ? number_format($totalOutstandingBalance, 0) : number_format($totalOutstandingBalance, 2) }}
                </div>
            </div>

            <!-- Total Loans Given -->
            <div
                class="p-3 transition-shadow duration-200 border border-red-100 rounded-lg shadow-sm bg-gradient-to-br from-red-50 to-rose-50 hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Loans Given</span>
                    <i class="text-sm text-red-500 fas fa-hand-holding-usd opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-red-700">
                    ৳{{ number_format($totalLoanOutstanding, 2) }}
                </div>
                <div class="mt-1 text-xs text-red-500">
                    {{ $activeBorrowers }} active borrowers
                </div>
            </div>

            <!-- Stamp Available -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Stock Available</span>
                    <i class="text-sm text-green-500 fas fa-boxes opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    {{ $totalStampAvailable == intval($totalStampAvailable) ? number_format($totalStampAvailable, 0) : number_format($totalStampAvailable, 0) }}
                </div>
            </div>

            <!-- Stock Buy Price -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Stock Value</span>
                    <i class="text-sm text-purple-500 fas fa-credit-card opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    @php
                        $product = $averageStampPricePerSet * $totalStampAvailable;
                    @endphp
                    {{ $product == intval($product) ? number_format($product, 0) : number_format($product, 2) }}
                </div>
            </div>

            <!-- Total Rejects -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Rejects</span>
                    <i class="text-sm text-red-500 fas fa-times-circle opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    @php
                        $value = $rejectTotal;
                        $formattedValue =
                            $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </div>
            </div>

            <!-- Funds -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Funds</span>
                    <i class="text-sm text-yellow-500 fas fa-coins opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    @formatNumber($this->funds)
                </div>
            </div>

            <!-- Cumulative Income -->
            <div
                class="p-3 transition-shadow duration-200 border border-green-100 rounded-lg shadow-sm bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Total Income</span>
                    <i class="text-sm text-green-500 fas fa-arrow-trend-up"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-green-700">
                    @formatNumber($this->cumulativeIncome)
                </div>
            </div>

            <!-- Cumulative Loss -->
            <div
                class="p-3 transition-shadow duration-200 border border-red-100 rounded-lg shadow-sm bg-gradient-to-br from-red-50 to-rose-50 hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Loss</span>
                    <i class="text-sm text-red-500 fas fa-arrow-trend-down"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-red-700">
                    @formatNumber($this->cumulativeLoss)
                </div>
            </div>

            <!-- Cumulative Profit -->
            <div
                class="col-span-2 p-3 transition-shadow duration-200 border border-yellow-200 rounded-lg shadow-sm bg-gradient-to-br from-yellow-50 to-amber-50 hover:shadow-md sm:col-span-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-yellow-700">Net Profit</span>
                    <i class="text-sm text-yellow-600 fas fa-trophy"></i>
                </div>
                <div class="text-xl font-bold leading-tight text-yellow-800">
                    @formatNumber($this->cumulativeProfit)
                </div>
            </div>

            <!-- Per Set Buy Price -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Buy Price/Set</span>
                    <i class="text-sm text-indigo-500 fas fa-tag opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    {{ $averageStampPricePerSet == intval($averageStampPricePerSet) ? number_format($averageStampPricePerSet, 0) : number_format($averageStampPricePerSet, 2) }}
                </div>
            </div>

            <!-- Unit Price -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Unit Price</span>
                    <i class="text-sm fas fa-dollar-sign text-emerald-500 opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    @formatNumber($this->unitPrice)
                </div>
            </div>

            <!-- Total Branches -->
            <div
                class="p-3 transition-shadow duration-200 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500">Branches</span>
                    <i class="text-sm fas fa-building text-cyan-500 opacity-70"></i>
                </div>
                <div class="text-lg font-bold leading-tight text-gray-900">
                    {{ $totalBranch }}
                </div>
            </div>

        </div>

        <!-- Loan Summary Section -->
        <div class="p-3 mt-4 border border-purple-100 rounded-lg bg-gradient-to-r from-purple-50 to-pink-50">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="text-sm text-purple-600 fas fa-hand-holding-usd"></i>
                    <span class="text-sm font-medium text-purple-800">Loan Summary</span>
                </div>
                <a href="{{ route('loan.management') }}"
                    class="px-3 py-1 text-xs text-purple-600 transition-colors border border-purple-200 rounded-md hover:bg-purple-100">
                    Manage Loans
                </a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <!-- Total Loans Given -->
                <div class="text-center">
                    <div class="mb-1 text-xs text-purple-600">Total Given</div>
                    <div class="text-lg font-bold text-purple-800">
                        ৳{{ number_format(App\Models\Loan::sum('amount'), 2) }}
                    </div>
                </div>

                <!-- Total Payments Received -->
                <div class="text-center">
                    <div class="mb-1 text-xs text-purple-600">Received Back</div>
                    <div class="text-lg font-bold text-green-700">
                        ৳{{ number_format(App\Models\LoanPayment::sum('amount'), 2) }}
                    </div>
                </div>

                <!-- Outstanding Balance -->
                <div class="text-center">
                    <div class="mb-1 text-xs text-purple-600">Outstanding</div>
                    <div class="text-lg font-bold text-red-700">
                        ৳{{ number_format($totalLoanOutstanding, 2) }}
                    </div>
                </div>

                <!-- Active Borrowers -->
                <div class="text-center">
                    <div class="mb-1 text-xs text-purple-600">Active Borrowers</div>
                    <div class="text-lg font-bold text-purple-800">
                        {{ $activeBorrowers }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Summary -->
        <div class="p-3 mt-4 border border-blue-100 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="text-sm text-blue-600 fas fa-chart-line"></i>
                    <span class="text-sm font-medium text-blue-800">Dashboard Overview</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-blue-600">
                    <span>{{ $totalBranch }} Branches</span>
                    <span class="font-medium">{{ number_format($totalStampAvailable) }} Sets Available</span>
                    @if ($totalLoanOutstanding > 0)
                        <span class="font-medium text-red-600">৳{{ number_format($totalLoanOutstanding, 0) }} Loan
                            Due</span>
                    @endif
                    <span class="px-2 py-1 font-medium bg-blue-100 rounded-full">Updated Live</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional hover effects and micro-animations */
        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        /* Responsive text scaling */
        @media (max-width: 640px) {
            .text-lg {
                font-size: 1rem;
                line-height: 1.25rem;
            }
        }

        /* Custom scrollbar for mobile */
        @media (max-width: 768px) {
            .overflow-x-auto::-webkit-scrollbar {
                height: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }
        }

        /* Loan card styling */
        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
        }
    </style>
</div>
