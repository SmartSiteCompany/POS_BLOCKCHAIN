<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pay;
use App\Models\User;

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

    // Guardar transacción en JSON (acumula transacciones)
    public function storeToJson(Request $request)
{
    $data = $request->validate([
        'amount' => 'required|numeric',
        'user_id' => 'nullable|exists:users,id',
        'payment_method' => 'required|in:credits,cash',
    ]);

    $path = storage_path('app/transactions/pending.json');

    // Cargar transacciones previas si existen
    if (file_exists($path)) {
        $json = file_get_contents($path);
        $transactions = json_decode($json, true);
        if (!is_array($transactions)) {
            $transactions = [];
        }
    } else {
        $transactions = [];
    }

    $data['created_at'] = now()->toDateTimeString();

    $transactions[] = $data;

    file_put_contents($path, json_encode($transactions, JSON_PRETTY_PRINT));

    return redirect()->route('json.show')->with('success', 'Transacción guardada en archivo JSON.');
}


    // Procesar las transacciones del JSON y guardarlas en BD
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

        foreach ($transactions as $data) {
            if (!isset($data['amount']) || !is_numeric($data['amount'])) {
                continue;
            }

            $amount = $data['amount'];
            $paymentMethod = $data['payment_method'];
            $userId = $data['user_id'] ?? null;

            $user = $userId ? User::find($userId) : null;

            if ($user) {
                if ($paymentMethod === 'credits') {
                    if ($user->balance < $amount) {
                        continue; // Saldo insuficiente, se omite
                    }
                    $user->decrement('balance', $amount);
                } elseif ($paymentMethod === 'cash') {
                    $cashback = $amount * 0.10;
                    $user->increment('balance', $cashback);
                }
            }

            Pay::create([
                'amount' => $amount,
                'user_id' => $userId,
                'payment_method' => $paymentMethod,
            ]);
        }

        // Limpiar archivo
        file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));

        return redirect()->route('json.show')->with('success', 'Transacciones procesadas exitosamente.');
    }
}

