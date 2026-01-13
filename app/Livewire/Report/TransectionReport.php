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

        $monthName = $this->month ? date('F', mktime(0, 0, 0, $this->month, 1)) : 'All';
        $filename = 'Transactions-Report-' . $monthName . '-' . ($this->year ?? 'All-Years') . '.pdf';

        $pdf = Pdf::loadView('pdf.transactions', [
            'transactions' => $this->transactions,
        ])->setPaper('a4');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

    public function render()
    {
        return view('livewire.report.transection-report', [
            'transactions' => $this->transactions,
        ]);
    }

}
