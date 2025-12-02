<?php

namespace App\Livewire;

use App\Models\Balance;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Stock;
use App\Models\TotalStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockComponent extends Component
{
    use WithPagination;

    public $date;
    public $address;
    public $sets;
    public $pieces;
    public $price_per_set;
    public $price_per_piece;
    public $total_price;
    public $note;
    public $stockId;
    public $totalSet;
    public $totalPcs;

    // Pagination and filtering properties
    public $perPage = 10;
    public $searchDate = '';
    public $searchAddress = '';
    public $sortBy = 'date';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'address' => 'required|string',
        'sets' => 'required|integer|min:0',
        'price_per_set' => 'required|numeric|min:0',
        'note' => 'nullable|string',
    ];

    public function mount()
    {
        $this->resetInputFields();
        $this->loadTotalStock();
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Build query with filters and pagination
        $query = Stock::query()
            ->when($this->searchDate, function ($q) {
                return $q->whereDate('date', $this->searchDate);
            })
            ->when($this->searchAddress, function ($q) {
                return $q->where('address', 'like', '%' . $this->searchAddress . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $stocks = $query->paginate($this->perPage);

        // Get all stocks for summary calculations
        $allStocks = Stock::all();

        return view('livewire.stock-component', [
            'stocks' => $stocks,
            'allStocks' => $allStocks,
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
        $this->searchAddress = '';
        $this->resetPage();
    }

    // Reset pagination when filters change
    public function updatingSearchDate()
    {
        $this->resetPage();
    }

    public function updatingSearchAddress()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function loadTotalStock()
    {
        $this->totalSet = TotalStock::sum('total_sets');
        $this->totalPcs = TotalStock::sum('total_pieces');
    }

    public function resetInputFields()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->address = '';
        $this->sets = '';
        $this->pieces = '';
        $this->price_per_set = '';
        $this->price_per_piece = '';
        $this->total_price = '';
        $this->note = '';
        $this->stockId = null;
    }

    public function store()
    {
        $validatedData = $this->validate();

        $pieces_per_set = 3;
        $pieces = $validatedData['sets'] * $pieces_per_set;
        $price_per_piece = $validatedData['price_per_set'] / $pieces_per_set;
        $total_price = $validatedData['price_per_set'] * $validatedData['sets'];

        $stock = Stock::create([
            'date' => $this->date,
            'address' => $this->address,
            'sets' => $this->sets,
            'pieces' => $pieces,
            'price_per_set' => $this->price_per_set,
            'price_per_piece' => $price_per_piece,
            'total_price' => $total_price,
            'note' => $this->note,
        ]);

        $totalStock = DB::table('total_stocks')
            ->update([
                'total_sets' => DB::raw("COALESCE(total_sets, 0) + " . $stock->sets),
                'total_pieces' => DB::raw("COALESCE(total_pieces, 0) + " . $pieces)
            ]);

        // Update the Balance
        Balance::updateOrCreate(
            [], // Assuming there's only one row for total_balance
            ['total_balance' => DB::raw("total_balance - $total_price")]
        );

        sweetalert()->success('Stock added successfully.');
        $this->resetInputFields();
        $this->loadTotalStock();
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $this->stockId = $stock->id;
        $this->date = Carbon::parse($stock->date)->format('Y-m-d');
        $this->address = $stock->address;
        $this->sets = $stock->sets;
        $this->pieces = $stock->pieces;
        $this->price_per_set = $stock->price_per_set;
        $this->price_per_piece = $stock->price_per_piece;
        $this->total_price = $stock->total_price;
        $this->note = $stock->note;
    }

    public function update()
    {
        $validatedData = $this->validate();

        // Compute derived values
        $pieces = $validatedData['sets'] * 3;
        $price_per_piece = $validatedData['price_per_set'] / 3;
        $total_price = $validatedData['sets'] * $validatedData['price_per_set'];

        // Find the existing stock record
        $stock = Stock::find($this->stockId);
        if (!$stock) {
            sweetalert()->error('Stock not found.');
            return;
        }

        // Calculate differences
        $oldSets = $stock->sets;
        $oldPieces = $stock->pieces;
        $oldTotalPrice = $stock->total_price;

        // Update the stock record
        $stock->update([
            'date' => $validatedData['date'],
            'address' => $validatedData['address'],
            'sets' => $validatedData['sets'],
            'pieces' => $pieces,
            'price_per_set' => $validatedData['price_per_set'],
            'price_per_piece' => $price_per_piece,
            'total_price' => $total_price,
            'note' => $validatedData['note'],
        ]);

        // Update TotalStock
        $totalStock = TotalStock::firstOrFail(); // Assuming there's only one row
        $totalStock->total_sets += ($validatedData['sets'] - $oldSets);
        $totalStock->total_pieces += ($pieces - $oldPieces);
        $totalStock->save();

        // Update balance with the difference
        $priceDifference = $total_price - $oldTotalPrice;
        Balance::updateOrCreate(
            [],
            ['total_balance' => DB::raw("total_balance - $priceDifference")]
        );

        sweetalert()->success('Stock updated successfully.');
        $this->resetInputFields();
        $this->loadTotalStock();
    }

    public function delete($id)
    {
        $stock = Stock::findOrFail($id);

        // Update the total balance
        Balance::updateOrCreate(
            [], // Assuming there's only one row for total_balance
            ['total_balance' => DB::raw("total_balance + $stock->total_price")]
        );

        // Update TotalStock
        $totalStock = TotalStock::firstOrFail(); // Assuming there's only one row
        $totalStock->total_sets -= $stock->sets;
        $totalStock->total_pieces -= $stock->pieces;
        $totalStock->save();

        // Delete the stock record
        $stock->delete();
        $this->loadTotalStock();
        sweetalert()->success('Stock deleted successfully.');
    }

    // Pagination navigation methods
    public function previousPage()
    {
        $this->setPage($this->getPage() - 1);
    }

    public function nextPage()
    {
        $this->setPage($this->getPage() + 1);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }
}
