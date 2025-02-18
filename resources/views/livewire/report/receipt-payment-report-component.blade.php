<div class="bg-white shadow-md rounded-md p-8">

    
    <div id="content-to-pdf">
        <h2 class="text-5xl text-center font-bold text-gray-900 mb-2">Mousumi Publication</h2>
        <h2 class="text-3xl text-center font-bold text-gray-900 mb-8">Receipts and Payments Statement</h2>
        {{-- <h3 class="text-lg mb-2 font-medium">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h3> --}}
        <div class="flex space-x-6 mb-6">
            <div>
                <select wire:model.live="year" id="year" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    @for($i = \Carbon\Carbon::now()->year; $i >= 2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
    
            <!-- Month Selector -->
            <div>
                <select wire:model.live="month" id="month" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex space-x-2">
            <!-- Receipts Table -->

            <div class="flex-1 bg-white shadow-md rounded-md overflow-hidden">
                <h3 class="text-lg font-semibold bg-gray-200 px-4 py-2 text-center">Receipts</h3>
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border border-gray-300">Description</th>
                            <th class="px-4 py-2 text-right text-sm font-medium text-gray-700 border border-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Opening Cash at Bank</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['opening_balance'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Stamp Sale Collection</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['stamp_sale_collection'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Fund Receive</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['fund_receive'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 h-[2.4rem]"></td>
                            <td class="border px-4 py-2 text-right"></td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-bold">Total</td>
                            <td class="border px-4 py-2 text-right font-bold">@formatNumber($data['total_receipt'])</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payments Table -->
            <div class="flex-1 bg-white shadow-md rounded-md overflow-hidden">
                <h3 class="text-lg font-semibold bg-gray-200 px-4 py-2 text-center">Payments</h3>
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-center text-sm font-bold text-gray-700  border border-gray-300">Particular</th>
                            <th class="px-4 py-2 text-center text-sm font-bold text-gray-700  border border-gray-300">Current Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Purchase of Stamps</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['purchase_of_stamps'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Fund Refund</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['fund_refund'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Other Expenses</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['other_expenses'])</td>
                        </tr>
                        <tr class="">
                            <td class="border px-4 py-2">Closing Cash at Bank</td>
                            <td class="border px-4 py-2 text-right">@formatNumber($data['closing_balance'])</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-bold">Total</td>
                            <td class="border px-4 py-2 text-right font-bold">@formatNumber($data['total_payment'])</td>
                        </tr>

                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <button id="download-pdf" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Download PDF
    </button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
            var element = document.getElementById('content-to-pdf');
            html2pdf()
                .from(element)
                .set({
                    margin: .3,
                    filename: 'receipt-payment.pdf',
                    html2canvas: { scale: 4 },
                    jsPDF: { orientation: 'portrait', unit: 'in', format: 'A4', compressPDF: true }
                })
                .save();
        });
    </script>
</div>
