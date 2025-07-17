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

    DB::transaction(function () use ($amount, $isRegistered, $userId, $paymentMethod) {
        $cashback = 0;

        if ($isRegistered) {
            $user = User::find($userId);

            if ($paymentMethod === 'credits') {
                // Verificar saldo suficiente
                if ($user->balance < $amount) {
                    throw new \Exception('Saldo insuficiente.');
                }
                // Descontar del balance
                $user->decrement('balance', $amount);
                // No hay cashback para pagos con crédito
            } elseif ($paymentMethod === 'cash') {
                // No se descuenta del balance, solo cashback para el user
                $cashback = $amount * 0.10;
                $user->increment('balance', $cashback);
            }

            // Guardar pago con user_id
            Pay::create([
                'amount' => $amount,
                'user_id' => $userId,
            ]);

            // Usuario master recibe el resto (pago menos cashback)
            $masterUser = User::where('kind', 2)->firstOrFail();
            $masterUser->increment('balance', $amount - $cashback);

        } else {
            // Usuario no registrado, todo va al master
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
