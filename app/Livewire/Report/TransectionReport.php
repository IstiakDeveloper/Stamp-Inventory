<?php

namespace App\Livewire\Report;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class TransectionReport extends Component
{
    public $year;
    public $month;
    public $fromDate;
    public $toDate;
    public $transactions = [];

    public function mount()
    {
        $this->year = Carbon::now()->format('Y');
        $this->month = Carbon::now()->format('m');
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        $this->filterTransactions();
    }

    public function updated($propertyName)
    {
        $this->filterTransactions();
    }

    private function filterTransactions()
    {
        $query = Payment::query();

        if ($this->year) {
            $query->whereYear('date', $this->year);
        }

        if ($this->month) {
            $query->whereMonth('date', $this->month);
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('date', [$this->fromDate, $this->toDate]);
        }

        $this->transactions = $query->get();
    }

    public function downloadPdf()
    {
        $this->filterTransactions();

        $pdf = Pdf::loadView('pdf.transactions', [
            'transactions' => $this->transactions,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'transactions-report.pdf');
    }

    public function render()
    {
        return view('livewire.report.transection-report', [
            'transactions' => $this->transactions,
        ]);
    }

}
