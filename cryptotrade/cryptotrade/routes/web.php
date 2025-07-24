<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\JsonTransactionController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// CRUD usuarios
Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Transacciones
Route::get('/transactions/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transferForm');
Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer');
Route::get('/transactions/buy/{id}', [TransactionController::class, 'showBuyForm'])->name('transactions.buyForm');
Route::post('/transactions/buy/{id}', [TransactionController::class, 'buy'])->name('transactions.buy');

// Formulario de pago
Route::get('/pay', [PayController::class, 'create'])->name('pay.create');
Route::post('/pay', [PayController::class, 'store'])->name('pay.store');

// JSON transacciones
Route::get('/json', [JsonTransactionController::class, 'showPendingTransactions'])->name('json.show');
Route::post('/json/save', [JsonTransactionController::class, 'storeToJson'])->name('json.save');
Route::post('/json/process', [JsonTransactionController::class, 'processJson'])->name('json.process');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Rutas protegidas (requieren login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});


