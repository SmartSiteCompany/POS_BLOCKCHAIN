<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">

        <h2 class="mb-4">Usuarios</h2>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Crear nuevo usuario</a>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id}}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ number_format($user->balance, 2) }}</td>
                        <td>
                            <a href="{{ route('transactions.buyForm', $user->id) }}" class="btn btn-sm btn-success">
                                Comprar monedas
                            </a>

                            {{-- Mostrar el botón de eliminar solo si el usuario autenticado NO es superusuario --}}
                            @if ($user->kind == 1)
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
    </form>
@endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('transactions.transferForm') }}" class="btn btn-success mt-4">Transferir monedas</a>
    </div>
</body>
</html>
