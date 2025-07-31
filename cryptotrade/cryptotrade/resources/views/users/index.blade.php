@extends('layouts.app')

@section('content')
<body class="bg-light">
    <div class="vista-usuarios container mt-5">
        <h2 class="mb-4">Usuarios</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('register') }}" class="btn btn-primary mb-3">Nuevo usuario</a>
        <a href="{{ route('dashboard') }}" class="btn btn-success mb-3">Dashboard</a>
        <a href="{{ route('json.show') }}" class="btn btn-danger mb-3">Pagos</a>

        <table class="table table-bordered table-striped">
            <thead class="encabezado-dark">
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
                        <td class="d-flex gap-1">
                            <!-- Botón de Editar -->
                            <a href="{{ route('users.edit', $user->id) }}" class="action-button btn-alter" style="text-decoration: none;">
                                <div class="svg-wrapper-1">
                                    <div class="svg-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="icon">
                                            <path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41L18.37 3.29c-.39-.39-1.02-.39-1.41 0L15.13 5.12l3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </div>
                                </div>
                                <span>Editar</span>
                            </a>

                            <!-- Botón de Eliminar -->
                            @if ($user->kind != 2)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <div class="svg-wrapper-1">
                                            <div class="svg-wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M9 3v1H4v2h16V4h-5V3H9zm2 4v12h2V7h-2zM7 7v12h2V7H7zm8 0v12h2V7h-2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span>Eliminar</span>
                                    </button>
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
