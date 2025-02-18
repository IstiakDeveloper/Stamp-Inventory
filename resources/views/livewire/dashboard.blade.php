<div class="max-w-6xl mx-auto px-4">
    <!-- Navigation Section -->
    <div class="flex justify-around mb-4 py-4 border rounded-lg">
        <a href="{{ route('branch_sale') }}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-shopping-cart class="h-6 w-6 mr-2" />
            <span>Sell (Branch)</span>
        </a>
        <a href="{{ route('office_sale') }}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-store class="h-6 w-6 mr-2" />
            <span>Sell (HO)</span>
        </a>
        <a href="{{ route('expences') }}" class="flex items-center p-4 bg-white shadow-md rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out active:bg-blue-700">
            <x-lucide-wallet class="h-6 w-6 mr-2" />
            <span>Expense</span>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="mb-2 hidden">
        <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 p-4 bg-white shadow rounded-lg">
            <div class="flex flex-col">
                <select wire:model.live="selectedYear" id="year" class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <select wire:model.live="selectedMonth" id="month" class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($months as $month)
                        <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <!-- Bank Balance -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Bank Balance</span>
                <h2 class="text-xl font-bold">
                    {{ $totalBalance == intval($totalBalance) ? number_format($totalBalance, 0) : number_format($totalBalance, 2) }}
                </h2>
            </div>
            <x-lucide-currency class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Outstanding Balance -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Branch Total Due</span>
                <h2 class="text-xl font-bold">
                    {{ $totalOutstandingBalance == intval($totalOutstandingBalance) ? number_format($totalOutstandingBalance, 0) : number_format($totalOutstandingBalance, 2) }}
                </h2>
            </div>
            <x-lucide-hand-coins class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Stamp Available -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Stamp Available</span>
                <h2 class="text-xl font-bold">
                    {{ $totalStampAvailable == intval($totalStampAvailable) ? number_format($totalStampAvailable, 0) : number_format($totalStampAvailable, 0) }}
                </h2>
            </div>
            <x-lucide-box class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Average Stamp Price per Set -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Stock Buy Price</span>
                <h2 class="text-xl font-bold">
                    @php
                        $product = $averageStampPricePerSet * $totalStampAvailable;
                    @endphp
                
                    {{-- Format the result --}}
                    {{ $product == intval($product) ? number_format($product, 0) : number_format($product, 2) }}
                </h2>
            </div>
            <x-lucide-credit-card class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Rejects -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Rejects</span>
                <h2 class="text-xl font-bold">
                    @php
                        $value = $rejectTotal;
                        // Format to two decimal places if needed
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </h2>

            </div>
            <x-lucide-ban class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Payment Amount -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Funds</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->funds)
                </h2>
            </div>
            <x-lucide-circle-dollar-sign class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Cumulative Income -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Cumulative Income</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeIncome)
                </h2>
            </div>
            <x-lucide-banknote class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Cumulative Loss -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Cumulative Loss</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeLoss)
                </h2>
            </div>
            <x-lucide-shopping-bag class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Cumulative Profit -->
        <div class="bg-yellow-100 shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Cumulative Profit</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeProfit)
                </h2>
            </div>
            <x-lucide-clipboard-check class="text-blue-500 h-10 w-10" />
        </div>


        <!-- Average Stamp Price per Set -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Per Set Buy Price</span>
                <h2 class="text-xl font-bold">
                    {{ $averageStampPricePerSet == intval($averageStampPricePerSet) ? number_format($averageStampPricePerSet, 0) : number_format($averageStampPricePerSet, 2) }}
                </h2>
            </div>
            <x-lucide-diamond-percent class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Average Stamp Price per Set -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Stamp Unit Price</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->unitPrice)
                </h2>
            </div>
            <x-lucide-dollar-sign class="text-blue-500 h-10 w-10" />
        </div>

        <!-- Total Branches -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between mt-4">
            <div>
                <span class="text-gray-400 text-sm">Total Branches</span>
                <h2 class="text-xl font-bold">{{ $totalBranch }}</h2>
            </div>
            <x-lucide-store class="text-blue-500 h-10 w-10" />
        </div>
    </div>
</div>
