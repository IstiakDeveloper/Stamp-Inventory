<?php

namespace App\Livewire\Report;

use App\Models\Money;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class MoneyReport extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $transactions = [];
    public $openingBalance;
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
        $this->years = range(Carbon::now()->format('Y'), 2000); // Adjust the range as needed
        $this->filterTransactions();
    }

    public function filterTransactions()
    {
        $query = Money::query();

        if ($this->selectedYear !== 'all') {
            $query->whereYear('date', $this->selectedYear);
        }

        if ($this->selectedMonth !== 'all') {
            $query->whereMonth('date', $this->selectedMonth);
        }

        $this->transactions = $query->get();
        $this->calculateOpeningBalance();
    }

    public function calculateOpeningBalance()
    {
        if ($this->selectedYear === 'all' && $this->selectedMonth === 'all') {
            $this->openingBalance = 0;
            return;
        }

        $previousDate = Carbon::parse($this->selectedYear . '-' . ($this->selectedMonth !== 'all' ? $this->selectedMonth : '01') . '-01')
            ->subDay()
            ->format('Y-m-d');

        $balanceUpToPreviousDate = Money::where('date', '<=', $previousDate)
            ->get()
            ->reduce(function ($carry, $item) {
                return $carry + ($item->type === 'cash_in' ? $item->amount : -$item->amount);
            }, 0);

        $this->openingBalance = $balanceUpToPreviousDate;
    }

    public function handleFilterChange()
    {
        $this->filterTransactions();
    }

    public function downloadPdf()
    {
        $this->filterTransactions();

        $pdf = Pdf::loadView('pdf.money-report', [
            'transactions' => $this->transactions,
            'openingBalance' => $this->openingBalance,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'money-report.pdf');
    }

    public function render()
    {
        return view('livewire.report.money-report', [
            'transactions' => $this->transactions,
            'openingBalance' => $this->openingBalance,
            'years' => $this->years,
            'months' => $this->months,
        ]);
    }



}
