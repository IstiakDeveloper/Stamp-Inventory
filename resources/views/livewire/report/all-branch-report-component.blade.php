<div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
    <div class="flex items-center gap-4 mb-6">
        <div class="mb-4">
            <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date:</label>
            <input type="date" id="fromDate" wire:model.live="fromDate" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="toDate" class="block text-sm font-medium text-gray-700">To Date:</label>
            <input type="date" id="toDate" wire:model.live="toDate" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div id="content-to-pdf" class="overflow-x-auto">

        <div class="mb-4 text-lg font-semibold text-gray-700 flex justify-between">
            <div>
                All Branch Report
            </div>

           <div>
             <!-- Display From Date and To Date -->
            @if ($fromDate && $toDate)
                <span class="ml-4">From: <span class="text-blue-600">{{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</span></span>
                <span class="ml-4">To: <span class="text-blue-600">{{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}</span></span>
            @else
                <span class="ml-4">Date range not selected</span>
            @endif
           </div>
        </div>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-300">SL</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Branch Name</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Previous Due</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Sets</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider border-b border-gray-300">Cash Receive</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider border-b border-gray-300">Total Due</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Initialize totals
                    $totalPreviousOutstanding = 0;
                    $totalSets = 0;
                    $totalPrice = 0;
                    $totalCash = 0;
                    $totalDue = 0;
                @endphp

                @forelse($reportData as $data)
                    @php
                        // Accumulate totals
                        $totalPreviousOutstanding += $data['previous_outstanding'];
                        $totalSets += $data['sets'];
                        $totalPrice += $data['price'];
                        $totalCash += $data['cash'];
                        $totalDue += $data['total_due'];

                        // Determine if any of the fields have a value
                        $hasValue = !empty($data['sets']) || !empty($data['price']) || !empty($data['cash']);
                    @endphp

                    <tr class="{{ $hasValue ? 'bg-gray-100' : ($loop->odd ? 'bg-white' : '') }} border-b border-gray-200">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['branch_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['previous_outstanding'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">
                            @php
                                $value = $data['sets'];
                                // Format to two decimal places if needed
                                $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                            @endphp
                            {{ $formattedValue }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['price'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $data['cash'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $data['total_due'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-900">No data available</td>
                    </tr>
                @endforelse

                <!-- Total Row -->
                <tr class="bg-gray-200 font-bold border-t border-gray-300">
                    <td class="px-6 py-4 text-sm text-right" colspan="2">Total:</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalPreviousOutstanding }}</td> <!-- Total Previous Outstanding -->
                    <td class="px-6 py-4 text-sm text-center">{{ $totalSets }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalPrice }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $totalCash }}</td>
                    <td class="px-6 py-4 text-sm text-right">{{ $totalDue }}</td>
                </tr>

                <tr class="bg-green-200 font-bold border-t border-gray-300">
                    <td class="px-6 py-4 text-sm text-right" colspan="2">Head Office Total:</td>
                    <td class="px-6 py-4 text-sm text-center">--</td> <!-- No total for Previous Due -->
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $hoSetsSum;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $hoTotalPriceSum;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $hoCashSum;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>

                    <td class="px-6 py-4 text-sm text-right">0</td>
                </tr>

                <tr class="bg-blue-200 font-bold border-t border-gray-300">
                    <td class="px-6 py-4 text-sm text-right" colspan="2">Reject or Free Total:</td>
                    <td class="px-6 py-4 text-sm text-center">--</td> <!-- No total for Previous Due -->
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $rfSetsSum;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">--</td>
                    <td class="px-6 py-4 text-sm text-center">--</td>

                    <td class="px-6 py-4 text-sm text-right">--</td>
                </tr>
                @php
                  $grandTotalSets =  $totalSets + $hoSetsSum + $rfSetsSum;
                  $grandTotalPrice = $totalPrice + $hoTotalPriceSum;
                  $grandTotalCash = $totalCash + $hoCashSum;
                @endphp

                <tr class="bg-yellow-300 font-bold border-t border-gray-300">
                    <td class="px-6 py-4 text-sm text-right" colspan="2">Grand Total:</td>
                    <td class="px-6 py-4 text-sm text-center">--</td> <!-- No total for Previous Due -->
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $grandTotalSets;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $grandTotalPrice;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        @php
                            $value = $grandTotalCash;
                            // Format to two decimal places if needed
                            $formattedValue = $value - floor($value) > 0 ? number_format($value, 2) : number_format($value, 0);
                        @endphp
                        {{ $formattedValue }}
                    </td>

                    <td class="px-6 py-4 text-sm text-right">--</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Button to download PDF -->
    <div class="mt-4 text-right">
        <button id="download-pdf" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Download PDF</button>
    </div>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
            var element = document.getElementById('content-to-pdf');
            html2pdf()
                .from(element)
                .set({
                    margin: .3,
                    filename: 'stock-register.pdf',
                    html2canvas: { scale: 4 },
                    jsPDF: { orientation: 'portrait', unit: 'in', format: 'A4', compressPDF: true }
                })
                .save();
        });
    </script>
</div>
