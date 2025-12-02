<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Branch;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use App\Models\Expense;
use App\Models\FundManagement;
use App\Models\HeadOfficeSale;
use App\Models\Money;
use App\Models\RejectOrFree;
use App\Models\SofarNetProfit;
use App\Models\Stock;
use App\Models\TotalFund;
use App\Models\TotalStock;
use App\Models\Loan;
use App\Models\LoanPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class BalanceSheetAll extends Component
{
    public $totalCashIn;
    public $cashWas;
    public $soFarCash;
    public $totalBankOrHandBalance;
    public $stockStampBuyPrice;
    public $totalOutstandingBalance;
    public $outstandingTotal;
    public $netProfit;
    public $loanReceivables; // New property for loans given

    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;
        $this->generateReport();
    }

    public function updatedSelectedYear()
    {
        $this->generateReport();
    }

    public function updatedSelectedMonth()
    {
        $this->generateReport();
    }

    private function generateReport()
    {
        // Build the last day of the selected month and year
        $endDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();

        $this->totalCashIn = FundManagement::whereDate('date', '<=', $endDate)
            ->selectRaw('SUM(CASE WHEN type = "fund_in" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "fund_out" THEN amount ELSE 0 END) as total_cash_in')
            ->value('total_cash_in');

        // Total cash out until the selected month
        $totalCashOut = Money::where('type', 'cash_out')
            ->where('created_at', '<=', $endDate)
            ->sum('amount');

        // Total outstanding balance until the selected month
        $this->totalOutstandingBalance = Branch::sum('outstanding_balance');

        // After-sale outstanding balance until the selected month
        $afterSaleOutstanding = BranchSaleOutstanding::where('date', '<=', $endDate)
            ->sum('outstanding_balance') - BranchSaleOutstanding::where('date', '<=', $endDate)
            ->sum('extra_money');

        // Cash as of the selected month
        $this->cashWas = $this->totalOutstandingBalance;
        $this->soFarCash = $this->totalCashIn + $this->cashWas;

        $this->outstandingTotal = $afterSaleOutstanding + $this->totalOutstandingBalance;

        // Calculate loan receivables (loans given minus payments received)
        $totalLoansGiven = Loan::where('date', '<=', $endDate)->sum('amount');
        $totalLoanPaymentsReceived = LoanPayment::where('date', '<=', $endDate)->sum('amount');
        $this->loanReceivables = $totalLoansGiven - $totalLoanPaymentsReceived;

        // Total bank or hand balance up until the selected month (including loan impact)
        $this->totalBankOrHandBalance = $this->calculateTotalBankOrHandBalance($endDate);

        // Total sets bought until the selected month
        $totalSetsBuy = Stock::where('date', '<=', $endDate)->sum('sets');
        $totalSetsBuyPrice = Stock::where('date', '<=', $endDate)->sum('total_price');

        // Average stamp price per set
        $averageStampPricePerSet = $totalSetsBuy > 0 ? $totalSetsBuyPrice / $totalSetsBuy : 0;

        $totalStampAvailable = TotalStock::sum('total_sets'); // This reflects the most recent stock

        // Total sets purchased during the selected month
        $setsPurchased = Stock::where('date', '<=', $endDate)->sum('sets');

        // Total sets sold (branch + head office) during the selected month
        $setsSoldBranch = BranchSale::where('date', '<=', $endDate)->sum('sets');
        $setsSoldHeadOffice = HeadOfficeSale::where('date', '<=', $endDate)->sum('sets');

        // Total rejected or free sets during the selected month
        $setsRejectedOrFree = RejectOrFree::where('date', '<=', $endDate)->sum('sets');

        // Calculate total stock at the end of the selected month
        $totalStockEndOfMonth = $setsPurchased    // Add purchases
            - ($setsSoldBranch + $setsSoldHeadOffice)  // Subtract sales
            - $setsRejectedOrFree;  // Subtract rejected/free sets

        // Calculate stock stamp buy price (total stock * average price per set)
        $this->stockStampBuyPrice = $totalStockEndOfMonth * $averageStampPricePerSet;

        // Total reject or free sets until the selected month
        $totalRejectOrFreeSets = RejectOrFree::where('date', '<=', $endDate)->sum('sets');
        $totalRejectOrFree = $totalRejectOrFreeSets * $averageStampPricePerSet;

        // Total sets sold (branch + head office) until the selected month
        $totalBranchSaleSets = BranchSale::where('date', '<=', $endDate)->sum('sets');
        $totalHeadOfficeSaleSets = HeadOfficeSale::where('date', '<=', $endDate)->sum('sets');
        $totalBranchSalePrice = BranchSale::where('date', '<=', $endDate)->sum('total_price');
        $totalHeadOfficeSalePrice = HeadOfficeSale::where('date', '<=', $endDate)->sum('total_price');

        // Sale set buy price (what the sets cost to acquire)
        $saleSetBuyPrice = ($totalBranchSaleSets + $totalHeadOfficeSaleSets) * $averageStampPricePerSet;

        // Total expenses until the selected month
        $totalExpense = Expense::where('date', '<=', $endDate)->sum('amount');

        // Total lose (expenses + reject/free + cost of sold sets)
        $totalLose = $totalRejectOrFree + $saleSetBuyPrice + $totalExpense;

        // Total sale revenue until the selected month
        $totalSale = $totalBranchSalePrice + $totalHeadOfficeSalePrice;

        // SoFarNetProfit is the total accumulated net profit until the selected month
        $soFarNetProfitAmount = SofarNetProfit::sum('amount'); // Keeping this without filter

        // Calculate net profit (total sale revenue - total expenses and losses)
        $netProfit = $totalSale - $totalLose + $soFarNetProfitAmount;

        // Store the computed values
        $this->netProfit = $netProfit;
    }

    private function calculateTotalBankOrHandBalance($endDate)
    {
        // Summing up the cash inflows and outflows up until the selected month
        $cashInTotal = Money::where('type', 'cash_in')
            ->whereDate('date', '<=', $endDate)
            ->sum('amount');

        $cashOutTotal = Money::where('type', 'cash_out')
            ->whereDate('date', '<=', $endDate)
            ->sum('amount');

        $totalPurchases = Stock::whereDate('date', '<=', $endDate)
            ->sum('total_price');

        $totalExpenses = Expense::whereDate('date', '<=', $endDate)
            ->sum('amount');

        $totalSalesReceipts = HeadOfficeSale::whereDate('date', '<=', $endDate)
            ->sum('cash') + BranchSale::whereDate('date', '<=', $endDate)
            ->sum('cash');

        // Add loan impact to balance calculation
        $totalLoansGiven = Loan::where('date', '<=', $endDate)->sum('amount');
        $totalLoanPaymentsReceived = LoanPayment::where('date', '<=', $endDate)->sum('amount');

        // Calculating the total balance up until the selected month (including loan impact)
        $totalBalance = $cashInTotal + $totalSalesReceipts + $totalLoanPaymentsReceived
            - $cashOutTotal - $totalPurchases - $totalExpenses - $totalLoansGiven;

        return $totalBalance;
    }

    public function downloadPdf()
    {
        $this->generateReport();

        $pdf = Pdf::loadView('pdf.balance-sheet-all', [
            'totalCashIn' => $this->totalCashIn,
            'cashWas' => $this->cashWas,
            'soFarCash' => $this->soFarCash,
            'totalBankOrHandBalance' => $this->totalBankOrHandBalance,
            'stockStampBuyPrice' => $this->stockStampBuyPrice,
            'outstandingTotal' => $this->outstandingTotal,
            'loanReceivables' => $this->loanReceivables,
            'netProfit' => $this->netProfit,
            'month' => (int)$this->selectedMonth,
            'year' => (int)$this->selectedYear,
        ])->setPaper('a4')->output();

        $base64 = base64_encode($pdf);
        $this->dispatch('openPdfInNewTab', base64: $base64, filename: 'balance-sheet-all.pdf');
    }

    public function render()
    {
        return view('livewire.balance-sheet-all', [
            'availableYears' => range(now()->year - 5, now()->year),
            'availableMonths' => [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ]
        ]);
    }
}
