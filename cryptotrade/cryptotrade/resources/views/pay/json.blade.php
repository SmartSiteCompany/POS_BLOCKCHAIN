@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestión de Transacciones JSON</h1>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @elseif(session('info'))
        <div class="alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @elseif(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Formulario para guardar transacción en JSON --}}
    <div class="card mb-4">
        <div class="card-header">Nueva Transacción</div>
        <div class="card-body">
            <form action="{{ route('json.save') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Monto:</label>
                    <input type="number" name="amount" id="amount" class="form-control" required step="0.01" min="0.01">
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">ID de Usuario (opcional):</label>
                    <input type="number" name="user_id" id="user_id" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Método de Pago:</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                      <option value="Efectivo">Efectivo</option>  
                      <option value="Crédito">Crédito</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Guardar en JSON</button>
            </form>
        </div>
    </div>

    {{-- Tabla con transacciones pendientes --}}
    <div class="card mb-4">
        <div class="card-header">Transacciones Pendientes</div>
        <div class="card-body">
            @if(!empty($transactions))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Monto</th>
                            <th>ID Usuario</th>
                            <th>Método de Pago</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                 <tbody>
    @foreach($transactions as $index => $transaction)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ number_format($transaction['amount'] ?? 0, 2) }}</td>
            <td>{{ $transaction['user_id'] ?? 'No registrado' }}</td>
            <td>{{ isset($transaction['payment_method']) ? ucfirst($transaction['payment_method']) : 'N/A' }}</td>
            <td>
                @if(isset($transaction['created_at']))
                    {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i') }}
                @else
                    Sin fecha
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

                </table>
            @else
                <p class="text-muted">No hay transacciones pendientes.</p>
            @endif
        </div>
    </div>

    {{-- Botón para procesar transacciones --}}
    <form action="{{ route('json.process') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Procesar Transacciones</button>
    </form>

   

<script src="{{ asset('js/alerts.js') }}"></script>
@endsection
