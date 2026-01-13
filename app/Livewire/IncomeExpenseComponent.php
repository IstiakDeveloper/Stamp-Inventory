<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Livewire\Component;

class IncomeExpenseComponent extends Component
{
    public $activeTab = 'expense'; // Default tab

    // Expense Properties
    public $expenseDate;
    public $expenseAmount;
    public $expensePurpose;

    // Income Properties
    public $incomeDate;
    public $incomeAmount;
    public $incomeSource;
    public $incomeType = 'other';

    protected function rules()
    {
        if ($this->activeTab === 'expense') {
            return [
                'expenseDate' => 'required|date_format:Y-m-d',
                'expenseAmount' => 'required|numeric|min:0',
                'expensePurpose' => 'required|string|max:255',
            ];
        } else {
            return [
                'incomeDate' => 'required|date_format:Y-m-d',
                'incomeAmount' => 'required|numeric|min:0',
                'incomeSource' => 'required|string|max:255',
                'incomeType' => 'required|in:extra,other,commission,bonus,refund,bank_interest',
            ];
        }
    }

    public function mount() {
        $this->expenseDate = Carbon::today()->format('Y-m-d');
        $this->incomeDate = Carbon::today()->format('Y-m-d');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
    }

    public function saveExpense()
    {
        $this->validate();

        Expense::create([
            'date' => $this->expenseDate,
            'amount' => $this->expenseAmount,
            'purpose' => $this->expensePurpose,
        ]);

        $balance = Balance::first();
        $balance->decrement('total_balance', $this->expenseAmount);

        sweetalert()->success('Expense recorded successfully.');
        $this->resetExpenseFields();
    }

    public function saveIncome()
    {
        $this->validate();

        Income::create([
            'date' => $this->incomeDate,
            'amount' => $this->incomeAmount,
            'source' => $this->incomeSource,
            'type' => $this->incomeType,
        ]);

        $balance = Balance::first();
        $balance->increment('total_balance', $this->incomeAmount);

        sweetalert()->success('Income recorded successfully.');
        $this->resetIncomeFields();
    }

    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $balance = Balance::first();
        $balance->increment('total_balance', $expense->amount);
        $expense->delete();
        sweetalert()->success('Expense deleted successfully.');
    }

    public function deleteIncome($id)
    {
        $income = Income::findOrFail($id);
        $balance = Balance::first();
        $balance->decrement('total_balance', $income->amount);
        $income->delete();
        sweetalert()->success('Income deleted successfully.');
    }

    private function resetExpenseFields()
    {
        $this->expenseDate = Carbon::today()->format('Y-m-d');
        $this->expenseAmount = null;
        $this->expensePurpose = null;
    }

    private function resetIncomeFields()
    {
        $this->incomeDate = Carbon::today()->format('Y-m-d');
        $this->incomeAmount = null;
        $this->incomeSource = null;
        $this->incomeType = 'other';
    }

    public function render()
    {
        $expenses = Expense::latest()->get();
        $incomes = Income::latest()->get();

        return view('livewire.income-expense-component', [
            'expenses' => $expenses,
            'incomes' => $incomes,
        ]);
    }
}
