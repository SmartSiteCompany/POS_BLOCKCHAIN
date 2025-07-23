<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">POS Blockchain</a>
        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light">Cerrar sesión</button>
            </form>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Información del usuario -->
        <div class="mb-4">
            <h2 class="d-flex justify-content-between align-items-center">
    Hola, {{ $user->name }}
    <span class="badge bg-success fs-6">
        Balance: ${{ number_format($user->balance, 2) }}
    </span>
</h2>


            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Tipo de usuario:</strong> 
                @if($user->kind == 2)
                    Cuenta maestra
                @elseif($user->kind == 1)
                    Usuario registrado
                @else
                    Invitado / Otro
                @endif
            </p>
        </div>

        <!-- Tabla de transacciones -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                Transacciones
            </div>
            <div class="card-body">
                @if(count($transactions ?? []) > 0)
                    <table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Monto</th>
            <th>Método de Pago</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            @php
                $isCredit = $transaction->payment_method === 'credits';
                $rowClass = $isCredit ? 'table-danger' : 'table-success';
                $displayAmount = $isCredit
                    ? $transaction->amount
                    : $transaction->amount * 0.10;
            @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $transaction->id }}</td>
                <td>
    {{ $isCredit ? '-' : '+' }}${{ number_format($displayAmount, 2) }}
</td>

                <td>{{ ucfirst($transaction->payment_method) }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

                @else
                    <p class="text-muted">No hay transacciones aún.</p>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
