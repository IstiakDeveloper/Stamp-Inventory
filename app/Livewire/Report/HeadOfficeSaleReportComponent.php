<?php

namespace App\Livewire\Report;

use App\Models\HeadOfficeDue;
use App\Models\HeadOfficeSale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class HeadOfficeSaleReportComponent extends Component
{
public $selectedMonth;
    public $selectedYear;
    public $soFarDue;
    public $soFarSets;
    public $soFarCash;
    public $previousMonthDue;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('m');
        $this->selectedYear = Carbon::now()->format('Y');
        $this->calculateSoFarDue();
        $this->calculatePreviousMonthDue();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'selectedMonth' || $propertyName === 'selectedYear') {
            $this->calculateSoFarDue();
            $this->calculatePreviousMonthDue();
        }
    }

    public function render()
    {
        $sales = $this->getSalesData();
        $completeMonth = $this->getCompleteMonth();

        // Calculate the total due by adding the soFarDue to the sum of the due amounts from the sales data
        $totalDue = $this->soFarDue + $sales->sum('due');

        return view('livewire.report.head-office-sale-report-component', [
            'sales' => $sales,
            'completeMonth' => $completeMonth,
            'soFarDue' => $this->soFarDue,
            'previousMonthDue' => $this->previousMonthDue,
            'soFarSets' => $this->soFarSets,
            'soFarCash' => $this->soFarCash,
            'totalSetPrice' => $sales->sum('sets'),
            'totalPrice' => $sales->sum('price'),
            'totalCashReceived' => $sales->sum('cash'),
            'totalDue' => $totalDue,
        ]);
    }

    private function calculateSoFarDue()
    {
        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endOfPreviousMonth = $startOfMonth->subDay(); // End of the previous month

        $this->soFarDue = HeadOfficeDue::where('date', '<=', $endOfPreviousMonth)->sum('due_amount');
        $this->soFarSets = HeadOfficeSale::where('date', '<=', $endOfPreviousMonth)->sum('sets');
        $this->soFarCash = HeadOfficeSale::where('date', '<=', $endOfPreviousMonth)->sum('cash');
    }

    private function calculatePreviousMonthDue()
    {
        $previousMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $startOfPreviousMonth = $previousMonth->startOfMonth();
        $endOfPreviousMonth = $previousMonth->endOfMonth();

        $this->previousMonthDue = HeadOfficeDue::whereBetween('date', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('due_amount');
    }

    private function getSalesData()
    {
        $sales = HeadOfficeSale::whereYear('date', $this->selectedYear)
                               ->whereMonth('date', $this->selectedMonth)
                               ->get();

        return $sales->groupBy('date')->map(function ($salesOnDate) {
            $totalSets = $salesOnDate->sum('sets');
            $firstSale = $salesOnDate->first(); // Get the first sale of the day for the set price
            $totalPrice = $salesOnDate->sum('total_price');
            $totalCash = $salesOnDate->sum('cash');
            $totalDue = $salesOnDate->sum(fn($sale) => $sale->total_price - $sale->cash);

            return [
                'date' => $firstSale->date,
                'sets' => $totalSets,
                'set_price' => $firstSale->per_set_price,
                'price' => $totalPrice,
                'cash' => $totalCash,
                'due' => $totalDue,
            ];
        })->values();
    }

    public function downloadPdf()
    {
        $this->calculateSoFarDue();
        $this->calculatePreviousMonthDue();

        $sales = $this->getSalesData();
        $completeMonth = $this->getCompleteMonth();
        $totalDue = $this->soFarDue + $sales->sum('due');

        $pdf = Pdf::loadView('pdf.head-office-sale-report', [
            'sales' => $sales,
            'completeMonth' => $completeMonth,
            'soFarDue' => $this->soFarDue,
            'soFarSets' => $this->soFarSets,
            'soFarCash' => $this->soFarCash,
            'previousMonthDue' => $this->previousMonthDue,
            'totalSetPrice' => $sales->sum('sets'),
            'totalPrice' => $sales->sum('price'),
            'totalCashReceived' => $sales->sum('cash'),
            'totalDue' => $totalDue,
            'selectedMonth' => (int)$this->selectedMonth,
            'selectedYear' => (int)$this->selectedYear,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'head-office-sale-report-' . $this->selectedYear . '-' . $this->selectedMonth . '.pdf');
    }

    private function getCompleteMonth()
    {
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->daysInMonth;

        return collect(range(1, $daysInMonth))->map(function ($day) {
            return Carbon::create($this->selectedYear, $this->selectedMonth, $day);
        });
    }
}
