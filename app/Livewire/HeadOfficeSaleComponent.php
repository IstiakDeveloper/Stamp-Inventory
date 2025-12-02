<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\HeadOfficeDue;
use App\Models\HeadOfficeSale;
use App\Models\Payment;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class HeadOfficeSaleComponent extends Component
{
    use WithPagination;

    // Form properties
    public $date;
    public $sets;
    public $per_stamp_price;
    public $cash;
    public $note;

    // Pagination and filtering properties
    public $perPage = 10;
    public $searchDate = '';
    public $sortBy = 'id';
    public $sortDirection = 'desc';

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'sets' => 'required|numeric',
        'per_stamp_price' => 'required|numeric|min:0',
        'cash' => 'required|numeric|min:0',
        'note' => 'nullable|string',
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Build query with filters and pagination
        $query = HeadOfficeSale::with('headOfficeDue')
            ->when($this->searchDate, function ($q) {
                return $q->whereDate('date', $this->searchDate);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $transactions = $query->paginate($this->perPage);

        // Calculate summary stats for all records (not just current page)
        $allTransactions = HeadOfficeSale::all();

        return view('livewire.head-office-sale-component', [
            'transactions' => $transactions,
            'totalPrice' => $this->calculateTotalPrice(),
            'allTransactions' => $allTransactions, // For summary cards
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
        $this->searchDate = '';
        $this->resetPage();
    }

    // Reset pagination when filters change
    public function updatingSearchDate()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function saveSale()
    {
        $this->validate();

        // Calculate total price based on sets and per_stamp_price
        $totalPrice = $this->sets * $this->per_stamp_price;

        // Calculate the total due amount
        $totalDue = HeadOfficeDue::sum('due_amount');

        if ($this->sets == 0 && $this->per_stamp_price == 0) {
            // Create a new Head Office Sale record with all zero values except cash
            $headOfficeSale = HeadOfficeSale::create([
                'date' => $this->date,
                'sets' => 0,
                'per_set_price' => 0,
                'total_price' => 0,
                'cash' => $this->cash,
                'note' => $this->note,
            ]);

            // Calculate the remaining due amount after subtracting the cash payment
            $remainingDue = $totalDue - $this->cash;

            // Delete existing dues
            HeadOfficeDue::truncate(); // Clears all existing dues

            // Create a new record with the remaining due amount (if necessary)
            if ($remainingDue > 0) {
                HeadOfficeDue::create([
                    'head_office_sale_id' => $headOfficeSale->id,
                    'date' => $this->date,
                    'due_amount' => $remainingDue,
                    'note' => $this->note,
                ]);
            } else {
                // Optionally create a record with zero due amount
                HeadOfficeDue::create([
                    'head_office_sale_id' => $headOfficeSale->id,
                    'date' => $this->date,
                    'due_amount' => 0,
                    'note' => $this->note,
                ]);
            }

            // Record payment
            Payment::create([
                'date' => $this->date,
                'amount' => $this->cash,
            ]);

            // Update the balance
            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance + $this->cash]);

            sweetalert()->success('Due amount adjusted and cash received recorded successfully');
        } else {
            // Create Head Office Sale record with actual sets and prices
            $headOfficeSale = HeadOfficeSale::create([
                'date' => $this->date,
                'sets' => $this->sets,
                'per_set_price' => $this->per_stamp_price,
                'total_price' => $totalPrice,
                'cash' => $this->cash,
                'note' => $this->note,
            ]);

            // Record payment
            Payment::create([
                'date' => $this->date,
                'amount' => $this->cash,
            ]);

            // Check if there's any due
            if ($this->cash < $totalPrice) {
                // Calculate due amount
                $dueAmount = $totalPrice - $this->cash;

                // Create a due record linked to the sale
                HeadOfficeDue::create([
                    'head_office_sale_id' => $headOfficeSale->id,
                    'date' => $this->date,
                    'due_amount' => $dueAmount,
                    'note' => $this->note,
                ]);
            }

            // Update total stock and balance
            $piecesSold = $this->sets * 3;
            $totalStock = TotalStock::find(1);
            $totalStock->decrement('total_sets', $this->sets);
            $totalStock->decrement('total_pieces', $piecesSold);

            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance + $this->cash]);

            sweetalert()->success('Head office sale recorded successfully');
        }

        // Reset input fields
        $this->resetInputFields();
    }

    public function deleteSale($saleId)
    {
        // Begin transaction to ensure data consistency
        \DB::transaction(function () use ($saleId) {
            // Find the sale record
            $sale = HeadOfficeSale::findOrFail($saleId);

            // Adjust the balance by subtracting the cash amount of the sale
            $balance = Balance::first();
            $balance->update(['total_balance' => $balance->total_balance - $sale->cash]);

            // Check and remove any associated due records
            if ($sale->headOfficeDue) {
                $sale->headOfficeDue->delete();
            }

            // Recalculate total stock (if necessary)
            $piecesSold = $sale->sets * 3;
            $totalStock = TotalStock::find(1);
            $totalStock->increment('total_sets', $sale->sets);
            $totalStock->increment('total_pieces', $piecesSold);

            // Remove the sale record
            $sale->delete();

            // Show a success message
            session()->flash('message', 'Sale deleted successfully!');
        });
    }

    private function resetInputFields()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->sets = null;
        $this->per_stamp_price = null;
        $this->cash = null;
        $this->note = null;
    }

    public function calculateTotalPrice()
    {
        if (!is_null($this->sets) && !is_null($this->per_stamp_price)) {
            return $this->sets * $this->per_stamp_price;
        }

        return null;
    }
}
