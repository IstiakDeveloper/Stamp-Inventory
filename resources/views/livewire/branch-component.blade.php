<div class="mx-auto py-6">
    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">Branch Management</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <form wire:submit.prevent="save" class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <div class="mb-4">
                    <label for="branch_code" class="block text-sm font-medium text-gray-700">Branch Code</label>
                    <input type="text" id="branch_code" wire:model.defer="branch_code" class="mt-1 p-3 border border-gray-300 rounded-lg shadow-sm w-full focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('branch_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="branch_name" class="block text-sm font-medium text-gray-700">Branch Name</label>
                    <input type="text" id="branch_name" wire:model.defer="branch_name" class="mt-1 p-3 border border-gray-300 rounded-lg shadow-sm w-full focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('branch_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label for="outstanding_balance" class="block text-sm font-medium text-gray-700">Outstanding Balance</label>
                    <input type="number" step="0.01" id="outstanding_balance" wire:model.defer="outstanding_balance" class="mt-1 p-3 border border-gray-300 rounded-lg shadow-sm w-full focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('outstanding_balance') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col-span-full">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ $isEditMode ? 'Update Branch' : 'Save Branch' }}
                </button>
            </div>
        </form>

        <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Branches List</h2>
            <div class="flex justify-between mb-4">
                <a href="{{ route('branch_total_report') }}" class="text-blue-600 hover:text-blue-800 font-medium">View Report</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Branch Code</th>
                            <th class="py-3 px-6 text-left">Branch Name</th>
                            <th class="py-3 px-6 text-left">Outstanding Balance</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-light text-gray-700">
                        @foreach($branches as $branch)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $branch->branch_code }}</td>
                            <td class="py-3 px-6">{{ $branch->branch_name }}</td>
                            <td class="py-3 px-6">@formatNumber($branch->outstanding_balance)</td>
                            <td class="py-3 px-6 text-right">
                                <button wire:click="edit({{ $branch->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">Edit</button>
                                <button wire:click="delete({{ $branch->id }})" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 ml-2">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
