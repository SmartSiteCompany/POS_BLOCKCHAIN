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

    public function storeToJson(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|in:Crédito,Efectivo',
    ]);

    $userId = $request->input('user_id');
    if ($userId !== null && !User::find($userId)) {
        return redirect()->route('json.show')->with('error', 'El usuario con ID ' . $userId . ' no existe.');
    }

    $data = [
        'amount' => $request->input('amount'),
        'user_id' => $userId,
        'payment_method' => $request->input('payment_method'),
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
        $paymentMethod = $data['payment_method'];
        $userId = $data['user_id'] ?? null;

        $user = $userId ? User::find($userId) : null;

        if ($user) {
            if ($paymentMethod === 'Crédito') {
                if ($user->balance < $amount) {
                    continue; // Saldo insuficiente
                }
                $user->decrement('balance', $amount);

                // Todo el pago va al master
                if ($master) {
                    $master->increment('balance', $amount);
                }

            } elseif ($paymentMethod === 'Efectivo') {
                $cashback = $amount * 0.10;
                $remaining = $amount - $cashback;

                $user->increment('balance', $cashback);

                // Resto del dinero va al master
                if ($master) {
                    $master->increment('balance', $remaining);
                }
            }
        } else {
            // Usuario no registrado, todo va al master
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

