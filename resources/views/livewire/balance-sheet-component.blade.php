<div class="mx-auto p-6 rounded-lg shadow-md bg-white">

    <div class="mt-4" id="content-to-pdf">
        <h2 class="text-5xl text-center font-bold text-gray-900 mb-2">Mousumi Publication</h2>
        <h2 class="text-3xl text-center font-bold text-gray-900 mb-8">Income & Expenditure Sheet</h2>

        <div class="flex gap-4 items-center">


            <div class="mb-6">
                <select id="year" wire:model.live="year" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-6">
                <select id="month" wire:model.live="month" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $i == now()->month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="flex border-l border-r border-gray-300">
            <!-- Left Column -->
                 <!-- Left Column -->
                 <div class="w-1/2 border-r border-gray-300">
                    <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                        <caption class="px-6 py-3 text-center text-lg font-bold border border-gray-300 bg-gray-50">Expenditure</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-gray-300">Title</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-r border-gray-300">Month</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Year</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Reject or Free</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($rejectOrFreeSumMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($rejectOrFreeSumYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Expenses</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($expenseSumMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($expenseSumYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>

                            <tr class="bg-white font-bold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]">

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]">

                                </td>
                            </tr>
                            <tr class="bg-white font-bold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Total </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($totalLossMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($totalLossYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <!-- Right Column -->
                <div class="w-1/2">
                    <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                        <caption class="px-6 py-3 text-center text-lg font-bold border border-gray-300 bg-gray-50">Income</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-gray-300">Title</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-r border-gray-300">Month</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-r border-gray-300">Year</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Starting Net Profit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($sofarNetProfitSumMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($sofarNetProfitSumYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Branch Profit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($branchSalePriceSumMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($branchSalePriceSumYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Head Office Profit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($headOfficeSalePriceSumMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($headOfficeSalePriceSumYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>

                            <tr class="bg-white font-bold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($totalRevenueMonth, 2, '.', ''), '0'), '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                    {{ rtrim(rtrim(number_format($totalRevenueYear, 2, '.', ''), '0'), '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Net Profit Table -->
            <div class="mt-4">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-gray-300">Title</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-r border-gray-300">For the month</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">For the year</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="bg-white font-bold">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Net Profit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                {{ rtrim(rtrim(number_format($netProfitMonth, 2, '.', ''), '0'), '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">
                                {{ rtrim(rtrim(number_format($netProfitYear, 2, '.', ''), '0'), '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

    </div>
    <div class="mt-4">
        <button wire:click="exportPDF" class="bg-blue-500 text-white px-4 py-2 rounded">Download PDF</button>
    </div>
</div>

<x-pdf-viewer-script />
