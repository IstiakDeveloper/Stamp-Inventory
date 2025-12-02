<?php

namespace App\Livewire\Report;

use App\Models\RejectOrFree;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class RejectOrFreeReportComponent extends Component
{
    public $currentMonth;
    public $currentYear;
    public $previousMonthNetLoss;
    public $rejectOrFreeRecords;
    public $cumulativeSets;     // Added property for cumulative sets
    public $cumulativeLoss;     // Added property for cumulative loss

    public function mount()
    {
        // Set default values for current month and year
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        // Calculate the net loss for the previous month
        $this->previousMonthNetLoss = $this->calculatePreviousMonthNetLoss($this->currentMonth, $this->currentYear);

        // Fetch records for the current month and year
        $this->fetchRecords();

        // Calculate cumulative sets and loss
        $this->calculateCumulatives();
    }

    public function calculatePreviousMonthNetLoss($selectedMonth, $selectedYear)
    {
        // Get the last day of the previous month
        $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->subDay();

        // Get the cumulative net loss up to the end of the previous month
        $previousNetLoss = RejectOrFree::whereDate('date', '<=', $endDate)
                            ->sum('purchase_price_total');

        return $previousNetLoss;
    }

    public function calculateCumulatives()
    {
        // Calculate all-time cumulative sets and loss (not just the current month)
        $this->cumulativeSets = RejectOrFree::sum('sets');
        $this->cumulativeLoss = RejectOrFree::sum('purchase_price_total');
    }

    public function fetchRecords()
    {
        $this->rejectOrFreeRecords = RejectOrFree::whereMonth('date', $this->currentMonth)
                                ->whereYear('date', $this->currentYear)
                                ->get();
    }

    public function updated($field)
    {
        // Recalculate when the month or year is changed
        $this->previousMonthNetLoss = $this->calculatePreviousMonthNetLoss($this->currentMonth, $this->currentYear);
        $this->fetchRecords();
        $this->calculateCumulatives();
    }

    public function render()
    {
        $totalSetsBuy = Stock::sum('sets');
        $totalSetsBuyPrice = Stock::sum('total_price');

        if ($totalSetsBuy > 0) {
            $averageStampPricePerSet = $totalSetsBuyPrice / $totalSetsBuy;
        } else {
            $averageStampPricePerSet = 0;
        }

        // Calculate this month's total loss
        $totalMonthlyLoss = 0;
        foreach ($this->rejectOrFreeRecords as $record) {
            $totalMonthlyLoss += $record->sets * $averageStampPricePerSet;
        }

        return view('livewire.report.reject-or-free-report-component', [
            'averageStampPricePerSet' => $averageStampPricePerSet,
            'totalSetsBuy' => $totalSetsBuy,
            'totalSetsBuyPrice' => $totalSetsBuyPrice,
            'totalMonthlyLoss' => $totalMonthlyLoss,
            'cumulativeSets' => $this->cumulativeSets,
            'cumulativeLoss' => $this->cumulativeLoss,
        ]);
    }

    public function downloadPDF()
    {
        $this->fetchRecords();
        $this->calculateCumulatives();
        $this->previousMonthNetLoss = $this->calculatePreviousMonthNetLoss($this->currentMonth, $this->currentYear);

        $averageStampPricePerSet = $this->getAverageStampPricePerSet();
        $totalMonthlyLoss = 0;
        foreach ($this->rejectOrFreeRecords as $record) {
            $totalMonthlyLoss += $record->sets * $averageStampPricePerSet;
        }

        $pdf = Pdf::view('pdf.reject-or-free-report', [
            'rejectOrFreeRecords' => $this->rejectOrFreeRecords,
            'averageStampPricePerSet' => $averageStampPricePerSet,
            'previousMonthNetLoss' => $this->previousMonthNetLoss,
            'currentMonth' => (int)$this->currentMonth,
            'currentYear' => (int)$this->currentYear,
            'totalMonthlyLoss' => $totalMonthlyLoss,
            'cumulativeSets' => $this->cumulativeSets,
            'cumulativeLoss' => $this->cumulativeLoss,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'reject-or-free-report-' . $this->currentYear . '-' . str_pad($this->currentMonth, 2, '0', STR_PAD_LEFT) . '.pdf');
    }

    private function getAverageStampPricePerSet()
    {
        $totalSetsBuy = Stock::sum('sets');
        $totalSetsBuyPrice = Stock::sum('total_price');

        if ($totalSetsBuy > 0) {
            return $totalSetsBuyPrice / $totalSetsBuy;
        }

        return 0;
    }
}
