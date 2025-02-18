<div class="mx-auto p-6 rounded-lg shadow-md bg-white">
    <div class="mt-4" id="content-to-pdf">
        <h2 class="text-5xl text-center font-bold text-gray-900 mb-2">Mousumi Publication</h2>
        <h2 class="text-3xl text-center font-bold text-gray-900 mb-8">Balance Sheet</h2>
        
        <div class="flex items-center gap-4 mb-4">
            <div class="mb-4">
                <select wire:model.live="selectedYear" id="year" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        
            <div class="mb-4">
                <select wire:model.live="selectedMonth" id="month" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($availableMonths as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex border-l border-r border-gray-300">
            <!-- Left Column -->
            <div class="w-1/2 border-r border-gray-300">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <caption class="px-6 py-3 text-center text-lg font-bold border border-gray-300 bg-gray-50">Funds & Liabilities</caption>
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300">Metric</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider border border-gray-300">Amount</th>
                        </tr> 
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Funds</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">@formatNumber($totalCashIn)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Net Profit</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">@formatNumber($netProfit)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 h-[3.3rem]"></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">@formatNumber($totalCashIn + $netProfit)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- <div class="inline-block w-0.5 self-stretch bg-gray-500"></div> --}}

            <!-- Right Column -->
            <div class="w-1/2">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <caption class="px-6 py-3 text-center text-lg font-bold border border-gray-300 bg-gray-50">Property & Assets</caption>
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300">Metric</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider border border-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Bank Balance</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">@formatNumber($totalBankOrHandBalance)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Branches Due</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">@formatNumber($outstandingTotal)</td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300">Stock Stamp Buy Price</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 text-right">@formatNumber($stockStampBuyPrice)</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-300 font-bold text-right">@formatNumber($outstandingTotal + $totalBankOrHandBalance + $stockStampBuyPrice)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

        <!-- Download Buttons -->
        <div class="mt-4">
            <button id="download-pdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download PDF</button>
        </div>
    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
            var element = document.getElementById('content-to-pdf');
            html2pdf()
                .from(element)
                .set({
                    margin: .3,
                    filename: 'balance_sheet.pdf',
                    html2canvas: { scale: 2 },
                    jsPDF: { orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true }
                })
                .save();
        });
    </script>
</div>
