<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseComponent extends Component
{
    public $date;
    public $amount;
    public $purpose;

    protected $rules = [
        'date' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric|min:0',
        'purpose' => 'required|string|max:255',
    ];

    public function mount() {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function saveExpense()
    {
        $this->validate();

        // Create a new Expense record
        Expense::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'purpose' => $this->purpose,
        ]);

        // Decrease the main balance by the expense amount
        $balance = Balance::first();
        $balance->decrement('total_balance', $this->amount);
        
        sweetalert()->success('Expense recorded successfully.)');

        // Reset input fields
        $this->resetInputFields();
    }
    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $balance = Balance::first();

        // Increase the main balance by the amount of the deleted expense
        $balance->increment('total_balance', $expense->amount);

        $expense->delete();

        sweetalert()->success('Expense deleted successfully.');
    }

    private function resetInputFields()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->amount = null;
        $this->purpose = null;
    }

    public function render()
    {
        $expenses = Expense::latest()->get();
        return view('livewire.expense-component', [
            'expenses' => $expenses,
        ]);
    }

}
