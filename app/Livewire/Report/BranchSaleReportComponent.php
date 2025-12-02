<?php

namespace App\Livewire\Report;

use App\Models\Branch;
use App\Models\BranchSale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class BranchSaleReportComponent extends Component
{
    public $fromDate;
    public $toDate;
    public $branchId;
    public $soFarOutstanding = 0;
    public $sales;
    public $totalSets = 0;
    public $totalPrice = 0;
    public $totalCash = 0;
    public $totalDue = 0;
    public $serialNumber = 1;

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['branchId', 'fromDate', 'toDate'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        // Step 1: Calculate the initial outstanding balance from the branch
        $branch = Branch::find($this->branchId);
        $initialOutstanding = $branch ? $branch->outstanding_balance : 0;

        // Step 2: Calculate the previous outstanding balance before the report period
        if ($this->fromDate) {
            $fromDate = Carbon::parse($this->fromDate)->format('Y-m-d');

            // Sum of total prices before 'fromDate'
            $previousPrice = BranchSale::where('branch_id', $this->branchId)
                ->whereDate('date', '<', $fromDate)
                ->sum('total_price');

            // Sum of cash received before 'fromDate'
            $previousCash = BranchSale::where('branch_id', $this->branchId)
                ->whereDate('date', '<', $fromDate)
                ->sum('cash');

            // Calculate previous outstanding: initial outstanding + total price - total cash before the report period
            $previousOutstanding = $initialOutstanding + $previousPrice - $previousCash;
        } else {
            // If 'fromDate' is not provided, previous outstanding is just the initial outstanding
            $previousOutstanding = $initialOutstanding;
        }

        // Initialize the outstanding balance up to the start of the period
        $this->soFarOutstanding = $previousOutstanding;

        // Step 3: Query sales data within the specified date range
        $query = BranchSale::where('branch_id', $this->branchId);

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->where('date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where('date', '<=', $this->toDate);
        }

        // Fetch sales data for the branch
        $this->sales = $query->get();

        // Step 4: Initialize cumulative due with the outstanding balance up to the start of the period
        $cumulativeDue = $this->soFarOutstanding;

        // Step 5: Calculate totals for display
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');

        foreach ($this->sales as $sale) {
            $previousDue = $cumulativeDue;

            // Update the cumulative due by adding the sale's price and subtracting the cash received
            $cumulativeDue += $sale->total_price - $sale->cash;

            // Assign the cumulative due to the sale's total_due attribute
            $sale->total_due = $cumulativeDue;

            // Store the previous due in a new attribute
            $sale->previous_due = $previousDue;
        }

        // Step 6: Calculate the final total due for the entire period
        $this->totalDue = $cumulativeDue;
    }

    public function downloadPdf()
    {
        $this->generateReport();
        $branch = Branch::find($this->branchId);

        $pdf = Pdf::loadView('pdf.branch-sale-report', [
            'soFarOutstanding' => $this->soFarOutstanding,
            'sales' => $this->sales,
            'branchName' => $branch ? $branch->branch_name : 'Unknown Branch',
            'totalSets' => $this->totalSets,
            'totalPrice' => $this->totalPrice,
            'totalCash' => $this->totalCash,
            'totalDue' => $this->totalDue,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'branch-sale-report-' . ($this->fromDate ?? 'no-date') . '-to-' . ($this->toDate ?? 'no-date') . '.pdf');
    }

    public function calculateTotals()
    {
        $this->totalSets = $this->sales->sum('sets');
        $this->totalPrice = $this->sales->sum('total_price');
        $this->totalCash = $this->sales->sum('cash');
        $this->totalDue = max($this->totalPrice - $this->totalCash, 0);
    }

    public function render()
    {
        $this->calculateTotals();
        $branches = Branch::all();
        $selectedBranch = $this->branchId ? Branch::find($this->branchId) : null;

        return view('livewire.report.branch-sale-report-component', [
            'branches' => $branches,
            'selectedBranch' => $selectedBranch,
        ]);
    }
}
