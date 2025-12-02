<?php

namespace App\Livewire\Report;

use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\RejectOrFree;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockRegisterReportComponent extends Component
{
    public $totalStockIn;
    public $selectedMonth;
    public $selectedYear;
    public $soFarStockIn;
    public $soFarStockOut;
    public $totalStockInPrice;
    public $averageStockInPrice;
    public $totalStockOut;
    public $totalStockOutPrice;
    public $averageStockOutPrice;
    public $soFarTotalStockIn;
    public $soFarTotalStockInPrice;
    public $soFarAverageStockInPrice;
    public $soFarTotalStockOut;
    public $soFarAverageStockOutPrice;
    public $soFarTotalStockOutPrice;
    public $cumulativeStockIn;
    public $cumulativeStockOut;
    public $availableSets;
    public $cumulativeStockOutPrice;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('m');
        $this->selectedYear = Carbon::now()->format('Y');
    }



    private function getStockInData($beforeSelectedMonth = false)
    {
        $query = Stock::select(DB::raw('date, SUM(sets) as sets, SUM(total_price) as total_purchase_price, AVG(total_price / sets) as purchase_price'));

        if ($beforeSelectedMonth) {
            $query->where(function($q) {
                $q->where(function($inner) {
                    $inner->whereYear('date', '<', $this->selectedYear);
                })->orWhere(function($inner) {
                    $inner->whereYear('date', $this->selectedYear)
                          ->whereMonth('date', '<', $this->selectedMonth);
                });
            });
        } else {
            $query->whereYear('date', $this->selectedYear)
                  ->whereMonth('date', $this->selectedMonth);
        }

        return $query->groupBy('date')->get();
    }

    private function getStockOutData($beforeSelectedMonth = false)
    {
        // Helper function to build the date condition
        $applyDateCondition = function($query) use ($beforeSelectedMonth) {
            if ($beforeSelectedMonth) {
                $query->where(function($q) {
                    $q->where(function($inner) {
                        $inner->whereYear('date', '<', $this->selectedYear);
                    })->orWhere(function($inner) {
                        $inner->whereYear('date', $this->selectedYear)
                              ->whereMonth('date', '<', $this->selectedMonth);
                    });
                });
            } else {
                $query->whereYear('date', $this->selectedYear)
                      ->whereMonth('date', $this->selectedMonth);
            }
            return $query;
        };

        // Build the queries with the new date conditions
        $branchSales = $applyDateCondition(
            BranchSale::select('date', DB::raw('SUM(sets) as sets, SUM(sets * per_set_price) as total_price'))
        )->groupBy('date')->get();

        $headOfficeSales = $applyDateCondition(
            HeadOfficeSale::select('date', DB::raw('SUM(sets) as sets, SUM(sets * per_set_price) as total_price'))
        )->groupBy('date')->get();

        $rejectOrFree = $applyDateCondition(
            RejectOrFree::select('date', DB::raw('SUM(sets) as sets, SUM(sets * purchase_price_per_set) as total_price'))
        )->groupBy('date')->get();

        // Merge and process the results as before
        $allData = collect()->merge($branchSales)
                            ->merge($headOfficeSales)
                            ->merge($rejectOrFree);

        return $allData->groupBy('date')->map(function ($daySales) {
            $totalSets = $daySales->sum('sets');
            $totalPrice = $daySales->sum('total_price');
            $averagePrice = $totalSets > 0 ? $totalPrice / $totalSets : 0;

            return [
                'sets' => $totalSets,
                'price' => $averagePrice,
                'total_price' => $totalPrice,
            ];
        });
    }

    // private function getStockOutData($beforeSelectedMonth = false)
    // {
    //     // Build the query for BranchSale
    //     $branchSalesQuery = BranchSale::select('date', DB::raw('SUM(sets) as sets, SUM(sets * per_set_price) as total_price'))
    //         ->whereYear('date', $this->selectedYear);

    //     // Build the query for HeadOfficeSale
    //     $headOfficeSalesQuery = HeadOfficeSale::select('date', DB::raw('SUM(sets) as sets, SUM(sets * per_set_price) as total_price'))
    //         ->whereYear('date', $this->selectedYear);

    //     // Build the query for RejectOrFree
    //     $rejectOrFreeQuery = RejectOrFree::select('date', DB::raw('SUM(sets) as sets, SUM(sets * purchase_price_per_set) as total_price'))
    //         ->whereYear('date', $this->selectedYear);

    //     // Apply the appropriate month condition
    //     if ($beforeSelectedMonth) {
    //         $branchSalesQuery->whereMonth('date', '<', $this->selectedMonth);
    //         $headOfficeSalesQuery->whereMonth('date', '<', $this->selectedMonth);
    //         $rejectOrFreeQuery->whereMonth('date', '<', $this->selectedMonth);
    //     } else {
    //         $branchSalesQuery->whereMonth('date', $this->selectedMonth);
    //         $headOfficeSalesQuery->whereMonth('date', $this->selectedMonth);
    //         $rejectOrFreeQuery->whereMonth('date', $this->selectedMonth);
    //     }

    //     // Execute the queries and get the results
    //     $branchSales = $branchSalesQuery->groupBy('date')->get();
    //     $headOfficeSales = $headOfficeSalesQuery->groupBy('date')->get();
    //     $rejectOrFree = $rejectOrFreeQuery->groupBy('date')->get();

    //     // Merge all results into a single collection
    //     $allData = collect()->merge($branchSales)
    //                         ->merge($headOfficeSales)
    //                         ->merge($rejectOrFree);

    //     // Group by date and calculate aggregated results
    //     return $allData->groupBy('date')->map(function ($daySales) {
    //         $totalSets = $daySales->sum('sets');
    //         $totalPrice = $daySales->sum('total_price');
    //         $averagePrice = $totalSets > 0 ? $totalPrice / $totalSets : 0;

    //         return [
    //             'sets' => $totalSets,
    //             'price' => $averagePrice,
    //             'total_price' => $totalPrice,
    //         ];
    //     });
    // }


    public function render()
    {
        // Current month data
        $stockInData = $this->getStockInData();
        $stockOutData = $this->getStockOutData();

        // Before the selected month data
        $this->soFarStockIn = $this->getStockInData(true);
        $this->soFarStockOut = $this->getStockOutData(true);

        // Calculate totals for current month
        $this->totalStockIn = $stockInData->sum('sets');
        $this->totalStockInPrice = $stockInData->sum('total_purchase_price');
        $this->averageStockInPrice = $this->totalStockIn > 0 ? $this->totalStockInPrice / $this->totalStockIn : 0;

        $this->totalStockOut = $stockOutData->sum('sets');
        $this->totalStockOutPrice = $stockOutData->sum('total_price');
        $this->averageStockOutPrice = $this->totalStockOut > 0 ? $this->totalStockOutPrice / $this->totalStockOut : 0;

        $stockQuery = Stock::query();
        $totalSetsBuy = $stockQuery->sum('sets');
        $totalSetsBuyPrice = $stockQuery->sum('total_price');

        if ($totalSetsBuy > 0) {
            $averageStampPricePerSet = $totalSetsBuyPrice / $totalSetsBuy;
        } else {
            $averageStampPricePerSet = 0;
        }
        // Calculate totals for before the selected month
        $this->soFarTotalStockIn = $this->soFarStockIn->sum('sets');
        $this->soFarTotalStockInPrice = $this->soFarStockIn->sum('total_purchase_price');
        $this->soFarAverageStockInPrice = $this->soFarTotalStockIn > 0 ? $this->soFarTotalStockInPrice / $this->soFarTotalStockIn : 0;

        $this->soFarTotalStockOut = $this->soFarStockOut->sum('sets');
        $this->soFarTotalStockOutPrice = $this->soFarStockOut->sum('total_price');
        $this->soFarAverageStockOutPrice = $this->soFarTotalStockOut > 0 ? $this->soFarTotalStockOutPrice / $this->soFarTotalStockOut : 0;

        // Calculate cumulative totals
        $this->cumulativeStockIn = $this->soFarTotalStockIn + $this->totalStockIn;
        $this->cumulativeStockOut = $this->soFarTotalStockOut + $this->totalStockOut;

        // Calculate available sets
        $this->availableSets = $this->cumulativeStockIn - $this->cumulativeStockOut;

        // Cumulative stock out price (before + current month)
        $this->cumulativeStockOutPrice = $this->soFarTotalStockOutPrice + $this->totalStockOutPrice;

        return view('livewire.report.stock-register-report-component', [
            'stockInData' => $stockInData,
            'stockOutData' => $stockOutData,
            'completeMonth' => $this->getCompleteMonth(),
            'soFarStockIn' => $this->soFarStockIn,
            'soFarStockOut' => $this->soFarStockOut,
            'totalStockIn' => $this->totalStockIn,
            'totalStockInPrice' => $this->totalStockInPrice,
            'averageStockInPrice' => $this->averageStockInPrice,
            'totalStockOut' => $this->totalStockOut,
            'totalStockOutPrice' => $this->totalStockOutPrice,
            'averageStockOutPrice' => $this->averageStockOutPrice,
            'soFarTotalStockIn' => $this->soFarTotalStockIn,
            'soFarTotalStockInPrice' => $this->soFarTotalStockInPrice,
            'soFarAverageStockInPrice' => $this->soFarAverageStockInPrice,
            'soFarTotalStockOut' => $this->soFarTotalStockOut,
            'soFarTotalStockOutPrice' => $this->soFarTotalStockOutPrice,
            'soFarAverageStockOutPrice' => $this->soFarAverageStockOutPrice,
            'cumulativeStockIn' => $this->cumulativeStockIn,
            'cumulativeStockOut' => $this->cumulativeStockOut,
            'availableSets' => $this->availableSets,
            'cumulativeStockOutPrice' => $this->cumulativeStockOutPrice,
            'averageStampPricePerSet' => $averageStampPricePerSet,
        ]);
    }





    private function getCompleteMonth()
    {
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->daysInMonth;

        return collect(range(1, $daysInMonth))->map(function ($day) {
            return Carbon::create($this->selectedYear, $this->selectedMonth, $day);
        });
    }


    public function downloadPdf()
    {
        $stockInData = $this->getStockInData();
        $stockOutData = $this->getStockOutData();
        $completeMonth = $this->getCompleteMonth();
        $soFarStockIn = $this->getStockInData(true);
        $soFarStockOut = $this->getStockOutData(true);

        $cumulativeStockIn = $soFarStockIn->sum('sets') + $this->totalStockIn;
        $cumulativeStockOut = $soFarStockOut->sum('sets') + $this->totalStockOut;
        $availableSets = $cumulativeStockIn - $cumulativeStockOut;

        $stockQuery = Stock::query();
        $totalSetsBuy = $stockQuery->sum('sets');
        $totalSetsBuyPrice = $stockQuery->sum('total_price');
        $averageStampPricePerSet = $totalSetsBuy > 0 ? $totalSetsBuyPrice / $totalSetsBuy : 0;

        $pdf = Pdf::loadView('pdf.stock-register-report', [
            'stockInData' => $stockInData,
            'stockOutData' => $stockOutData,
            'completeMonth' => $completeMonth,
            'soFarStockIn' => $soFarStockIn,
            'soFarStockOut' => $soFarStockOut,
            'totalStockIn' => $this->totalStockIn,
            'totalStockInPrice' => $this->totalStockInPrice,
            'averageStockInPrice' => $this->averageStockInPrice,
            'totalStockOut' => $this->totalStockOut,
            'totalStockOutPrice' => $this->totalStockOutPrice,
            'averageStockOutPrice' => $this->averageStockOutPrice,
            'selectedYear' => $this->selectedYear,
            'selectedMonth' => $this->selectedMonth,
            'availableSets' => $availableSets,
            'averageStampPricePerSet' => $averageStampPricePerSet,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'stock-register-report-' . $this->selectedMonth . '-' . $this->selectedYear . '.pdf');
    }

}
