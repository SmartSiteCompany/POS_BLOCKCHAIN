<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\JsonTransactionController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Esta es la ruta que carga la vista JSON
Route::get('/json', function () {
    return view('pay.json'); // Asegúrate de que este archivo exista en: resources/views/pay/json.blade.php
})->name('json.view');

// CRUD de usuarios
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Transacciones
Route::get('/transactions/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transferForm');
Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer');
Route::get('/transactions/buy/{id}', [TransactionController::class, 'showBuyForm'])->name('transactions.buyForm');
Route::post('/transactions/buy/{id}', [TransactionController::class, 'buy'])->name('transactions.buy');

// Formulario de pago
Route::get('/pay', [PayController::class, 'create'])->name('pay.create');
Route::post('/pay', [PayController::class, 'store'])->name('pay.store');

// JSON: guardar y procesar transacciones

Route::get('/json', [JsonTransactionController::class, 'showPendingTransactions'])->name('json.show');
Route::post('/json/save', [JsonTransactionController::class, 'storeToJson'])->name('json.save');
Route::post('/json/process', [JsonTransactionController::class, 'processJson'])->name('json.process');

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');






Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['user' => auth()->user()]);
    })->name('dashboard');
});


