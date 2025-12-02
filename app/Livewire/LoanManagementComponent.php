<?php

namespace App\Livewire;

use App\Models\Borrower;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Balance;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class LoanManagementComponent extends Component
{
    use WithPagination;

    // Borrower form properties
    public $borrower_name;
    public $borrower_phone;
    public $borrower_address;
    public $borrower_note;

    // Loan form properties
    public $selected_borrower_id;
    public $loan_date;
    public $loan_amount;
    public $loan_purpose;
    public $loan_note;

    // Payment form properties
    public $payment_borrower_id;
    public $payment_date;
    public $payment_amount;
    public $payment_note;

    // Edit properties
    public $editingBorrowerId;

    // Filter properties
    public $searchName = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->loan_date = Carbon::today()->format('Y-m-d');
        $this->payment_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Get borrowers with pagination and search
        $borrowers = Borrower::when($this->searchName, function ($query) {
            return $query->where('name', 'like', '%' . $this->searchName . '%');
        })
        ->orderBy('name')
        ->paginate($this->perPage);

        // Get all borrowers for dropdowns
        $allBorrowers = Borrower::orderBy('name')->get();

        // Get recent loans and payments for dashboard
        $recentLoans = Loan::with('borrower')->latest()->limit(5)->get();
        $recentPayments = LoanPayment::with('borrower')->latest()->limit(5)->get();

        // Calculate totals
        $totalLoanAmount = Loan::sum('amount');
        $totalPaidAmount = LoanPayment::sum('amount');
        $totalOutstanding = $totalLoanAmount - $totalPaidAmount;

        return view('livewire.loan-management-component', [
            'borrowers' => $borrowers,
            'allBorrowers' => $allBorrowers,
            'recentLoans' => $recentLoans,
            'recentPayments' => $recentPayments,
            'totalLoanAmount' => $totalLoanAmount,
            'totalPaidAmount' => $totalPaidAmount,
            'totalOutstanding' => $totalOutstanding,
        ]);
    }

    // Borrower management methods
    public function addBorrower()
    {
        $this->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_phone' => 'nullable|string|max:15',
            'borrower_address' => 'nullable|string',
            'borrower_note' => 'nullable|string',
        ]);

        Borrower::create([
            'name' => $this->borrower_name,
            'phone' => $this->borrower_phone,
            'address' => $this->borrower_address,
            'note' => $this->borrower_note,
        ]);

        $this->resetBorrowerForm();
        sweetalert()->success('Borrower added successfully!');
    }

    public function editBorrower($id)
    {
        $borrower = Borrower::findOrFail($id);
        $this->editingBorrowerId = $id;
        $this->borrower_name = $borrower->name;
        $this->borrower_phone = $borrower->phone;
        $this->borrower_address = $borrower->address;
        $this->borrower_note = $borrower->note;
    }

    public function updateBorrower()
    {
        $this->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_phone' => 'nullable|string|max:15',
            'borrower_address' => 'nullable|string',
            'borrower_note' => 'nullable|string',
        ]);

        $borrower = Borrower::findOrFail($this->editingBorrowerId);
        $borrower->update([
            'name' => $this->borrower_name,
            'phone' => $this->borrower_phone,
            'address' => $this->borrower_address,
            'note' => $this->borrower_note,
        ]);

        $this->resetBorrowerForm();
        sweetalert()->success('Borrower updated successfully!');
    }

    public function deleteBorrower($id)
    {
        $borrower = Borrower::findOrFail($id);

        // Check if borrower has any outstanding loans
        if ($borrower->remaining_balance > 0) {
            sweetalert()->error('Cannot delete borrower with outstanding balance!');
            return;
        }

        $borrower->delete();
        sweetalert()->success('Borrower deleted successfully!');
    }

    // Loan management methods
    public function giveLoan()
    {
        $this->validate([
            'selected_borrower_id' => 'required|exists:borrowers,id',
            'loan_date' => 'required|date',
            'loan_amount' => 'required|numeric|min:1',
            'loan_purpose' => 'nullable|string',
            'loan_note' => 'nullable|string',
        ]);

        DB::transaction(function () {
            // Create loan record
            Loan::create([
                'borrower_id' => $this->selected_borrower_id,
                'date' => $this->loan_date,
                'amount' => $this->loan_amount,
                'purpose' => $this->loan_purpose,
                'note' => $this->loan_note,
            ]);

            // Update balance (subtract loan amount)
            $balance = Balance::first();
            if ($balance) {
                $balance->decrement('total_balance', $this->loan_amount);
            }
        });

        $this->resetLoanForm();
        sweetalert()->success('Loan given successfully!');
    }

    // Payment management methods
    public function receivePayment()
    {
        $this->validate([
            'payment_borrower_id' => 'required|exists:borrowers,id',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:1',
            'payment_note' => 'nullable|string',
        ]);

        $borrower = Borrower::findOrFail($this->payment_borrower_id);

        // Check if payment amount doesn't exceed outstanding balance
        if ($this->payment_amount > $borrower->remaining_balance) {
            sweetalert()->error('Payment amount cannot exceed outstanding balance of ৳' . number_format($borrower->remaining_balance, 2));
            return;
        }

        DB::transaction(function () {
            // Create payment record
            LoanPayment::create([
                'borrower_id' => $this->payment_borrower_id,
                'date' => $this->payment_date,
                'amount' => $this->payment_amount,
                'note' => $this->payment_note,
            ]);

            // Update balance (add payment amount)
            $balance = Balance::first();
            if ($balance) {
                $balance->increment('total_balance', $this->payment_amount);
            }
        });

        $this->resetPaymentForm();
        sweetalert()->success('Payment received successfully!');
    }

    // Reset form methods
    public function resetBorrowerForm()
    {
        $this->borrower_name = '';
        $this->borrower_phone = '';
        $this->borrower_address = '';
        $this->borrower_note = '';
        $this->editingBorrowerId = null;
    }

    public function resetLoanForm()
    {
        $this->selected_borrower_id = '';
        $this->loan_date = Carbon::today()->format('Y-m-d');
        $this->loan_amount = '';
        $this->loan_purpose = '';
        $this->loan_note = '';
    }

    public function resetPaymentForm()
    {
        $this->payment_borrower_id = '';
        $this->payment_date = Carbon::today()->format('Y-m-d');
        $this->payment_amount = '';
        $this->payment_note = '';
    }

    // Filter methods
    public function updatingSearchName()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchName = '';
        $this->resetPage();
    }
}
