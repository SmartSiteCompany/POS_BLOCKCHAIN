@extends('layouts.app')

@section('content')
<div class="vista-transacciones container py-4">

   <!-- Header de usuario -->
   <div class="usuario-header card shadow p-4 mb-4 rounded-4 bg-purple-gradient text-white">

       <div class="contenedor-dashboard">
           <!-- Izquierda: saludo -->
           <div class="seccion-usuario">
               <div class="welcome-text">
                   <h5 class="fw-light mb-1">¡Qué gusto verte,</h5>
                   <h3 class="fw-bold">{{ $user->name }}!</h3>
               </div>
           </div>

           <!-- Derecha: saldo -->
<div class="seccion-saldo balance-display">
    <small class="text-light">Saldo disponible</small>
    <h2 class="fw-bold display-6 balance-amount blurred" id="balance-amount">
        ${{ number_format($user->balance, 2) }} <span class="dtx-label">DTX</span>
    </h2>
</div>

       </div>

       <div class="btn-depositar mt-4">
           <a href="#" class="btn btn10 px-5 py-2 fw-bold rounded-pill shadow-sm">Depositar</a>
       </div>
   </div>

   <!-- Acciones rápidas -->
  @php
$acciones = [
    ['icon' => 'ri-folder-transfer-fill', 'label' => 'Enviar', 'url' => 'transactions/transfer'],
    ['icon' => 'ri-hand-heart-line', 'label' => 'Retirar', 'url' => '/transactions/withdraw'],
    ['icon' => 'ri-qr-code-line', 'label' => 'QR', 'url' => '/transactions/qr'],
    ['icon' => 'ri-wallet-3-fill', 'label' => 'Recargar', 'url' => '/transactions/recharge'],
];
@endphp

   <div class="acciones-rapidas mb-4">
    <h5 class="mb-3 text-dark fw-semibold">Hoy quiero...</h5>
    <div class="d-flex flex-wrap gap-3">
        @foreach ($acciones as $action)
            <a href="{{ $action['url'] }}" class="quick-action text-center border rounded p-3" style="min-width: 90px;">
                <div class="quick-action-icon fs-3 mb-2">
                    <i class="bi {{ $action['icon'] }}"></i>
                </div>
                <div class="quick-action-label">
                    {{ $action['label'] }}
                </div>
                <div class="quick-action-text mt-1">
                    {{ $action['texto_extra'] ?? '' }}
                </div>
            </a>
        @endforeach
    </div>
</div>



<!-- Tabla de transacciones -->
<div class="">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-semibold text-purple">Mis movimientos</h5>

    </div>

    @if(count($transactions) > 0)
        <table class="table table-borderless table-hover">
            <tbody>
            @foreach($transactions as $transaction)
                @php
                    $type = strtolower($transaction->type ?? 'desconocido');
                    $type = str_replace(
                        ['á','é','í','ó','ú'],
                        ['a','e','i','o','u'],
                        $type
                    );
                    $amount = $transaction->amount ?? 0;

                    $isTransfer = ($type === 'transfer');
                    $isBuy = ($type === 'buy');
                    $isCredit   = ($type === 'credito' || $type === 'credit');
                    $isCash     = ($type === 'efectivo' || $type === 'cash');

                    // Íconos modernos según tipo
                    $iconClass = 'fa-solid fa-circle text-secondary'; // default
                    if ($isTransfer) {
                        $iconClass = 'fa-solid fa-money-bill-transfer text-primary';
                    } elseif ($isCredit) {
                        $iconClass = 'fa-regular fa-credit-card text-danger';
                    } elseif ($isCash) {
                        $iconClass = 'fa-solid fa-money-bill-trend-up text-success';
                    } elseif ($isBuy) {
                        $iconClass = 'fa-solid fa-coins text-success';
                    }


                    // Cálculo de monto y signo
                    if ($isCredit) {
                        $sign = '-';
                        $displayAmount = $amount;
                    } elseif ($isCash) {
                        $sign = '+';
                        $displayAmount = $amount * 0.10; // 10% cashback
                    } elseif ($isTransfer) {
                        $sign = '→';
                        $displayAmount = $amount;
                    } else {
                        $sign = '';
                        $displayAmount = $amount;
                    }
                @endphp

                <tr class="border-bottom align-middle
                    @if($isTransfer) tr-transfer
                    @elseif($isCredit) tr-credit
                    @elseif($isCash) tr-cash
                    @elseif($isBuy) tr-buy
                    @endif
                ">
                    <!-- Columna de ícono -->
                    <td class="icon-column">
    <div class="icon-circle">
        <i class="{{ $iconClass }}"></i>
    </div>
</td>
                    <!-- Columna de descripción -->
                    <td>
                        @if($isTransfer)
                            Transferencia de <strong>{{ $transaction->sender ? $transaction->sender->name : 'Desconocido' }}</strong> a <strong>{{ $transaction->receiver ? $transaction->receiver->name : 'Desconocido' }}</strong>
                        @elseif($isCredit)
                            Uso de crédito
                        @elseif($isCash)
                            Pago en efectivo (10% Cashback)
                        @else
                            {{ ucfirst($type) }}
                        @endif
                        <br>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</small>
                    </td>

                    <!-- Columna de monto -->
                    <td class="text-end fw-bold {{ ($isTransfer || $isCredit) ? 'text-danger' : 'text-success' }}">
                        {{ $sign }}${{ number_format($displayAmount, 2) }}
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    
    @else
        <p class="text-muted text-center">No hay transacciones aún.</p>
    @endif
</div>

</div>
<script src="{{ asset('js/blur.js') }}"></script>
@endsection
