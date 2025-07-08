<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Procesar la transferencia entre usuarios.
     */
    public function transfer(Request $request) {
        $request->validate([
            'from_user' => 'required|exists:users,id',
            'to_user' => 'required|exists:users,id|different:from_user',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $sender = User::find($request->from_user);
        $receiver = User::find($request->to_user);
        $amount = $request->amount;

        if ($sender->balance < $amount) {
            return back()->with('error', 'Fondos insuficientes.');
        }

        DB::transaction(function () use ($sender, $receiver, $amount) {
            $sender->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'type' => 'transfer'
            ]);
        });

        return redirect()->route('users.index')->with('success', 'Transferencia realizada.');
    }

    /**
     * Mostrar el formulario para agregar saldo a un usuario.
     */
    public function showBuyForm($id) {
        $user = User::findOrFail($id);
        return view('transactions.buy', compact('user'));
    }

    /**
     * Procesar la compra de saldo para un usuario.
     */
    public function buy(Request $request, $id) {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $user = User::findOrFail($id);
        $amount = $request->amount;

        $user->increment('balance', $amount);

        Transaction::create([
            'sender_id' => null,
            'receiver_id' => $user->id,
            'amount' => $amount,
            'type' => 'buy'
        ]);

        return redirect()->route('users.index')->with('success', 'Compra realizada con éxito.');
    }
}
