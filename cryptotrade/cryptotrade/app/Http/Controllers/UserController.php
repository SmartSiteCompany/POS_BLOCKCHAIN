<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        // Validación de los campos necesarios
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6', // Añadido un mínimo de caracteres para la contraseña
            'kind' => 'nullable|integer', // Validación opcional para 'kind'
            'status' => 'nullable|integer', // Validación opcional para 'status'
        ]);

        // Crear el usuario, incluyendo 'kind' y 'status' con valores por defecto
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'balance' => 0,
            'password' => Hash::make($request->password),
            'kind' => $request->kind ?? 1,  // Si 'kind' no se pasa, se asigna 1 (usuario normal)
            'status' => $request->status ?? 1,  // Si 'status' no se pasa, se asigna 1 (activo)
        ]);
        

        // Redirigir a la vista de lista de usuarios
        return redirect()->route('users.index');
    }

    public function destroy($id) {
    $user = User::findOrFail($id); // Buscar al usuario por su ID
    $user->delete(); // Eliminar al usuario

    // Redirigir de vuelta a la lista de usuarios con un mensaje de éxito
    return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
}

}
