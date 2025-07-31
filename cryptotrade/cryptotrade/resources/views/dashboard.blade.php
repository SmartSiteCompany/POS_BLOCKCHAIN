@extends('layouts.app')

@section('content')
<div class="vista-transacciones container py-5">
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
                Administrador
            @elseif($user->kind == 1)
                Básico
            @else
                Invitado / Otro
            @endif
        </p>
    </div>

    <!-- Tabla de transacciones -->
    @if(count($transactions ?? []) > 0)
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    @if($user->kind == 2)
                        <th>ID de Usuario</th>
                    @endif
                    <th>Monto</th>
                    <th>Método de Pago</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    @php
                        $paymentMethod = strtolower($transaction['payment_method'] ?? '');
                        $creditOptions = ['crédito', 'credito', 'credits', 'creditos'];
                        $isCredit = in_array($paymentMethod, $creditOptions);
                        $rowClass = $isCredit ? 'table-danger' : 'table-success';
                        $displayAmount = $isCredit
                            ? ($transaction['amount'] ?? 0)
                            : (($transaction['amount'] ?? 0) * 0.10);
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $transaction['id'] ?? 'N/A' }}</td>
                        @if($user->kind == 2)
                            <td>{{ $transaction['user_id'] ?? 'N/A' }}</td>
                        @endif
                        <td>
                            {{ $isCredit ? '-' : '+' }}${{ number_format($displayAmount, 2) }}
                        </td>
                        <td>{{ ucfirst($transaction['payment_method'] ?? '') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction['created_at'] ?? now())->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No hay transacciones aún.</p>
    @endif
</div>
@endsection
