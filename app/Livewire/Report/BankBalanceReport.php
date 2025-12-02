<?php

namespace App\Livewire\Report;

use App\Models\BranchSale;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\Stock;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\LoanPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class BankBalanceReport extends Component
{
    use WithPagination;

    public $month;
    public $year;
    public $data;
    public $previousMonthData;
    public $monthName;
    public $yearOptions;
    public $monthOptions;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->setupOptions();
        $this->loadData();
    }

    public function updated($propertyName)
    {
        // Ensure proper type casting
        if ($propertyName === 'month') {
            $this->month = (int) $this->month;
        }
        if ($propertyName === 'year') {
            $this->year = (int) $this->year;
        }

        $this->setupOptions();
        $this->loadData();
    }

    private function setupOptions()
    {
        // Setup month and year options to avoid Carbon issues in blade
        $this->monthName = Carbon::create()->month((int) $this->month)->format('F');

        $this->yearOptions = [];
        for ($i = Carbon::now()->year - 5; $i <= Carbon::now()->year; $i++) {
            $this->yearOptions[] = $i;
        }

        $this->monthOptions = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->monthOptions[$i] = Carbon::create()->month($i)->format('F');
        }
    }

    public function loadData()
    {
        // Ensure proper type casting
        $month = (int) $this->month;
        $year = (int) $this->year;

        // Initialize the selected month start and end dates
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Initialize date ranges for the selected month
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Data for all time before the selected month
        $endOfPreviousMonth = $startDate->copy()->subMonth()->endOfMonth();
        $allTimeBeforeSummary = [
            'cash_in' => 0,
            'cash_out' => 0,
            'purchase_sets' => 0,
            'purchase_price' => 0,
            'expenses' => 0,
            'loans_given' => 0,
            'loan_payments_received' => 0
        ];

        // Calculate totals before the end of the previous month
        $allTimeBeforeSummary['cash_in'] = Money::where('type', 'cash_in')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $allTimeBeforeSummary['cash_out'] = Money::where('type', 'cash_out')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $allTimeBeforeSummary['purchase_sets'] = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('sets');
        $allTimeBeforeSummary['purchase_price'] = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('total_price');
        $allTimeBeforeSummary['expenses'] = Expense::whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');

        // Add loan calculations
        $allTimeBeforeSummary['loans_given'] = Loan::where('date', '<=', $endOfPreviousMonth)->sum('amount');
        $allTimeBeforeSummary['loan_payments_received'] = LoanPayment::where('date', '<=', $endOfPreviousMonth)->sum('amount');

        $cashReceiveBefore = HeadOfficeSale::whereDate('date', '<=', $endOfPreviousMonth)->sum('cash')
            + BranchSale::whereDate('date', '<=', $endOfPreviousMonth)->sum('cash');

        $previousMonthBalance = $allTimeBeforeSummary['cash_in']
            - $allTimeBeforeSummary['cash_out']
            + $cashReceiveBefore
            - $allTimeBeforeSummary['purchase_price']
            - $allTimeBeforeSummary['expenses']
            - $allTimeBeforeSummary['loans_given']
            + $allTimeBeforeSummary['loan_payments_received'];

        // Set the previous month data to be displayed in the view
        $this->previousMonthData = [
            'cash_in' => $allTimeBeforeSummary['cash_in'],
            'cash_out' => $allTimeBeforeSummary['cash_out'],
            'purchase_sets' => $allTimeBeforeSummary['purchase_sets'],
            'purchase_price' => $allTimeBeforeSummary['purchase_price'],
            'cash_receive' => $cashReceiveBefore,
            'expenses' => $allTimeBeforeSummary['expenses'],
            'loans_given' => $allTimeBeforeSummary['loans_given'],
            'loan_payments_received' => $allTimeBeforeSummary['loan_payments_received'],
            'balance' => $previousMonthBalance
        ];

        // Data for the selected month
        $currentMonthData = [];
        $balance = $previousMonthBalance;
        foreach ($dates as $dateStr) {
            $cashIn = Money::where('type', 'cash_in')->whereDate('date', $dateStr)->sum('amount');
            $cashOut = Money::where('type', 'cash_out')->whereDate('date', $dateStr)->sum('amount');
            $cashReceive = HeadOfficeSale::whereDate('date', $dateStr)->sum('cash')
                + BranchSale::whereDate('date', $dateStr)->sum('cash');
            $purchasePrice = Stock::whereDate('date', $dateStr)->sum('total_price');
            $purchaseSets = Stock::whereDate('date', $dateStr)->sum('sets');
            $expenses = Expense::whereDate('date', $dateStr)->sum('amount');

            // Add loan calculations for the specific date
            $loansGiven = Loan::where('date', $dateStr)->sum('amount');
            $loanPaymentsReceived = LoanPayment::where('date', $dateStr)->sum('amount');

            // Calculate available balance for this date
            $balance += $cashIn - $cashOut + $cashReceive - $purchasePrice - $expenses - $loansGiven + $loanPaymentsReceived;

            $currentMonthData[] = [
                'date' => $dateStr,
                'cash_in' => $cashIn,
                'cash_out' => $cashOut,
                'cash_receive' => $cashReceive,
                'purchase_sets' => $purchaseSets,
                'purchase_price' => $purchasePrice,
                'expenses' => $expenses,
                'loans_given' => $loansGiven,
                'loan_payments_received' => $loanPaymentsReceived,
                'available_balance' => $balance,
            ];
        }

        $this->data = $currentMonthData;
    }

    public function render()
    {
        return view('livewire.report.bank-balance-report');
    }

    public function downloadPdf()
    {
        $this->loadData();

        $month = (int) $this->month;
        $year = (int) $this->year;
        $monthName = Carbon::create()->month($month)->format('F');

        $pdf = Pdf::loadView('pdf.bank-balance-report', [
            'previousMonthData' => $this->previousMonthData,
            'data' => $this->data,
            'month' => $monthName,
            'year' => $year,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'bank-balance-report-' . $monthName . '-' . $year . '.pdf');
    }
}
