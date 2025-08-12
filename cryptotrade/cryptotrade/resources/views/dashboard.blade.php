@extends('layouts.app')

@section('content')
<div class="vista-transacciones container py-4">

   <!-- Header de usuario -->
   <div class="usuario-header card shadow p-4 mb-4 rounded-4 bg-purple-gradient text-white">

       <!-- Contenedor dividido en 2 -->
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
               <h2 class="fw-bold display-6">${{ number_format($user->balance, 2) }}</h2>
           </div>
       </div>

       <!-- Botón Depositar -->
       <div class="btn-depositar mt-4">
           <a href="#" class="btn btn10 px-5 py-2 fw-bold rounded-pill shadow-sm">Depositar</a>
       </div>
   </div>

   <!-- Acciones rápidas -->
   @php
   $acciones = [
       ['icon' => 'bi-send-fill', 'label' => 'Enviar', 'url' => 'transactions/transfer'],
       ['icon' => 'bi-download', 'label' => 'Retirar', 'url' => '/transactions/withdraw'],
       ['icon' => 'bi-upc-scan', 'label' => 'QR', 'url' => '/transactions/qr'],
       ['icon' => 'bi-phone', 'label' => 'Recargar', 'url' => '/transactions/recharge'],
   ];
   @endphp

   <div class="acciones-rapidas mb-4">
       <h5 class="mb-3 text-dark fw-semibold">Hoy quiero...</h5>
       <div class="d-flex flex-wrap gap-3">
           @foreach ($acciones as $action)
               <a href="{{ $action['url'] }}" class="quick-action text-center border rounded p-3" style="min-width: 90px;">
                   <div class="quick-action-icon fs-3 mb-2"><i class="bi {{ $action['icon'] }}"></i></div>
                   <div class="quick-action-label">{{ $action['label'] }}</div>
               </a>
           @endforeach
       </div>
   </div>

   <!-- Tabla de transacciones -->
   <div class="card rounded-4 p-3 shadow-sm">
       <div class="d-flex justify-content-between align-items-center mb-3">
           <h5 class="mb-0 fw-semibold text-purple">Mis movimientos</h5>
           <a href="#" class="text-decoration-none text-muted small">Ver todo</a>
       </div>

       @if(count($transactions) > 0)
           <table class="table table-borderless table-hover">
               <tbody>
               @foreach($transactions as $transaction)
                   @php
                       $type = strtolower($transaction->type ?? '');
                       $isTransfer = ($type === 'transfer');
                       $isCredit = ($type === 'credito' || $type === 'credit');
                       $isCash = ($type === 'efectivo' || $type === 'cash');

                       // Íconos por tipo
                       $icon = 'bi-info-circle-fill text-secondary';
                       if ($isTransfer) {
                           $icon = 'bi-arrow-left-right-circle-fill text-primary';
                       } elseif ($isCredit) {
                           $icon = 'bi-arrow-down-circle-fill text-danger';
                       } elseif ($isCash) {
                           $icon = 'bi-arrow-up-circle-fill text-success';
                       }

                       // Cálculo de monto y signo
                       if ($isCredit) {
                           $sign = '-';
                           $displayAmount = $transaction->amount;
                       } elseif ($isCash) {
                           $sign = '+';
                           $displayAmount = $transaction->amount * 0.10; // Mostrar solo 10%
                       } elseif ($isTransfer) {
                           $sign = '→';
                           $displayAmount = $transaction->amount;
                       } else {
                           $sign = '';
                           $displayAmount = $transaction->amount;
                       }
                   @endphp

                   <tr class="border-bottom align-middle">
                       <td><i class="bi {{ $icon }} fs-5"></i></td>
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
                       <td class="text-end fw-bold
                           {{ ($isTransfer || $isCredit) ? 'text-danger' : 'text-success' }}">
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
@endsection
