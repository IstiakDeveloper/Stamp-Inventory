<?php

use App\Livewire\Actions\Logout;
use App\Livewire\BalanceSheetAll;
use App\Livewire\BalanceSheetComponent;
use App\Livewire\BranchComponent;
use App\Livewire\BranchSaleComponent;
use App\Livewire\Dashboard;
use App\Livewire\ExpenseComponent;
use App\Livewire\FundManagementComponent;
use App\Livewire\HeadOfficeSaleComponent;
use App\Livewire\LoanManagementComponent;
use App\Livewire\LoginComponent;
use App\Livewire\MoneyComponent;
use App\Livewire\RejectOrFreeComponent;
use App\Livewire\Report\AllBranchReportComponent;
use App\Livewire\Report\BankBalanceReport;
use App\Livewire\Report\BranchSaleReportComponent;
use App\Livewire\Report\ExpenseReportComponent;
use App\Livewire\Report\FundManagementReportComponent;
use App\Livewire\Report\HeadOfficeSaleReportComponent;
use App\Livewire\Report\MoneyReport;
use App\Livewire\Report\PurchaseReport;
use App\Livewire\Report\ReceiptPaymentReportComponent;
use App\Livewire\Report\RejectOrFreeReportComponent;
use App\Livewire\Report\StockRegisterReportComponent;
use App\Livewire\Report\TransectionReport;
use App\Livewire\SetBranchPrice;
use App\Livewire\SofarNetProfitComponent;
use App\Livewire\StockComponent;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;



Route::get('/migrate', function () {
    Artisan::call('migrate');
});

Route::get('/create-user', function () {
    // Check if the user already exists to avoid duplicates
    if (User::where('email', 'is@admin.com')->exists()) {
        return 'User already exists';
    }

    // Create the user with the email and password
    $user = User::create([
        'name' => 'Admin',
        'email' => 'is@mail.com',
        'password' => Hash::make('password'), // Ensure the password is hashed
    ]);

    return 'Admin user created successfully';
});

Route::get('/', LoginComponent::class)->name('home');
Route::get('/login', LoginComponent::class)->name('login');
Route::post('/', Logout::class)->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/stocks', StockComponent::class)->name('stocks');
    Route::get('/money-manage', MoneyComponent::class)->name('money_manage');
    Route::get('/branches', BranchComponent::class)->name('branches');
    Route::get('/set-branch-price', SetBranchPrice::class)->name('set_price');
    Route::get('/branch-sale', BranchSaleComponent::class)->name('branch_sale');
    Route::get('/head-office-sale', HeadOfficeSaleComponent::class)->name('office_sale');
    Route::get('/reject-free', RejectOrFreeComponent::class)->name('reject_free');
    Route::get('/expences', ExpenseComponent::class)->name('expences');
    Route::get('/expenditure-sheet', BalanceSheetComponent::class)->name('expenditure_sheet');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/report/money-report', MoneyReport::class)->name('money_report');
    Route::get('/report/purchase-report', PurchaseReport::class)->name('purchase_report');
    Route::get('/report/branch-sale-report', BranchSaleReportComponent::class)->name('branch_sale_report');
    Route::get('/report/ho-sale-report', HeadOfficeSaleReportComponent::class)->name('ho_sale_report');
    Route::get('/report/reject-free-report', RejectOrFreeReportComponent::class)->name('reject_free_report');
    Route::get('/report/expense-report', ExpenseReportComponent::class)->name('expense_report');
    Route::get('/report/branch-total-report', AllBranchReportComponent::class)->name('branch_total_report');
    Route::get('/report/stock-register-report', StockRegisterReportComponent::class)->name('stock_register_report');
    Route::get('/report/bank-balance-report', BankBalanceReport::class)->name('bank_balance_report');
    Route::get('/report/fund-management-report', FundManagementReportComponent::class)->name('fund_management_report');
    Route::get('/balance-sheet', BalanceSheetAll::class)->name('balance_sheet');


    Route::get('/report/transection-report', TransectionReport::class)->name('transection_report');
    Route::get('/sofar-net-profit', SofarNetProfitComponent::class)->name('sofar-net-profit.index');
    Route::get('/fund-management', FundManagementComponent::class)->name('fund_management');
    Route::get('/receipt-payment', ReceiptPaymentReportComponent::class)->name('receipt_payment');

    // routes/web.php
    Route::get('/loan-management', LoanManagementComponent::class)->name('loan.management');
});



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
