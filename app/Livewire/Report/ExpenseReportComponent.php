<?php

namespace App\Livewire\Report;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseReportComponent extends Component
{
    public $currentMonth;
    public $currentYear;
    public $expensesRecords;
    public $totalAmount;
    public $previousMonthTotalExpenses;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        $this->previousMonthTotalExpenses = $this->calculatePreviousMonthTotalExpenses($this->currentMonth, $this->currentYear);

        $this->fetchRecords();
    }

    public function calculatePreviousMonthTotalExpenses($selectedMonth, $selectedYear)
    {
        $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->subDay();

        $previousTotal = Expense::whereDate('date', '<=', $endDate)
                                ->sum('amount');

        return $previousTotal;
    }

    public function fetchRecords()
    {
        $this->expensesRecords = Expense::whereMonth('date', $this->currentMonth)
                                        ->whereYear('date', $this->currentYear)
                                        ->get();

        $this->totalAmount = $this->expensesRecords->sum('amount');
    }

    public function updated($field)
    {
        $this->previousMonthTotalExpenses = $this->calculatePreviousMonthTotalExpenses($this->currentMonth, $this->currentYear);
        $this->fetchRecords();
    }

    public function render()
    {
        return view('livewire.report.expense-report-component');
    }

    public function downloadPDF()
    {
        $data = [
            'expensesRecords' => $this->expensesRecords,
            'totalAmount' => $this->totalAmount,
            'previousMonthTotalExpenses' => $this->previousMonthTotalExpenses,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
            'monthNameYear' => date('F-Y', mktime(0, 0, 0, $this->currentMonth, 1)), // Month and Year for filename
        ];

        $pdf = Pdf::loadView('pdf.expense-report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()), 
            'expense-report-'.$data['monthNameYear'].'.pdf'
        );
    }
}
