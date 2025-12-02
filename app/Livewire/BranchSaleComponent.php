<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Branch;
use App\Models\BranchPrice;
use App\Models\BranchSale;
use App\Models\BranchSaleOutstanding;
use App\Models\OutstandingBalanceHistory;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination; // Add pagination trait

class BranchSaleComponent extends Component
{
    use WithPagination; // Use pagination trait

    public $branches;
    public $branch_id;
    public $date;
    public $sets;
    public $perSetPrice;
    public $totalPrice;
    public $cash;
    public $extraMoney;
    public $receiver_name;

    // Pagination and filtering properties
    public $perPage = 10; // Number of records per page
    public $searchBranch = ''; // Filter by branch
    public $searchDate = ''; // Filter by date
    public $sortBy = 'id'; // Sort column
    public $sortDirection = 'desc'; // Sort direction

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric|min:0.1',
        'cash' => 'required|numeric|min:0',
        'receiver_name' => 'required|string|max:255',
    ];

    protected $paginationTheme = 'bootstrap'; // Use Bootstrap theme for pagination

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->branches = Branch::all();
    }

    public function render()
    {
        // Calculate extra money if cash and total price are set and numeric
        if (!is_null($this->cash) && is_numeric($this->cash) && !is_null($this->totalPrice)) {
            $this->extraMoney = $this->cash - $this->totalPrice;
        } else {
            $this->extraMoney = null;
        }

        // Build query with filters and pagination
        $query = BranchSale::with('branch')
            ->when($this->searchBranch, function ($q) {
                return $q->whereHas('branch', function ($branch) {
                    $branch->where('branch_name', 'like', '%' . $this->searchBranch . '%');
                });
            })
            ->when($this->searchDate, function ($q) {
                return $q->whereDate('date', $this->searchDate);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $branchSales = $query->paginate($this->perPage);

        return view('livewire.branch-sale-component', [
            'branchSales' => $branchSales
        ]);
    }

    // Sort functionality
    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    // Clear filters
    public function clearFilters()
    {
        $this->searchBranch = '';
        $this->searchDate = '';
        $this->resetPage(); // Reset to first page
    }

    // Reset pagination when filters change
    public function updatingSearchBranch()
    {
        $this->resetPage();
    }

    public function updatingSearchDate()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSets()
    {
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        if ($this->sets) {
            $branchPrice = $this->getBranchPrice();
            if ($branchPrice) {
                $this->perSetPrice = $branchPrice->price;
                $this->totalPrice = $this->sets * $this->perSetPrice;
            } else {
                $this->perSetPrice = null;
                $this->totalPrice = null;
            }
        } else {
            $this->perSetPrice = null;
            $this->totalPrice = null;
        }
    }

    public function saveSale()
    {
        // Validate required fields
        $this->validate([
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'sets' => 'required|numeric|min:0',
            'perSetPrice' => 'nullable|numeric',
            'cash' => 'required|numeric|min:0',
            'receiver_name' => 'required|string|max:255',
        ]);

        // Begin transaction to ensure data consistency
        \DB::transaction(function () {
            // Set default values for totalPrice and perSetPrice if sets is 0
            if ($this->sets == 0) {
                $this->perSetPrice = 0;
                $this->totalPrice = 0;
            } else {
                $this->totalPrice = $this->sets * $this->perSetPrice;
            }

            // Calculate total amount and extra money
            $totalAmount = $this->totalPrice;
            $cashReceived = $this->cash;
            $extraMoney = $cashReceived - $totalAmount;

            // Calculate outstanding balance
            $outstandingBalance = $totalAmount - $cashReceived;
            $extraMoney = $cashReceived > $totalAmount ? $cashReceived - $totalAmount : 0;

            // Ensure outstanding balance is not negative
            if ($outstandingBalance < 0) {
                $outstandingBalance = 0;
            }

            // Create branch sale record with receiver_name
            $branchSale = BranchSale::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'sets' => $this->sets,
                'per_set_price' => $this->perSetPrice,
                'total_price' => $this->totalPrice,
                'cash' => $this->cash,
                'receiver_name' => $this->receiver_name,
            ]);

            // Record payment
            Payment::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'amount' => $this->cash,
            ]);

            $piecesSold = $this->sets * 3;

            // Update stock
            $totalStock = TotalStock::find(1);
            $totalStock->decrement('total_sets', $this->sets);
            $totalStock->decrement('total_pieces', $piecesSold);

            // Update balance
            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance + $this->cash]);

            // Update branch outstanding_balance based on extra money
            $branch = Branch::find($this->branch_id);

            BranchSaleOutstanding::create([
                'branch_sale_id' => $branchSale->id,
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'outstanding_balance' => $outstandingBalance,
                'extra_money' => $extraMoney,
            ]);

            $saleDate = Carbon::parse($this->date);

            // Create a new record for OutstandingBalanceHistory
            $outstandingBalanceHistory = new OutstandingBalanceHistory();
            $outstandingBalanceHistory->branch_id = $this->branch_id;
            $outstandingBalanceHistory->date = $saleDate->format('Y-m-d');

            // Determine the value for outstanding_balance
            if (isset($this->outstandingBalance)) {
                $outstandingBalanceHistory->outstanding_balance = $branch->outstanding_balance + $this->outstandingBalance;
            } elseif (isset($this->extraMoney)) {
                $lastOutstandingBalance = OutstandingBalanceHistory::where('branch_id', $this->branch_id)
                    ->orderBy('date', 'desc')
                    ->value('outstanding_balance');

                if (is_null($lastOutstandingBalance)) {
                    $lastOutstandingBalance = $branch->outstanding_balance;
                }

                $outstandingBalanceHistory->outstanding_balance = $lastOutstandingBalance - $this->extraMoney;
            } else {
                $outstandingBalanceHistory->outstanding_balance = $branch->outstanding_balance;
            }

            // Save the record
            $outstandingBalanceHistory->save();

            if ($extraMoney > 0) {
                sweetalert()->success('Extra cash received: ' . number_format($extraMoney, 2));
            } elseif ($extraMoney < 0) {
                sweetalert()->success('Due amount received: ' . number_format(-$extraMoney, 2));
            } else {
                sweetalert()->success('Exact amount received (no extra or due)');
            }
        });

        // Reset input fields
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->branch_id = null;
        $this->date = Carbon::today()->format('Y-m-d');
        $this->sets = null;
        $this->perSetPrice = null;
        $this->totalPrice = null;
        $this->cash = null;
        $this->receiver_name = null;
    }

    public function deleteSale($branchSaleId)
    {
        // Begin transaction to ensure data consistency
        \DB::transaction(function () use ($branchSaleId) {
            // Retrieve the BranchSale record
            $branchSale = BranchSale::findOrFail($branchSaleId);

            // Retrieve the associated Payment record, if it exists
            $payment = Payment::where('branch_id', $branchSale->branch_id)
                ->where('date', $branchSale->date)
                ->first();

            // Reverse the stock update
            $piecesSold = $branchSale->sets * 3;
            $totalStock = TotalStock::find(1);
            $totalStock->increment('total_sets', $branchSale->sets);
            $totalStock->increment('total_pieces', $piecesSold);

            // Reverse the balance update if the payment exists
            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance - $branchSale->cash]);

            // Delete BranchOutstanding record
            BranchSaleOutstanding::where('branch_sale_id', $branchSale->id)->delete();

            // Delete OutstandingBalanceHistory record
            OutstandingBalanceHistory::where('branch_id', $branchSale->branch_id)
                ->where('date', $branchSale->date)
                ->delete();

            // Delete the BranchSale record
            $branchSale->delete();

            // Success message
            session()->flash('message', 'Sale deleted successfully!');
        });
    }

    private function getBranchPrice()
    {
        return BranchPrice::first();
    }
}
