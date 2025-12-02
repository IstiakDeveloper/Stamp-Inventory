<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\OutstandingBalanceHistory;
use Livewire\Component;

class BranchComponent extends Component
{
    public $branches;
    public $branch_code;
    public $branch_name;
    public $outstanding_balance;
    public $branchId;
    public $isEditMode = false; // Added this property to handle edit mode

    protected $rules = [
        'branch_code' => 'required|string|unique:branches,branch_code',
        'branch_name' => 'required|string',
        'outstanding_balance' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->resetInputFields();
        $this->loadBranches();
    }

    public function render()
    {
        return view('livewire.branch-component');
    }

    public function loadBranches()
    {
        $this->branches = Branch::all();
    }

    public function resetInputFields()
    {
        $this->branch_code = '';
        $this->branch_name = '';
        $this->outstanding_balance = 0;
        $this->branchId = null;
        $this->isEditMode = false; // Reset edit mode
    }

    public function store()
    {
        $validatedData = $this->validate();

        $branch = Branch::create([
            'branch_code' => $this->branch_code,
            'branch_name' => $this->branch_name,
            'outstanding_balance' => $this->outstanding_balance,
        ]);

        OutstandingBalanceHistory::create([
            'branch_id' => $branch->id,
            'date' => now(),
            'outstanding_balance' => $this->outstanding_balance,
        ]);

        sweetalert()->success('Branch added successfully.');
        $this->resetInputFields();
        $this->loadBranches();
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branchId = $branch->id;
        $this->branch_code = $branch->branch_code;
        $this->branch_name = $branch->branch_name;
        $this->outstanding_balance = $branch->outstanding_balance;
        $this->isEditMode = true; // Set edit mode
    }

    public function update()
    {
        $validatedData = $this->validate([
            'branch_code' => 'required|string|unique:branches,branch_code,' . $this->branchId,
            'branch_name' => 'required|string',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        $branch = Branch::find($this->branchId);
        $branch->update([
            'branch_code' => $this->branch_code,
            'branch_name' => $this->branch_name,
            'outstanding_balance' => $this->outstanding_balance,
        ]);

        sweetalert()->success('Branch updated successfully.');
        $this->resetInputFields();
        $this->loadBranches();
    }

    public function save()
    {
        if ($this->isEditMode) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function delete($id)
    {
        Branch::find($id)->delete();
        sweetalert()->success('Branch deleted successfully.');
        $this->loadBranches();
    }
}
