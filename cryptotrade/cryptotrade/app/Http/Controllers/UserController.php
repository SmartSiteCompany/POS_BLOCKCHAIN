<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
   public function index()
{
    $user = auth()->user();  // Obtener usuario autenticado
    
    if (!$user || $user->kind != 2) {
        $user = Auth::user();

        // Obtener transacciones del usuario
        $transactions = Pay::where('user_id', $user->id)
                           ->orderByDesc('created_at')
                           ->get();

        return view('dashboard', compact('user', 'transactions'));
    }

    $users = User::all();
    return view('users.index', compact('users'));
}


    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validación de los campos necesarios
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'kind' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        // Validar que no se pueda crear más de un usuario master (kind = 2)
        if (($request->kind ?? 1) == 2 && User::where('kind', 2)->exists()) {
            return back()->with('error', 'Ya existe un usuario master. No se puede crear otro.');
        }

        // Crear el usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'balance' => 0,
            'password' => Hash::make($request->password),
            'kind' => $request->kind ?? 1,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id); // Buscar al usuario por su ID
        $user->delete(); // Eliminar al usuario

        // Redirigir de vuelta a la lista de usuarios con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }


    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
}


}
