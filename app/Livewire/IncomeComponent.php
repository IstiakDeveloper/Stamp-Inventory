<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Income;
use Carbon\Carbon;
use Livewire\Component;

class IncomeComponent extends Component
{
    public $date;
    public $amount;
    public $source;
    public $type = 'other'; // Default type

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric|min:0',
        'source' => 'required|string|max:255',
        'type' => 'required|in:extra,other,commission,bonus,refund,bank_interest',
    ];

    public function mount() {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function saveIncome()
    {
        $this->validate();

        // Create a new Income record
        Income::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'source' => $this->source,
            'type' => $this->type,
        ]);

        // Increase the main balance by the income amount
        $balance = Balance::first();
        $balance->increment('total_balance', $this->amount);

        sweetalert()->success('Income recorded successfully.');

        // Reset input fields
        $this->resetInputFields();
    }

    public function deleteIncome($id)
    {
        $income = Income::findOrFail($id);
        $balance = Balance::first();

        // Decrease the main balance by the amount of the deleted income
        $balance->decrement('total_balance', $income->amount);

        $income->delete();

        sweetalert()->success('Income deleted successfully.');
    }

    private function resetInputFields()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->amount = null;
        $this->source = null;
        $this->type = 'other';
    }

    public function render()
    {
        $incomes = Income::latest()->get();
        return view('livewire.income-component', [
            'incomes' => $incomes,
        ]);
    }
}
