<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pay;
use App\Models\Transaction;

class AuthController extends Controller
{
    // Mostrar formulario de registro prueba
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'kind' => 1,
            'balance' => 0,
            'status' => 1,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->with('error', 'El correo o la contraseña no son correctos.');
    }

    // Logout (cerrar sesión)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

   public function dashboard()
{
    $user = Auth::user();

    // Obtener movimientos de Pays (Efectivo y Crédito)
    if ($user->kind == 2) {
        $pays = Pay::orderByDesc('created_at')->get();
        $transfers = Transaction::with(['sender', 'receiver'])->orderByDesc('created_at')->get();
    } else {
        $pays = Pay::where('user_id', $user->id)->orderByDesc('created_at')->get();
        $transfers = Transaction::with(['sender', 'receiver'])
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })->orderByDesc('created_at')->get();
    }

    // Normalizar Pays para vista (agregamos 'type' para distinguir)
    $pays = $pays->map(function($pay) {
    $method = strtolower($pay->payment_method ?? 'efectivo'); // default a efectivo
    $method = str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $method);

    // Aseguramos que solo tenga los tipos válidos
    if (!in_array($method, ['efectivo','credito'])) {
        $method = 'efectivo';
    }

    return (object) [
        'id' => $pay->id,
        'amount' => $pay->amount,
        'type' => $method,
        'created_at' => $pay->created_at,
        'sender' => null,
        'receiver' => null,
        'user_id' => $pay->user_id,
    ];
});

    // Normalizar Transactions para vista (ya tienen tipo: 'transfer')
    $transfers = $transfers->map(function($tx) {
        return (object) [
            'id' => $tx->id,
            'amount' => $tx->amount,
            'type' => $tx->type, // 'transfer'
            'created_at' => $tx->created_at,
            'sender' => $tx->sender,
            'receiver' => $tx->receiver,
        ];
    });

    // Combinar y ordenar todo por fecha descendente
    $transactions = $pays->concat($transfers)
        ->sortByDesc('created_at')
        ->values();

    return view('dashboard', compact('user', 'transactions'));
}
}
