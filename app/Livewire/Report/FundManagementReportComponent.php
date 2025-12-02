<?php

namespace App\Livewire\Report;

use App\Models\FundManagement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class FundManagementReportComponent extends Component
{
    use WithPagination;

    public $month;
    public $year;
    public $data = [];
    public $previousBalance = 0;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->loadData();
    }

    public function updated($propertyName)
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Calculate the previous balance
        // We need to consider all transactions up to the end of the previous month
        $previousMonthEnd = Carbon::create($this->year, $this->month, 1)->subMonth()->endOfMonth();

        $previousMonthData = FundManagement::where('date', '<=', $previousMonthEnd)
            ->get();

        $this->previousBalance = $previousMonthData->reduce(function ($carry, $item) {
            return $carry + ($item->type === 'fund_in' ? $item->amount : -$item->amount);
        }, 0);

        // Data for the selected month
        $this->data = FundManagement::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->orderBy('date')
            ->get();
    }

    public function downloadPdf()
    {
        $this->loadData();

        $pdf = Pdf::loadView('pdf.fund-management-report', [
            'data' => $this->data,
            'previousBalance' => $this->previousBalance,
            'year' => $this->year,
            'month' => $this->month,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'fund-management-report-' . $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function render()
    {
        return view('livewire.report.fund-management-report-component');
    }
}
