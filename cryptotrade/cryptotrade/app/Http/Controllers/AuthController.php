<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pay;

class AuthController extends Controller
{
    // Mostrar formulario de registro
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
            'password' => bcrypt($request->password), // encriptar
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

        return back()->withErrors([
            'email' => 'El correo o la contraseña no son correctos.',
        ]);
    }

    // Logout (cerrar sesión)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Mostrar dashboard con las transacciones del usuario
    public function dashboard()
    {
        $user = Auth::user();

        // Obtener transacciones del usuario
        $transactions = Pay::where('user_id', $user->id)
                           ->orderByDesc('created_at')
                           ->get();

        return view('dashboard', compact('user', 'transactions'));
    }
}
