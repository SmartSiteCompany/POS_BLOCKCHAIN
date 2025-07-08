<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/transactions/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transferForm');
Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer');

Route::get('/transactions/buy/{id}', [TransactionController::class, 'showBuyForm'])->name('transactions.buyForm');
Route::post('/transactions/buy/{id}', [TransactionController::class, 'buy'])->name('transactions.buy');
