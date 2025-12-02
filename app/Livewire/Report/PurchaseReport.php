<?php

namespace App\Livewire\Report;

use App\Models\Stock;
use App\Models\TotalStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class PurchaseReport extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $stocks = [];
    public $openingStock;
    public $totalStockSum;
    public $years = [];
    public $months = [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = 'all';
        $this->years = range(Carbon::now()->format('Y'), 2000); // Adjust range as needed
        $this->filterStocks();
    }

    public function filterStocks()
    {
        $query = Stock::query();

        if ($this->selectedYear !== 'all') {
            $query->whereYear('date', $this->selectedYear);
        }

        if ($this->selectedMonth !== 'all') {
            $query->whereMonth('date', $this->selectedMonth);
        }

        $this->stocks = $query->get();

        // Update totalStockSum based on the filters
        $this->updateTotalStockSum();

        $this->calculateOpeningStock();
    }

    public function updateTotalStockSum()
    {
        $query = Stock::query();

        if ($this->selectedYear !== 'all') {
            $query->whereYear('date', $this->selectedYear);
        }

        if ($this->selectedMonth !== 'all') {
            $query->whereMonth('date', $this->selectedMonth);
        }

        // Calculate the sum of sets
        $this->totalStockSum = $query->sum('sets');
    }

    public function calculateOpeningStock()
    {
        if ($this->selectedYear === 'all' && $this->selectedMonth === 'all') {
            $this->openingStock = 0;
            return;
        }

        $endOfPreviousMonth = Carbon::parse($this->selectedYear . '-' . ($this->selectedMonth !== 'all' ? $this->selectedMonth : '01') . '-01')
            ->subMonth()
            ->endOfMonth()
            ->format('Y-m-d');

        // Get total sets from TotalStock up to the end of the previous month
        $totalSetsBeforePeriod = $this->totalStockSum;

        // Calculate total sets up to the end of the previous month
        $totalSetsUpToEndOfMonth = Stock::whereDate('date', '<=', $endOfPreviousMonth)
            ->sum('sets'); // Assuming 'sets' is the column in Stock

        $this->openingStock = $totalSetsBeforePeriod - $totalSetsUpToEndOfMonth;
    }

    public function handleFilterChange()
    {
        $this->filterStocks();
    }

    public function downloadPdf()
    {
        $this->filterStocks();

        $monthName = $this->months[$this->selectedMonth] ?? 'All';

        $pdf = Pdf::loadView('pdf.purchase-report', [
            'stocks' => $this->stocks,
            'openingStock' => $this->openingStock,
            'totalStockSum' => $this->totalStockSum,
            'monthName' => $monthName,
            'year' => $this->selectedYear,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'purchase-report.pdf');
    }


    public function render()
    {
        return view('livewire.report.purchase-report', [
            'stocks' => $this->stocks,
            'openingStock' => $this->openingStock,
            'totalStockSum' => $this->totalStockSum,
            'years' => $this->years,
            'months' => $this->months,
        ]);
    }
}
