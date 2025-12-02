<div>
    <div class="max-w-md p-3 mx-auto space-y-4">

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="px-3 py-2 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50">
                <i class="mr-1 fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Set Unit Price Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="mr-2 text-xs text-blue-500 fas fa-tag"></i>
                    Set Unit Price
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="savePrice" class="space-y-4">

                    <!-- Price Input -->
                    <div>
                        <label for="price" class="block mb-1 text-xs font-medium text-gray-700">Unit Price</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-xs font-medium text-gray-400">৳</span>
                            </div>
                            <input type="number" step="0.01" id="price" wire:model.defer="price"
                                placeholder="Enter unit price"
                                class="w-full py-2 pl-8 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('price')
                            <span class="flex items-center mt-1 text-xs text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="mr-1 fas fa-save"></i>
                            Update Unit Price
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Price Display -->
        <div class="p-4 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="text-sm text-blue-600 fas fa-price-tag"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-blue-700">Current Unit Price</h3>
                    <p class="text-lg font-bold text-blue-800">
                        @if (isset($price) && $price)
                            ৳{{ number_format($price, 2) }}
                        @else
                            Not Set
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Price Info -->
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="text-center">
                <i class="mb-2 text-lg text-gray-400 fas fa-info-circle"></i>
                <h3 class="mb-2 text-sm font-medium text-gray-700">Price Information</h3>
                <p class="text-xs leading-relaxed text-gray-500">
                    This unit price will be used for all branch calculations and sales.
                    Make sure to set an accurate price for proper inventory management.
                </p>
            </div>
        </div>

        <!-- Recent Price History (Optional) -->
        @if (isset($priceHistory) && count($priceHistory) > 0)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-history"></i>
                        Recent Price Changes
                    </h2>
                </div>

                <!-- Price History List -->
                <div class="p-4 space-y-2">
                    @foreach ($priceHistory as $history)
                        <div class="flex items-center justify-between px-3 py-2 rounded-md bg-gray-50">
                            <div class="flex items-center gap-2">
                                <i class="text-xs text-gray-400 fas fa-clock"></i>
                                <span class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($history->date)->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="text-xs font-medium text-gray-900">
                                ৳{{ number_format($history->price, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Custom enhancements */
        .transition-colors {
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
        }

        /* Focus states */
        input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-0.5px);
        }

        /* Price input styling */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* BDT symbol styling */
        .font-medium {
            font-weight: 500;
        }
    </style>
</div>
