<?php

namespace App\Livewire\Report;

use App\Models\BranchSale;
use App\Models\Expense;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\Stock;
use App\Models\Loan;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptPaymentReportComponent extends Component
{
    public $month;
    public $year;
    public $data;
    public $previousMonthData;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
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

        $this->loadData();
    }

    public function loadData()
    {
        // Ensure proper type casting
        $month = (int) $this->month;
        $year = (int) $this->year;

        // Initialize the selected month start and end dates
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Data for all time before the selected month (Opening Cash at Bank)
        $endOfPreviousMonth = $startDate->copy()->subMonth()->endOfMonth();

        $cashInBefore = Money::where('type', 'cash_in')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $cashOutBefore = Money::where('type', 'cash_out')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $purchaseBefore = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('total_price');
        $expensesBefore = Expense::whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $cashReceiveBefore = HeadOfficeSale::where('date', '<=', $endOfPreviousMonth)->sum('cash')
            + BranchSale::where('date', '<=', $endOfPreviousMonth)->sum('cash');

        // Add loan calculations for opening balance
        $loansGivenBefore = Loan::where('date', '<=', $endOfPreviousMonth)->sum('amount');
        $loanPaymentsBefore = LoanPayment::where('date', '<=', $endOfPreviousMonth)->sum('amount');

        // Opening Cash at Bank (Previous Month's Balance) - including loan impact
        $openingBalance = $cashInBefore - $cashOutBefore + $cashReceiveBefore - $purchaseBefore - $expensesBefore - $loansGivenBefore + $loanPaymentsBefore;

        // Data for the current month
        $stampSaleCollection = HeadOfficeSale::whereBetween('date', [$startDate, $endDate])->sum('cash')
            + BranchSale::whereBetween('date', [$startDate, $endDate])->sum('cash');

        $fundReceive = Money::where('type', 'cash_in')
            ->whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Add loan payments received to receipts
        $loanPaymentsReceived = LoanPayment::whereBetween('date', [$startDate, $endDate])->sum('amount');

        $purchaseOfStamps = Stock::whereBetween('date', [$startDate, $endDate])->sum('total_price');
        $fundRefund = Money::where('type', 'cash_out')
            ->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $otherExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Add loans given to payments
        $loansGiven = Loan::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Closing Cash at Bank - including loan impact
        $closingBalance = $openingBalance + $stampSaleCollection + $fundReceive + $loanPaymentsReceived - $purchaseOfStamps - $fundRefund - $otherExpenses - $loansGiven;

        $totalReceipt = $openingBalance + $stampSaleCollection + $fundReceive + $loanPaymentsReceived;
        $totalPayment = $purchaseOfStamps + $fundRefund + $otherExpenses + $loansGiven + $closingBalance;

        // Store data for rendering
        $this->data = [
            'opening_balance' => $openingBalance,
            'stamp_sale_collection' => $stampSaleCollection,
            'fund_receive' => $fundReceive,
            'loan_payments_received' => $loanPaymentsReceived,
            'purchase_of_stamps' => $purchaseOfStamps,
            'fund_refund' => $fundRefund,
            'other_expenses' => $otherExpenses,
            'loans_given' => $loansGiven,
            'closing_balance' => $closingBalance,
            'total_receipt' => $totalReceipt,
            'total_payment' => $totalPayment,
        ];
    }

    public function render()
    {
        return view('livewire.report.receipt-payment-report-component');
    }

    public function downloadPdf()
    {
        $this->loadData();

        $pdf = Pdf::loadView('pdf.receipt-payment-report', [
            'data' => $this->data,
            'month' => (int)$this->month,
            'year' => (int)$this->year,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);

        $monthNames = ["", "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $filename = 'Receipt-Payment-Statement-' . $monthNames[$this->month] . '-' . $this->year . '.pdf';

        $this->dispatch('openPdfInNewTab', base64: $base64, filename: $filename);
    }
}
