<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pay;
use App\Models\User;
use App\Models\Transaction;

class JsonTransactionController extends Controller
{
    // Mostrar vista con transacciones pendientes
    public function showPendingTransactions()
    {
        $transactions = [];
        $path = storage_path('app/transactions/pending.json');

        if (file_exists($path)) {
            $json = file_get_contents($path);
            $transactions = json_decode($json, true) ?: [];
        }

        return view('pay.json', compact('transactions'));
    }

    public function uploadJson(Request $request)
{
    $request->validate([
        'json_file' => 'required|file|mimes:json',
    ]);

    $file = $request->file('json_file');
    $jsonData = file_get_contents($file->getRealPath());
    $transactions = json_decode($jsonData, true);

    if (!is_array($transactions)) {
        return redirect()->route('json.show')->with('error', 'El archivo JSON no es válido.');
    }

    // Leer las transacciones pendientes actuales
    $path = storage_path('app/transactions/pending.json');
    $pending = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

    // Agregar nuevas transacciones
    $pending = array_merge($pending, $transactions);

    file_put_contents($path, json_encode($pending, JSON_PRETTY_PRINT));

    return redirect()->route('json.show')->with('success', 'Archivo JSON cargado. Transacciones pendientes agregadas.');
}


    // Guardar transacción en JSON
    public function storeToJson(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:Crédito,Efectivo',
        ]);

        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        $userId = $request->input('user_id');

        $user = $userId ? User::find($userId) : null;
        if ($userId !== null && !$user) {
            return redirect()->route('json.show')->with('error', 'El usuario con ID ' . $userId . ' no existe.');
        }

        if ($paymentMethod === 'Crédito' && $user && $user->balance < $amount) {
            return redirect()->route('json.show')->with('error', 'Crédito insuficiente. No se guardó la transacción.');
        }

        $data = [
            'amount' => $amount,
            'user_id' => $userId,
            'payment_method' => $paymentMethod,
            'created_at' => now()->toDateTimeString(),
        ];

        $path = storage_path('app/transactions/pending.json');
        $transactions = [];

        if (file_exists($path)) {
            $json = file_get_contents($path);
            $transactions = json_decode($json, true) ?: [];
        }

        $transactions[] = $data;
        file_put_contents($path, json_encode($transactions, JSON_PRETTY_PRINT));

        return redirect()->route('json.show')->with('success', 'Transacción guardada en archivo JSON.');
    }

    // Procesar las transacciones del JSON
    public function processJson()
    {
        $path = storage_path('app/transactions/pending.json');

        if (!file_exists($path)) {
            return redirect()->route('json.show')->with('info', 'Archivo de transacciones no encontrado.');
        }

        $json = file_get_contents($path);
        $transactions = json_decode($json, true);

        if (!is_array($transactions) || empty($transactions)) {
            return redirect()->route('json.show')->with('info', 'No hay transacciones pendientes para procesar.');
        }

        $master = User::where('kind', 2)->first();

        $cashTransactions = [];
        $creditTransactions = [];
        $transferTransactions = [];

        // Separar por tipo
        foreach ($transactions as $data) {
            if (isset($data['category']) && $data['category'] === 'transfer') {
                $transferTransactions[] = $data;
            } else {
                $method = $data['payment_method'] ?? null;
                if ($method === 'Efectivo') $cashTransactions[] = $data;
                elseif ($method === 'Crédito') $creditTransactions[] = $data;
            }
        }

        // Procesar en orden: efectivo → transferencias → crédito
        $this->processTransactions($cashTransactions, $master);
        $this->processTransactions($transferTransactions, $master, true);
        $this->processTransactions($creditTransactions, $master);

        // Limpiar JSON
        file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));

        return redirect()->route('json.show')->with('success', 'Transacciones procesadas exitosamente.');
    }

    public function showUploadForm()
{
    return view('pay.upload-json'); // Apunta a resources/views/json/upload.blade.php
}


    // Método privado para procesar transacciones
    private function processTransactions(array $transactions, ?User $master, bool $isTransfer = false)
    {
        foreach ($transactions as $data) {
            $amount = $data['amount'] ?? 0;
            $userId = $data['user_id'] ?? null;
            $user = $userId ? User::find($userId) : null;

            if ($isTransfer) {
                $sender = isset($data['sender_id']) ? User::find($data['sender_id']) : null;
                $receiver = isset($data['receiver_id']) ? User::find($data['receiver_id']) : null;

                if ($sender && $receiver && $sender->balance >= $amount) {
                    $sender->decrement('balance', $amount);
                    $receiver->increment('balance', $amount);

                    Transaction::create([
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'amount' => $amount,
                        'type' => 'transfer',
                    ]);
                }
                continue;
            }

            $paymentMethod = $data['payment_method'] ?? null;
            if (!$paymentMethod) continue;

            if ($user) {
                if ($paymentMethod === 'Crédito') {
                    if ($user->balance < $amount) continue;
                    $user->decrement('balance', $amount);
                    if ($master) $master->increment('balance', $amount);

                } elseif ($paymentMethod === 'Efectivo') {
                    $cashback = $amount * 0.10;
                    $remaining = $amount - $cashback;
                    $user->increment('balance', $cashback);
                    if ($master) $master->increment('balance', $remaining);
                }
            } else {
                if ($master) $master->increment('balance', $amount);
            }

            Pay::create([
                'amount' => $amount,
                'user_id' => $userId,
                'payment_method' => $paymentMethod,
            ]);
        }
    }
}
