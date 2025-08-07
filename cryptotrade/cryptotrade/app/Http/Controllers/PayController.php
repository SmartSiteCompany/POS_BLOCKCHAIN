<?php

namespace App\Http\Controllers;

use App\Models\Pay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    public function create()
    {
        return view('pay.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'is_registered' => 'required|boolean',
        'user_id' => 'nullable|required_if:is_registered,1|exists:users,id',
        'payment_method' => 'nullable|required_if:is_registered,1|in:credits,cash',
    ]);

    $amount = $request->amount;
    $isRegistered = $request->is_registered;
    $userId = $request->user_id;
    $paymentMethod = $request->payment_method;

    // VALIDACIÓN DE SALDO ANTES DE LA TRANSACCIÓN
    if ($isRegistered && $paymentMethod === 'credits') {
        $user = User::find($userId);
        if ($user->balance < $amount) {
            return redirect()->back()->with('error', 'Saldo insuficiente.');
        }
    }

    DB::transaction(function () use ($amount, $isRegistered, $userId, $paymentMethod) {
        $cashback = 0;

        if ($isRegistered) {
            $user = User::find($userId);

            if ($paymentMethod === 'credits') {
                $user->decrement('balance', $amount);
            } elseif ($paymentMethod === 'cash') {
                $cashback = $amount * 0.10;
                $user->increment('balance', $cashback);
            }

            Pay::create([
                'amount' => $amount,
                'user_id' => $userId,
            ]);

            $masterUser = User::where('kind', 2)->firstOrFail();
            $masterUser->increment('balance', $amount - $cashback);

        } else {
            Pay::create([
                'amount' => $amount,
                'user_id' => null,
            ]);

            $masterUser = User::where('kind', 2)->firstOrFail();
            $masterUser->increment('balance', $amount);
        }
    });

    return redirect()->route('users.index')->with('success', 'Pago procesado correctamente.');
}
}