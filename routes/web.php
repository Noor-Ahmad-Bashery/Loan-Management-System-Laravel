<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DualController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DualController::class, 'index'])->name('login');

Route::post('/', [DualController::class, 'postLogin'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'getDashboard'])->name('dashboard');

    Route::get('/admin/register/register', [AdminController::class, 'getRegister'])->name('admin.register');
    Route::post('/admin/register/register', [AdminController::class, 'postRegister'])->name('admin.postRegister');

    Route::get('/admin/all', [AdminController::class, 'getRegisteredUser'])->name('admin.alluser');
    Route::get('/admin/user/update/{id}', [AdminController::class, 'getSingleUser'])->name('admin.getsingeluser');

    Route::put('/admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');

    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');


    Route::get('/admin/notebook', [AdminController::class, 'getNotebook'])->name('admin.notebook');

    Route::post('/admin/notebook', [AdminController::class, 'postNotebook'])->name('admin.notebook');

    // Monthlypayment
    Route::get('/admin/monthlypayment/monthlypayment', [AdminController::class, 'getMonthlyPayment'])->name('admin.monthlypayment');
    
    Route::post('/admin/monthlypayment/monthlypayment', [AdminController::class, 'postMonthlyPayment'])->name('admin.monthlypayment');
    
    Route::get('/admin/user/{userId}/monthlypayments', [AdminController::class, 'getPaidMonths'])->name('admin.getPaidMonths');

    
    
    Route::get('/admin/singleuser/payment/{id}', [AdminController::class, 'getSinglePayment'])->name('admin.singlePayment');
    
    Route::put('/admin/monthlypayment/update/{id}', [AdminController::class, 'updateMonthlypayment'])->name('admin.updateMonthlypayment');
    
    Route::delete('/admin/monthlypayment/delete/{id}', [AdminController::class, 'deletePayment'])->name('admin.deletePayment');
    
    
    /// Additional payment
    
    
    Route::get('/admin/additionalpayment/additionalpayment', [AdminController::class, 'getAdditionalPayment'])->name('admin.additionalpayment');
    
    Route::post('/admin/additionalpayment/additionalpayment', [AdminController::class, 'postAdditionalPayment'])->name('admin.additionalpayment');

    Route::get('/admin/singleuser/additionalpayment/update/{id}', [AdminController::class, 'getSingleAdditionalPaymentForUpdate'])->name('admin.singleAdditionalPayment');
    
    Route::get('/admin/user/{userId}/additionalpayment', [AdminController::class, 'getPaidAdditionalPayments'])->name('admin.getPaidAdditionalPayments');
    
    Route::put('/admin/additionalpayment/update/{id}', [AdminController::class, 'updateAdditionalPayment'])->name('admin.updateAdditionalPayment');
    
    Route::delete('/admin/additionalpayment/delete/{id}', [AdminController::class, 'deleteAdditionalPayment'])->name('admin.deleteAdditionalPayment');
    
    
    //////////////////////One User Loan Request
    Route::get('/admin/loan/loan', [LoanController::class, 'getLoanRequest'])->name('admin.loan');
    Route::post('/admin/loan/loan', [LoanController::class, 'postLoanRequest'])->name('admin.loan');
    
    
    /////MultiLoanRequest
    Route::get('/admin/multi/loan', [LoanController::class, 'getMultiLoanRequest'])->name('admin.getMultiLoanRequest');
    Route::post('/admin/multi/loan', [LoanController::class, 'postMultiLoanRequest'])->name('admin.postMultiLoanRequest');
    
    
    /// 
    Route::get('/admin/getloans', [LoanController::class, 'getLoans'])->name('admin.getLoans');
    Route::get('/admin/singleuser/loan/{id}', [LoanController::class, 'getSingleLoan'])->name('admin.getSingleLoan');

    Route::delete('/admin/loan/delete/{id}', [LoanController::class, 'deleteLoan'])->name('admin.deleteLoan');
    
    Route::put('/admin/loans/update/{id}', [LoanController::class, 'updateLoan'])->name('admin.updateLoan');
    
    Route::match(['get', 'post'], '/admin/loanpayment', [LoanController::class, 'loanPayments'])->name('admin.loanPayments');
    
    Route::post('/logout', [DualController::class, 'logout'])->name('dual.logout');
});
