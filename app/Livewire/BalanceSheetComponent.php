<?php

namespace App\Livewire;

use App\Models\RejectOrFree;
use App\Models\Expense;
use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\Stock;
use App\Models\TotalStock;
use App\Models\SofarNetProfit; // Add this line
use Carbon\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class BalanceSheetComponent extends Component
{
    public $month;
    public $year;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->month = now()->format('m');
        $this->year = now()->year;
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();
        $this->calculateValues();
    }

    public function updatedMonth() {
        $this->setDateRange();
    }

    public function updatedYear() {
        $this->setDateRange();
    }

    public function updatedStartDate() {
        $this->setDateRange();
    }

    public function updatedEndDate() {
        $this->setDateRange();
    }

    private function setDateRange() {
        if ($this->startDate && $this->endDate) {
            $this->startDate = Carbon::parse($this->startDate)->startOfDay();
            $this->endDate = Carbon::parse($this->endDate)->endOfDay();
        } elseif ($this->year === 'all' && $this->month === 'all') {
            $this->startDate = Carbon::parse('0000-01-01');
            $this->endDate = Carbon::parse('9999-12-31');
        } elseif ($this->year === 'all') {
            $this->startDate = Carbon::create(null, $this->month, 1)->startOfMonth();
            $this->endDate = Carbon::create(null, $this->month, 1)->endOfMonth();
        } elseif ($this->month === 'all') {
            $this->startDate = Carbon::create($this->year, 1, 1)->startOfYear();
            $this->endDate = Carbon::create($this->year, 12, 31)->endOfYear();
        } else {
            $this->startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
            $this->endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();
        }
    }

    private function calculateValues()
    {
        // For the selected month
        $startMonth = Carbon::create($this->year, $this->month)->startOfMonth();
        $endMonth = Carbon::create($this->year, $this->month)->endOfMonth();

        $rejectOrFreeSumMonth = RejectOrFree::whereBetween('date', [$startMonth, $endMonth])->sum('sets') * $this->getPurchasePriceSet();
        $expenseSumMonth = Expense::whereBetween('date', [$startMonth, $endMonth])->sum('amount');
        $branchSalePriceSumMonth = BranchSale::whereBetween('date', [$startMonth, $endMonth])->sum('total_price');

        $headOfficeSalePriceSumMonth = HeadOfficeSale::whereBetween('date', [$startMonth, $endMonth])->sum('total_price');


        $saleSetBranchBuyPriceMonth = BranchSale::whereBetween('date', [$startMonth, $endMonth])->sum('sets') * $this->getPurchasePriceSet();
        $saleSetHoBuyPriceMonth = HeadOfficeSale::whereBetween('date', [$startMonth, $endMonth])->sum('sets') * $this->getPurchasePriceSet();
        $saleStampBuyPriceMonth = $saleSetBranchBuyPriceMonth  + $saleSetHoBuyPriceMonth;

        $branchSalePriceSumMonth = $branchSalePriceSumMonth - $saleSetBranchBuyPriceMonth;
        $headOfficeSalePriceSumMonth = $headOfficeSalePriceSumMonth - $saleSetHoBuyPriceMonth;

        // Calculate SofarNetProfit totals
        $sofarNetProfitSumMonth = SofarNetProfit::whereBetween('date', [$startMonth, $endMonth])->sum('amount');

        $totalLossMonth = $rejectOrFreeSumMonth + $expenseSumMonth;
        $totalRevenueMonth = $branchSalePriceSumMonth + $headOfficeSalePriceSumMonth + $sofarNetProfitSumMonth;
        $netProfitMonth = $totalRevenueMonth - $totalLossMonth;

        // For the entire year
        $startYear = Carbon::create(2023)->startOfYear();
        $endYear = Carbon::now();

        $rejectOrFreeSumYear = RejectOrFree::whereBetween('date', [$startYear, $endYear])->sum('sets') * $this->getPurchasePriceSet();
        $expenseSumYear = Expense::whereBetween('date', [$startYear, $endYear])->sum('amount');
        $branchSalePriceSumYear = BranchSale::whereBetween('date', [$startYear, $endYear])->sum('total_price');
        $headOfficeSalePriceSumYear = HeadOfficeSale::whereBetween('date', [$startYear, $endYear])->sum('total_price');

        $saleStampBranchBuyPriceYear = BranchSale::whereBetween('date', [$startYear, $endYear])->sum('sets') * $this->getPurchasePriceSet();
        $saleStampHoBuyPriceYear =  HeadOfficeSale::whereBetween('date', [$startYear, $endYear])->sum('sets') * $this->getPurchasePriceSet();
        $saleStampBuyPriceYear = $saleStampBranchBuyPriceYear + $saleStampHoBuyPriceYear;


        $headOfficeSalePriceSumYear = $headOfficeSalePriceSumYear - $saleStampHoBuyPriceYear;
        $branchSalePriceSumYear = $branchSalePriceSumYear - $saleStampBranchBuyPriceYear;

        $sofarNetProfitSumYear = SofarNetProfit::whereBetween('date', [$startYear, $endYear])->sum('amount');

        $totalLossYear = $rejectOrFreeSumYear + $expenseSumYear;
        $totalRevenueYear = $branchSalePriceSumYear + $headOfficeSalePriceSumYear + $sofarNetProfitSumYear;
        $netProfitYear = $totalRevenueYear - $totalLossYear;



        return [
            'rejectOrFreeSumMonth' => $rejectOrFreeSumMonth,
            'rejectOrFreeSumYear' => $rejectOrFreeSumYear,
            'expenseSumMonth' => $expenseSumMonth,
            'expenseSumYear' => $expenseSumYear,
            'branchSalePriceSumMonth' => $branchSalePriceSumMonth,
            'branchSalePriceSumYear' => $branchSalePriceSumYear,
            'headOfficeSalePriceSumMonth' => $headOfficeSalePriceSumMonth,
            'headOfficeSalePriceSumYear' => $headOfficeSalePriceSumYear,
            'saleStampBuyPriceMonth' => $saleStampBuyPriceMonth,
            'saleStampBuyPriceYear' => $saleStampBuyPriceYear,
            'totalLossMonth' => $totalLossMonth,
            'totalLossYear' => $totalLossYear,
            'totalRevenueMonth' => $totalRevenueMonth,
            'totalRevenueYear' => $totalRevenueYear,
            'netProfitMonth' => $netProfitMonth,
            'netProfitYear' => $netProfitYear,
            'sofarNetProfitSumMonth' => $sofarNetProfitSumMonth,
            'sofarNetProfitSumYear' => $sofarNetProfitSumYear,
        ];
    }

    private function getPurchasePriceSet()
    {
        $stockTotalPriceSum = Stock::sum('total_price');
        $totalStockSetsSum = Stock::sum('sets');
        return $stockTotalPriceSum / $totalStockSetsSum;
    }

    public function exportCSV()
    {
        $values = $this->calculateValues();
        $fileName = 'balance_sheet_' . $this->year . '_' . $this->month . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($values) {
            $handle = fopen('php://output', 'w');

            // Write header row
            fputcsv($handle, ['Title', 'For the month Amount', 'For the year Amount']);

            // Write data rows
            fputcsv($handle, ['Reject or Free (Total Purchase Price)', number_format($values['rejectOrFreeSumMonth'], 2), number_format($values['rejectOrFreeSumYear'], 2)]);
            fputcsv($handle, ['Expenses', number_format($values['expenseSumMonth'], 2), number_format($values['expenseSumYear'], 2)]);
            fputcsv($handle, ['Sale Set Purchase Price', number_format($values['saleStampBuyPriceMonth'], 2), number_format($values['saleStampBuyPriceYear'], 2)]);
            fputcsv($handle, ['Branch Sales (Total Price)', number_format($values['branchSalePriceSumMonth'], 2), number_format($values['branchSalePriceSumYear'], 2)]);
            fputcsv($handle, ['Head Office Sales (Total Price)', number_format($values['headOfficeSalePriceSumMonth'], 2), number_format($values['headOfficeSalePriceSumYear'], 2)]);
            fputcsv($handle, ['Total Loss', number_format($values['totalLossMonth'], 2), number_format($values['totalLossYear'], 2)]);
            fputcsv($handle, ['Total Revenue', number_format($values['totalRevenueMonth'], 2), number_format($values['totalRevenueYear'], 2)]);
            fputcsv($handle, ['Sofar Net Profit', number_format($values['sofarNetProfitSumMonth'], 2), number_format($values['sofarNetProfitSumYear'], 2)]);
            fputcsv($handle, ['Net Profit', number_format($values['netProfitMonth'], 2), number_format($values['netProfitYear'], 2)]);

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }



    public function render()
    {
        $values = $this->calculateValues();

        return view('livewire.balance-sheet-component', [
            'rejectOrFreeSumMonth' => $values['rejectOrFreeSumMonth'],
            'rejectOrFreeSumYear' => $values['rejectOrFreeSumYear'],
            'expenseSumMonth' => $values['expenseSumMonth'],
            'expenseSumYear' => $values['expenseSumYear'],
            'branchSalePriceSumMonth' => $values['branchSalePriceSumMonth'],
            'branchSalePriceSumYear' => $values['branchSalePriceSumYear'],
            'headOfficeSalePriceSumMonth' => $values['headOfficeSalePriceSumMonth'],
            'headOfficeSalePriceSumYear' => $values['headOfficeSalePriceSumYear'],
            'saleStampBuyPriceMonth' => $values['saleStampBuyPriceMonth'],
            'saleStampBuyPriceYear' => $values['saleStampBuyPriceYear'],
            'totalLossMonth' => $values['totalLossMonth'],
            'totalLossYear' => $values['totalLossYear'],
            'totalRevenueMonth' => $values['totalRevenueMonth'],
            'totalRevenueYear' => $values['totalRevenueYear'],
            'netProfitMonth' => $values['netProfitMonth'],
            'netProfitYear' => $values['netProfitYear'],
            'sofarNetProfitSumMonth' => $values['sofarNetProfitSumMonth'],
            'sofarNetProfitSumYear' => $values['sofarNetProfitSumYear'],
        ]);
    }


    public function exportPDF()
    {
        $values = $this->calculateValues();

        $pdf = Pdf::loadView('pdf.balance-sheet', [
            'year' => (int)$this->year,
            'month' => (int)$this->month,
            'rejectOrFreeSumMonth' => $values['rejectOrFreeSumMonth'],
            'rejectOrFreeSumYear' => $values['rejectOrFreeSumYear'],
            'expenseSumMonth' => $values['expenseSumMonth'],
            'expenseSumYear' => $values['expenseSumYear'],
            'branchSalePriceSumMonth' => $values['branchSalePriceSumMonth'],
            'branchSalePriceSumYear' => $values['branchSalePriceSumYear'],
            'headOfficeSalePriceSumMonth' => $values['headOfficeSalePriceSumMonth'],
            'headOfficeSalePriceSumYear' => $values['headOfficeSalePriceSumYear'],
            'saleStampBuyPriceMonth' => $values['saleStampBuyPriceMonth'],
            'saleStampBuyPriceYear' => $values['saleStampBuyPriceYear'],
            'totalLossMonth' => $values['totalLossMonth'],
            'totalLossYear' => $values['totalLossYear'],
            'totalRevenueMonth' => $values['totalRevenueMonth'],
            'totalRevenueYear' => $values['totalRevenueYear'],
            'netProfitMonth' => $values['netProfitMonth'],
            'netProfitYear' => $values['netProfitYear'],
            'sofarNetProfitSumMonth' => $values['sofarNetProfitSumMonth'],
            'sofarNetProfitSumYear' => $values['sofarNetProfitSumYear'],
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'balance_sheet_' . $this->year . '_' . $this->month . '.pdf');
    }
}
