<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transferencia de monedas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Transferir monedas</h2>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.transfer') }}" class="card p-4 shadow-sm bg-white">
            @csrf

            <div class="mb-3">
                <label for="from_user" class="form-label">De:</label>
                <select name="from_user" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} (Balance: ${{ number_format($user->balance, 2) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="to_user" class="form-label">Para:</label>
                <select name="to_user" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Cantidad:</label>
                <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="btn btn-success">Transferir</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Volver</a>
        </form>
    </div>
</body>
</html>
