<?php

namespace App\Livewire;

use App\Models\FundManagement;
use App\Models\TotalFund;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Component;

class FundManagementComponent extends Component
{
    public $date;
    public $amount;
    public $type;
    public $note;
    public $editMode = false;
    public $fundId;

    public function mount()
    {
        $this->initializeTotalFund();
        $this->resetInput();
    }

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'type' => ['required', ValidationRule::in(['fund_in', 'fund_out'])],
            'note' => 'nullable|string|max:255',
        ];
    }

    public function submit()
    {
        $this->validate($this->rules());

        $fund = FundManagement::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
        ]);

        $this->updateTotalFund($fund);

        $this->resetInput();
        session()->flash('message', 'Fund recorded successfully!');
    }

    private function initializeTotalFund()
    {
        if (!TotalFund::exists()) {
            TotalFund::create(['total_fund' => 0]);
        }
    }

    private function updateTotalFund($fund)
    {
        $totalFund = TotalFund::first();

        if ($fund->type == 'fund_in') {
            $totalFund->total_fund += $fund->amount;
        } else {
            $totalFund->total_fund -= $fund->amount;
        }

        $totalFund->save();
    }

    private function adjustTotalFund($fund, $previousAmount, $previousType)
    {
        $totalFund = TotalFund::first();

        if ($previousType == 'fund_in') {
            $totalFund->total_fund -= $previousAmount;
        } else {
            $totalFund->total_fund += $previousAmount;
        }

        if ($fund->type == 'fund_in') {
            $totalFund->total_fund += $fund->amount;
        } else {
            $totalFund->total_fund -= $fund->amount;
        }

        $totalFund->save();
    }

    public function edit($id)
    {
        $fund = FundManagement::findOrFail($id);
        $this->fundId = $fund->id;
        $this->date = $fund->date;
        $this->amount = $fund->amount;
        $this->type = $fund->type;
        $this->note = $fund->note;
        $this->editMode = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $fund = FundManagement::findOrFail($this->fundId);
        $previousAmount = $fund->amount;
        $previousType = $fund->type;

        $fund->update([
            'date' => $this->date,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
        ]);

        $this->adjustTotalFund($fund, $previousAmount, $previousType);

        $this->resetInput();
        $this->editMode = false;
        session()->flash('message', 'Fund updated successfully!');
    }

    public function delete($id)
    {
        $fund = FundManagement::findOrFail($id);

        // Adjust the total fund before deleting
        $this->adjustTotalFundOnDelete($fund);

        $fund->delete();

        session()->flash('message', 'Fund deleted successfully!');
    }

    private function adjustTotalFundOnDelete($fund)
    {
        $totalFund = TotalFund::first();

        if ($fund->type == 'fund_in') {
            $totalFund->total_fund -= $fund->amount;
        } else {
            $totalFund->total_fund += $fund->amount;
        }

        $totalFund->save();
    }

    private function resetInput()
    {
        $this->date = now()->toDateString();
        $this->amount = null;
        $this->type = null;
        $this->note = null; // Reset note field
    }

    public function render()
    {
        $totalFund = TotalFund::first()->total_fund;
        $funds = FundManagement::orderBy('date', 'desc')->get();

        return view('livewire.fund-management-component', [
            'totalFund' => $totalFund,
            'funds' => $funds,
        ]);
    }

}
