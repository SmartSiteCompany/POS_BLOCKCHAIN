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

        <table class="table table-bordered table-striped mt-3">
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

                            <!-- Botón de Editar con ícono Remixicon -->
                            <a href="{{ route('users.edit', $user->id) }}" class="action-button btn-alter text-decoration-none d-flex align-items-center gap-1">
                                <i class="ri-edit-box-line" style="font-size: 20px;"></i>
                                <span>Editar</span>
                            </a>

                            <!-- Botón de Eliminar con ícono Remixicon -->
                            @if ($user->kind != 2)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button btn-danger d-flex align-items-center gap-1" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <i class="ri-delete-bin-6-line" style="font-size: 20px;"></i>
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

