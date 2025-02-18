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

    public function mount()
    {
        // Set default values for current month and year
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        // Calculate the net loss for the previous month
        $this->previousMonthNetLoss = $this->calculatePreviousMonthNetLoss($this->currentMonth, $this->currentYear);

        // Fetch records for the current month and year
        $this->fetchRecords();
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

        return view('livewire.report.reject-or-free-report-component', [
            'averageStampPricePerSet' => $averageStampPricePerSet,
            'totalSetsBuy' => $totalSetsBuy,
            'totalSetsBuyPrice' => $totalSetsBuyPrice,
        ]);
    }

    public function downloadPDF()
    {
        $pdf = Pdf::loadView('pdf.reject-or-free-report', [
            'rejectOrFreeRecords' => $this->rejectOrFreeRecords,
            'averageStampPricePerSet' => $this->getAverageStampPricePerSet(),
            'previousMonthNetLoss' => $this->previousMonthNetLoss,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
        ]);

        return response()->stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="reject-or-free-report-' . $this->currentYear . '-' . str_pad($this->currentMonth, 2, '0', STR_PAD_LEFT) . '.pdf"',
            ]
        );
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
