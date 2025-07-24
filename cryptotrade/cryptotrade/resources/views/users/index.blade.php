@extends('layouts.app')

@section('content')
<body class="bg-light">
    <div class="container mt-5">

        <h2 class="mb-4">Usuarios</h2>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('register') }}" class="btn btn-primary mb-3">Nuevo usuario</a>
        <a href="{{ route('json.show') }}" class="btn btn-danger mb-3">Pagos</a>

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
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ number_format($user->balance, 2) }}</td>
                        <td>

                            {{-- Mostrar el botón de eliminar solo si el usuario autenticado NO es superusuario --}}
                            @if ($user->kind != 2)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

<script src="{{ asset('js/alerts.js') }}"></script>
@endsection
