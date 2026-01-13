<?php

namespace App\Livewire\Report;

use App\Models\Income;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class IncomeReportComponent extends Component
{
    public $currentMonth;
    public $currentYear;
    public $incomesRecords;
    public $totalAmount;
    public $previousMonthTotalIncome;
    public $incomeByType;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        $this->previousMonthTotalIncome = $this->calculatePreviousMonthTotalIncome($this->currentMonth, $this->currentYear);

        $this->fetchRecords();
    }

    public function calculatePreviousMonthTotalIncome($selectedMonth, $selectedYear)
    {
        $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->subDay();

        $previousTotal = Income::whereDate('date', '<=', $endDate)
                                ->sum('amount');

        return $previousTotal;
    }

    public function fetchRecords()
    {
        $this->incomesRecords = Income::whereMonth('date', $this->currentMonth)
                                        ->whereYear('date', $this->currentYear)
                                        ->get();

        $this->totalAmount = $this->incomesRecords->sum('amount');

        // Group income by type
        $this->incomeByType = $this->incomesRecords->groupBy('type')->map(function($items) {
            return $items->sum('amount');
        });
    }

    public function updated($field)
    {
        $this->previousMonthTotalIncome = $this->calculatePreviousMonthTotalIncome($this->currentMonth, $this->currentYear);
        $this->fetchRecords();
    }

    public function render()
    {
        return view('livewire.report.income-report-component');
    }

    public function downloadPDF()
    {
        $this->fetchRecords();
        $this->previousMonthTotalIncome = $this->calculatePreviousMonthTotalIncome($this->currentMonth, $this->currentYear);

        $monthNames = [
            1 => "January", 2 => "February", 3 => "March", 4 => "April",
            5 => "May", 6 => "June", 7 => "July", 8 => "August",
            9 => "September", 10 => "October", 11 => "November", 12 => "December"
        ];
        $monthNameYear = $monthNames[(int)$this->currentMonth] . '-' . $this->currentYear;

        $pdf = Pdf::loadView('pdf.income-report', [
            'incomesRecords' => $this->incomesRecords,
            'totalAmount' => $this->totalAmount,
            'incomeByType' => $this->incomeByType,
            'previousMonthTotalIncome' => $this->previousMonthTotalIncome,
            'currentMonth' => (int)$this->currentMonth,
            'currentYear' => (int)$this->currentYear,
            'monthNameYear' => $monthNameYear,
        ])->setPaper('a4');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'Income-Report-' . $monthNameYear . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
