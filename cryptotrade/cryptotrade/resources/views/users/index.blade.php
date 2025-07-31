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

        <a href="{{ route('register') }}" class="action-button btn-primary text-decoration-none">
            <div class="svg-wrapper-1">
                <div class="svg-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.5-1.8 4.5-4.5S14.7 3 12 3 7.5 4.8 7.5 7.5 9.3 12 12 12zm0 1.5c-3 0-9 1.5-9 4.5V21h18v-3c0-3-6-4.5-9-4.5z"/>
                    </svg>
                </div>
            </div>
            <span>Registro</span>
        </a>

        <a href="{{ route('dashboard') }}" class="action-button btn-alter text-decoration-none">
            <div class="svg-wrapper-1">
                <div class="svg-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <title>monitor-dashboard</title>
                        <path d="M21,16V4H3V16H21M21,2A2,2 0 0,1 23,4V16A2,2 0 0,1 21,18H14V20H16V22H8V20H10V18H3C1.89,18 1,17.1 1,16V4C1,2.89 1.89,2 3,2H21M5,6H14V11H5V6M15,6H19V8H15V6M19,9V14H15V9H19M5,12H9V14H5V12M10,12H14V14H10V12Z" />
                    </svg>
                </div>
            </div>
            <span>Panel</span>
        </a>

        <a href="{{ route('json.show') }}" class="action-button btn-success text-decoration-none">
            <div class="svg-wrapper-1">
                <div class="svg-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <title>cash-multiple</title>
                        <path d="M5,6H23V18H5V6M14,9A3,3 0 0,1 17,12A3,3 0 0,1 14,15A3,3 0 0,1 11,12A3,3 0 0,1 14,9M9,8A2,2 0 0,1 7,10V14A2,2 0 0,1 9,16H19A2,2 0 0,1 21,14V10A2,2 0 0,1 19,8H9M1,10H3V20H19V22H1V10Z" />
                    </svg>
                </div>
            </div>
            <span>Pagos</span>
        </a>

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
                            <!-- Botón de Editar (corregido) -->
                            <a href="{{ route('users.edit', $user->id) }}" class="action-button btn-alter text-decoration-none">
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
