<div>
    <div class="max-w-5xl p-3 mx-auto space-y-4">

        <!-- Add/Edit Branch Form -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-900">
                    <i class="fas fa-{{ $isEditMode ? 'edit' : 'plus' }} mr-2 text-blue-500 text-xs"></i>
                    {{ $isEditMode ? 'Edit Branch' : 'Add New Branch' }}
                </h2>
            </div>

            <!-- Form -->
            <div class="p-4">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                        <!-- Branch Code -->
                        <div>
                            <label for="branch_code" class="block mb-1 text-xs font-medium text-gray-700">Branch
                                Code</label>
                            <input type="text" id="branch_code" wire:model.defer="branch_code"
                                placeholder="e.g., BR001"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('branch_code')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Branch Name -->
                        <div>
                            <label for="branch_name" class="block mb-1 text-xs font-medium text-gray-700">Branch
                                Name</label>
                            <input type="text" id="branch_name" wire:model.defer="branch_name"
                                placeholder="Enter branch name"
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            @error('branch_name')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Outstanding Balance -->
                        <div>
                            <label for="outstanding_balance"
                                class="block mb-1 text-xs font-medium text-gray-700">Outstanding Balance</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-xs text-gray-400">৳</span>
                                </div>
                                <input type="number" step="0.01" id="outstanding_balance"
                                    wire:model.defer="outstanding_balance" placeholder="0.00"
                                    class="w-full py-2 pl-6 pr-3 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @error('outstanding_balance')
                                <span class="flex items-center mt-1 text-xs text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="fas fa-{{ $isEditMode ? 'save' : 'plus' }} mr-1"></i>
                            {{ $isEditMode ? 'Update Branch' : 'Add Branch' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Branches List -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 rounded-t-lg bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="mr-2 text-xs text-gray-600 fas fa-list"></i>
                        Branches List
                    </h2>
                    <a href="{{ route('branch_total_report') }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
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
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Branch Code</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-700">Branch Name</th>
                            <th class="px-4 py-3 font-medium text-right text-gray-700">Outstanding Balance</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($branches as $branch)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $branch->branch_code }}
                                </td>
                                <td class="px-4 py-3 text-gray-900">
                                    {{ $branch->branch_name }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-medium {{ $branch->outstanding_balance > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                    ৳@formatNumber($branch->outstanding_balance)
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $branch->id }})"
                                            class="p-1 text-yellow-600 transition-colors rounded hover:bg-yellow-100">
                                            <i class="text-xs fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $branch->id }})"
                                            class="p-1 text-red-600 transition-colors rounded hover:bg-red-100"
                                            onclick="return confirm('Are you sure you want to delete this branch?')">
                                            <i class="text-xs fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if ($branches->isEmpty())
                <div class="p-8 text-center">
                    <i class="mb-3 text-3xl text-gray-400 fas fa-building"></i>
                    <p class="text-sm text-gray-600">No branches found</p>
                    <p class="mt-1 text-xs text-gray-500">Add your first branch using the form above</p>
                </div>
            @endif
        </div>

        <!-- Branch Summary -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Total Branches -->
            <div class="p-3 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-blue-600">Total Branches</span>
                    <i class="text-sm text-blue-500 fas fa-building opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-blue-700">
                    {{ $branches->count() }}
                </div>
            </div>

            <!-- Total Outstanding -->
            <div class="p-3 border border-red-200 rounded-lg bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-red-600">Total Outstanding</span>
                    <i class="text-sm text-red-500 fas fa-exclamation-triangle opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-red-700">
                    @php
                        $totalOutstanding = $branches->sum('outstanding_balance');
                    @endphp
                    ৳@formatNumber($totalOutstanding)
                </div>
            </div>

            <!-- Active Branches -->
            <div class="p-3 border border-green-200 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Active Branches</span>
                    <i class="text-sm text-green-500 fas fa-check-circle opacity-70"></i>
                </div>
                <div class="text-sm font-bold text-green-700">
                    @php
                        $activeBranches = $branches->where('outstanding_balance', '<=', 0)->count();
                    @endphp
                    {{ $activeBranches }}
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

        /* Summary cards hover */
        .rounded-lg:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</div>
