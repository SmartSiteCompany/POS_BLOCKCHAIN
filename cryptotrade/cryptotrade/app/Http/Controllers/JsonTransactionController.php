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

        // Validar existencia del usuario (si se proporciona)
        $user = $userId ? User::find($userId) : null;
        if ($userId !== null && !$user) {
            return redirect()->route('json.show')->with('error', 'El usuario con ID ' . $userId . ' no existe.');
        }

        // Verificar crédito suficiente si es método Crédito
        if ($paymentMethod === 'Crédito') {
            if (!$user) {
                return redirect()->route('json.show')->with('error', 'No se puede usar crédito sin un usuario válido.');
            }

            if ($user->balance < $amount) {
                return redirect()->route('json.show')->with('error', 'Credito insuficiente. No se guardó la transacción.');
            }
        }

        // Guardar en JSON si pasa todas las validaciones
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

        // Buscar el usuario maestro (kind == 2)
        $master = User::where('kind', 2)->first();

        foreach ($transactions as $data) {
            if (!isset($data['amount']) || !is_numeric($data['amount'])) {
                continue;
            }

            $amount = $data['amount'];

            // Verificar si es transferencia
            if (isset($data['category']) && $data['category'] === 'transfer') {
                $senderId = $data['sender_id'] ?? null;
                $receiverId = $data['receiver_id'] ?? null;

                $sender = $senderId ? User::find($senderId) : null;
                $receiver = $receiverId ? User::find($receiverId) : null;

                if ($sender && $receiver) {
                    if ($sender->balance < $amount) {
                        continue; // saldo insuficiente, saltar esta transferencia
                    }

                    // Ejecutar transferencia
                    $sender->decrement('balance', $amount);
                    $receiver->increment('balance', $amount);

                    Transaction::create([
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'amount' => $amount,
                        'type' => 'transfer',
                    ]);
                }

                continue; // ya procesamos esta transacción, saltar al siguiente
            }

            // No es transferencia, se espera payment_method
            $paymentMethod = $data['payment_method'] ?? null;
            $userId = $data['user_id'] ?? null;
            $user = $userId ? User::find($userId) : null;

            if (!$paymentMethod) {
                // Si no tiene método de pago, saltar
                continue;
            }

            if ($user) {
                if ($paymentMethod === 'Crédito') {
                    if ($user->balance < $amount) {
                        continue; // saldo insuficiente
                    }
                    $user->decrement('balance', $amount);

                    if ($master) {
                        $master->increment('balance', $amount);
                    }

                } elseif ($paymentMethod === 'Efectivo') {
                    $cashback = $amount * 0.10;
                    $remaining = $amount - $cashback;

                    $user->increment('balance', $cashback);

                    if ($master) {
                        $master->increment('balance', $remaining);
                    }
                }
            } else {
                if ($master) {
                    $master->increment('balance', $amount);
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
