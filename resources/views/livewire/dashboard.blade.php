<div class="max-w-6xl px-4 mx-auto">
    <!-- Navigation Section -->
    <div class="flex justify-around py-4 mb-4 border rounded-lg">
        <a href="{{ route('branch_sale') }}" class="flex items-center p-4 text-gray-700 transition duration-300 ease-in-out bg-white rounded-lg shadow-md hover:bg-blue-500 hover:text-white active:bg-blue-700">
            <x-lucide-shopping-cart class="w-6 h-6 mr-2" />
            <span>Sell (Branch)</span>
        </a>
        <a href="{{ route('office_sale') }}" class="flex items-center p-4 text-gray-700 transition duration-300 ease-in-out bg-white rounded-lg shadow-md hover:bg-blue-500 hover:text-white active:bg-blue-700">
            <x-lucide-store class="w-6 h-6 mr-2" />
            <span>Sell (HO)</span>
        </a>
        <a href="{{ route('expences') }}" class="flex items-center p-4 text-gray-700 transition duration-300 ease-in-out bg-white rounded-lg shadow-md hover:bg-blue-500 hover:text-white active:bg-blue-700">
            <x-lucide-wallet class="w-6 h-6 mr-2" />
            <span>Expense</span>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="hidden mb-2">
        <form class="grid grid-cols-1 gap-6 p-4 bg-white rounded-lg shadow sm:grid-cols-2 lg:grid-cols-2">
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
    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Bank Balance -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Bank Balance</span>
                <h2 class="text-xl font-bold">

                    {{ $totalBalance == intval($totalBalance) ? number_format($totalBalance, 0) : number_format($totalBalance, 2) }}
                </h2>
            </div>
            <x-lucide-currency class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Total Outstanding Balance -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Branch Total Due</span>
                <h2 class="text-xl font-bold">
                    {{ $totalOutstandingBalance == intval($totalOutstandingBalance) ? number_format($totalOutstandingBalance, 0) : number_format($totalOutstandingBalance, 2) }}
                </h2>
            </div>
            <x-lucide-hand-coins class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Total Stamp Available -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Stamp Available</span>
                <h2 class="text-xl font-bold">
                    {{ $totalStampAvailable == intval($totalStampAvailable) ? number_format($totalStampAvailable, 0) : number_format($totalStampAvailable, 0) }}
                </h2>
            </div>
            <x-lucide-box class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Average Stamp Price per Set -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Stock Buy Price</span>
                <h2 class="text-xl font-bold">
                    @php
                        $product = $averageStampPricePerSet * $totalStampAvailable;
                    @endphp

                    {{-- Format the result --}}
                    {{ $product == intval($product) ? number_format($product, 0) : number_format($product, 2) }}
                </h2>
            </div>
            <x-lucide-credit-card class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Total Rejects -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Total Rejects</span>
                <h2 class="text-xl font-bold">
                    @php
                        $value = $rejectTotal;
                        // Format to two decimal places if needed
                        $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                    @endphp
                    {{ $formattedValue }}
                </h2>

            </div>
            <x-lucide-ban class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Total Payment Amount -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Funds</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->funds)
                </h2>
            </div>
            <x-lucide-circle-dollar-sign class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Cumulative Income -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Cumulative Income</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeIncome)
                </h2>
            </div>
            <x-lucide-banknote class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Cumulative Loss -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Cumulative Loss</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeLoss)
                </h2>
            </div>
            <x-lucide-shopping-bag class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Cumulative Profit -->
        <div class="flex items-center justify-between p-6 mt-4 bg-yellow-100 rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Cumulative Profit</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->cumulativeProfit)
                </h2>
            </div>
            <x-lucide-clipboard-check class="w-10 h-10 text-blue-500" />
        </div>


        <!-- Average Stamp Price per Set -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Per Set Buy Price</span>
                <h2 class="text-xl font-bold">
                    {{ $averageStampPricePerSet == intval($averageStampPricePerSet) ? number_format($averageStampPricePerSet, 0) : number_format($averageStampPricePerSet, 2) }}
                </h2>
            </div>
            <x-lucide-diamond-percent class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Average Stamp Price per Set -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Stamp Unit Price</span>
                <h2 class="text-xl font-bold">
                    @formatNumber($this->unitPrice)
                </h2>
            </div>
            <x-lucide-dollar-sign class="w-10 h-10 text-blue-500" />
        </div>

        <!-- Total Branches -->
        <div class="flex items-center justify-between p-6 mt-4 bg-white rounded-lg shadow-md">
            <div>
                <span class="text-sm text-gray-400">Total Branches</span>
                <h2 class="text-xl font-bold">{{ $totalBranch }}</h2>
            </div>
            <x-lucide-store class="w-10 h-10 text-blue-500" />
        </div>
    </div>
</div>
