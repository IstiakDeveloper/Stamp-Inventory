<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\BranchPrice;
use Livewire\Component;

class SetBranchPrice extends Component
{
    public $price;

    protected $rules = [
        'price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->price = BranchPrice::first()->price ?? 0; // Initialize with the first branch's price if available
    }

    
    public function render()
    {
        return view('livewire.set-branch-price');
    }

    public function savePrice()
    {
        $validatedData = $this->validate();

        BranchPrice::query()->updateOrCreate([], ['price' => $this->price]);

        sweetalert()->success('Price updated or created for all branches successfully.');
    }
}
