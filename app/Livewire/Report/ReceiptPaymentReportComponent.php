<?php

namespace App\Livewire\Report;

use App\Models\BranchSale;
use App\Models\Expense;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\Stock;
use Carbon\Carbon;
use Livewire\Component;

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
        $this->loadData();
    }

    public function loadData()
    {
        // Initialize the selected month start and end dates
        $startDate = Carbon::create($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Data for all time before the selected month (Opening Cash at Bank)
        $endOfPreviousMonth = $startDate->copy()->subMonth()->endOfMonth();

        $cashInBefore = Money::where('type', 'cash_in')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $cashOutBefore = Money::where('type', 'cash_out')
            ->whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $purchaseBefore = Stock::whereDate('date', '<=', $endOfPreviousMonth)->sum('total_price');
        $expensesBefore = Expense::whereDate('date', '<=', $endOfPreviousMonth)->sum('amount');
        $cashReceiveBefore = HeadOfficeSale::whereDate('date', '<=', $endOfPreviousMonth)->sum('cash')
            + BranchSale::whereDate('date', '<=', $endOfPreviousMonth)->sum('cash');

        // Opening Cash at Bank (Previous Month's Balance)
        $openingBalance = $cashInBefore - $cashOutBefore + $cashReceiveBefore - $purchaseBefore - $expensesBefore;

        // Data for the current month
        $stampSaleCollection = HeadOfficeSale::whereBetween('date', [$startDate, $endDate])->sum('cash')
            + BranchSale::whereBetween('date', [$startDate, $endDate])->sum('cash');

        $fundReceive = Money::where('type', 'cash_in')
            ->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $purchaseOfStamps = Stock::whereBetween('date', [$startDate, $endDate])->sum('total_price');
        $fundRefund = Money::where('type', 'cash_out')
            ->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $otherExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Closing Cash at Bank
        $closingBalance = $openingBalance + $stampSaleCollection + $fundReceive - $purchaseOfStamps - $fundRefund - $otherExpenses;


        $totalReceipt = $openingBalance + $stampSaleCollection + $fundReceive;
        $totalPayment = $purchaseOfStamps + $fundRefund + $otherExpenses + $closingBalance;
        // Store data for rendering
        $this->data = [
            'opening_balance' => $openingBalance,
            'stamp_sale_collection' => $stampSaleCollection,
            'fund_receive' => $fundReceive,
            'purchase_of_stamps' => $purchaseOfStamps,
            'fund_refund' => $fundRefund,
            'other_expenses' => $otherExpenses,
            'closing_balance' => $closingBalance,
            'total_receipt' => $totalReceipt,
            'total_payment' => $totalPayment,
        ];
    }

    public function render()
    {
        return view('livewire.report.receipt-payment-report-component');
    }
}
