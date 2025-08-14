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

// CRUD Usuarios
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);

    // Transferencias
    Route::get('/transactions/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transferForm');
    Route::post('/transactions/transfer', [TransactionController::class, 'transfer'])->name('transactions.transfer');

    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

// Buscar usuario por ID
Route::get('/buscar-usuario/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    return $user ? response()->json($user) : response()->json(['error' => 'Usuario no encontrado'], 404);
});

// Compras
Route::get('/transactions/buy/{id}', [TransactionController::class, 'showBuyForm'])->name('transactions.buyForm');
Route::post('/transactions/buy/{id}', [TransactionController::class, 'buy'])->name('transactions.buy');

// Pagos
Route::get('/pay', [PayController::class, 'create'])->name('pay.create');
Route::post('/pay', [PayController::class, 'store'])->name('pay.store');

// JSON transacciones
Route::get('/json', [JsonTransactionController::class, 'showPendingTransactions'])->name('json.show');
Route::post('/json/save', [JsonTransactionController::class, 'storeToJson'])->name('json.save');
Route::post('/json/process', [JsonTransactionController::class, 'processJson'])->name('json.process');

// Subida de JSON
// Subida de JSON
Route::get('/upload-json', [JsonTransactionController::class, 'showUploadForm'])->name('transactions.uploadForm');
Route::post('/upload-json', [JsonTransactionController::class, 'uploadJson'])->name('transactions.upload');

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
