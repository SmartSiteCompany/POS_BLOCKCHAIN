<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Mostrar el formulario de transferencia.
     */
    public function showTransferForm() {
        $users = User::all();
        return view('transactions.transfer', compact('users'));
    }

    /**
     * Guardar la transferencia en el archivo JSON (pendiente de procesar).
     */
    public function transfer(Request $request) {
        $request->validate([
            'from_user' => 'required|exists:users,id',
            'to_user' => 'required|exists:users,id|different:from_user',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $amount = $request->amount;
        $senderId = $request->from_user;
        $receiverId = $request->to_user;

        $sender = User::find($senderId);

        // Verificar saldo suficiente antes de guardar
        if ($sender->balance < $amount) {
            return back()->with('error', 'Fondos insuficientes.');
        }

        $path = storage_path('app/transactions/pending.json');
        $transactions = [];

        if (file_exists($path)) {
            $json = file_get_contents($path);
            $transactions = json_decode($json, true) ?: [];
        }

        // Añadir transacción de tipo transfer a JSON
        $transactions[] = [
            'amount' => $amount,
            'category' => 'transfer',
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'created_at' => now()->toDateTimeString(),
        ];

        file_put_contents($path, json_encode($transactions, JSON_PRETTY_PRINT));

        return redirect()->route('transactions.transfer')->with('success', 'Transferencia guardada y pendiente de procesamiento.');
    }



}
