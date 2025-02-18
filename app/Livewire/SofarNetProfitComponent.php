<?php

namespace App\Livewire;

use App\Models\SofarNetProfit;
use Livewire\Component;

class SofarNetProfitComponent extends Component
{

    public $sofarNetProfits;
    public $sofarNetProfitId;
    public $date;
    public $amount;

    public function mount()
    {
        $this->loadSofarNetProfits();
    }

    public function loadSofarNetProfits()
    {
        $this->sofarNetProfits = SofarNetProfit::all();
    }

    public function resetForm()
    {
        $this->sofarNetProfitId = null;
        $this->date = '';
        $this->amount = '';
    }

    public function edit($id)
    {
        $sofarNetProfit = SofarNetProfit::findOrFail($id);
        $this->sofarNetProfitId = $sofarNetProfit->id;
        $this->date = $sofarNetProfit->date;
        $this->amount = $sofarNetProfit->amount;
    }

    public function save()
    {
        $validatedData = $this->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        SofarNetProfit::updateOrCreate(
            ['id' => $this->sofarNetProfitId],
            $validatedData
        );

        session()->flash('message', $this->sofarNetProfitId ? 'Net Profit Updated Successfully.' : 'Net Profit Created Successfully.');

        $this->resetForm();
        $this->loadSofarNetProfits();
    }

    public function delete($id)
    {
        SofarNetProfit::findOrFail($id)->delete();
        session()->flash('message', 'Net Profit Deleted Successfully.');
        $this->loadSofarNetProfits();
    }

    public function render()
    {
        return view('livewire.sofar-net-profit-component');
    }
}
