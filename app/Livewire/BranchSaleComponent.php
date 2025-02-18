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

class BranchSaleComponent extends Component
{
    public $branches;
    public $branch_id;
    public $date;
    public $sets;
    public $perSetPrice;
    public $totalPrice;
    public $cash;
    public $extraMoney;
    public $branchSales;

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric|min:0.1',
        'cash' => 'required|numeric|min:0',
    ];


    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->branches = Branch::all();
        $branchSale = BranchSale::with('branch')->latest()->get();
        if ($branchSale) {
            $this->branchSales = $branchSale;
        }
    }

    public function render()
    {
        // Calculate extra money if cash and total price are set and numeric
        if (!is_null($this->cash) && is_numeric($this->cash) && !is_null($this->totalPrice)) {
            $this->extraMoney = $this->cash - $this->totalPrice;
        } else {
            $this->extraMoney = null;
        }

        return view('livewire.branch-sale-component');
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
    
            // Create branch sale record
            $branchSale = BranchSale::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'sets' => $this->sets,
                'per_set_price' => $this->perSetPrice,
                'total_price' => $this->totalPrice,
                'cash' => $this->cash,
            ]);
    
            // Record payment
            Payment::create([
                'branch_id' => $this->branch_id,
                'date' => $this->date,
                'amount' => $this->cash,
            ]);
    
            $piecesSold = $this->sets * 3;
    
            // Update stock (example decrement, adjust based on your stock logic)
            $totalStock = TotalStock::find(1); // Assuming you want to update the row with id = 1
            $totalStock->decrement('total_sets', $this->sets);
            $totalStock->decrement('total_pieces', $piecesSold);
    
            // Update balance (example update, adjust based on your balance logic)
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
                // If $outstandingBalance is set, add it to the branch's outstanding balance
                $outstandingBalanceHistory->outstanding_balance = $branch->outstanding_balance + $this->outstandingBalance;
            } elseif (isset($this->extraMoney)) {
                // If $outstandingBalance is not set but $extraMoney is, subtract it from the last outstanding balance
                // Get the last recorded outstanding balance
                $lastOutstandingBalance = OutstandingBalanceHistory::where('branch_id', $this->branch_id)
                    ->orderBy('date', 'desc')
                    ->value('outstanding_balance');
    
                // If no history found, use the branch's initial outstanding balance
                if (is_null($lastOutstandingBalance)) {
                    $lastOutstandingBalance = $branch->outstanding_balance;
                }
    
                // Subtract $extraMoney from the last outstanding balance
                $outstandingBalanceHistory->outstanding_balance = $lastOutstandingBalance - $this->extraMoney;
            } else {
                // Default case if neither $outstandingBalance nor $extraMoney is set
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
            $totalStock = TotalStock::find(1); // Assuming you want to update the row with id = 1
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
    
            // Optionally, send a success flash message
            flash()->success('Sale and all related data have been successfully deleted.');
        });
    }
    

    private function getBranchPrice()
    {
        return BranchPrice::first();
    }

}
