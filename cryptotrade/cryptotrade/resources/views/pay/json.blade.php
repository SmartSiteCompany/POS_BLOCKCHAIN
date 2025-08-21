@extends('layouts.app')

@section('content')
<div class="container">
    <div class="title-with-button">
        <h1>Gestión de Transacciones JSON</h1>
        
    </div>

    {{-- Mensajes --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow" style="z-index: 1050;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@elseif(session('info'))
    <div class="alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow" style="z-index: 1050;" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow" style="z-index: 1050;" role="alert">
        {!! session('error') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@elseif(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow" style="z-index: 1050;" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif


   <!-- Contenedor de dos columnas -->
<div class="two-column-container">
    
   <!-- Columna izquierda: Nueva transacción -->
<div class="left-column styled-card">
    <div class="card cart">
        <label class="title">Nueva Transacción</label>

        <div class="steps">
            <div class="step">
            

                <!-- Formulario de monto y método de pago -->
                <div class="promo">
                    <span>DETALLES DE LA TRANSACCIÓN</span>
                    <form action="{{ route('json.save') }}" method="POST" class="form">
                        @csrf
                        <input type="number" name="amount" id="amount" placeholder="Monto" step="0.01" min="0.01" required class="input_field">

                        <input type="number" name="user_id" id="user_id" placeholder="ID de Usuario (opcional)" class="input_field">

                        <select name="payment_method" id="payment_method" required class="input_field">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Crédito">Crédito</option>
                        </select>

                        <button class="btn_json" type="submit">Guardar en JSON</button>
                    </form>
                </div>
                <hr>

                <!-- Resumen de transacción (opcional, puede actualizarse con JS) -->
                <div class="payments">
    <span>RESUMEN</span>
    <div class="details">
    <span>Subtotal:</span>
    <span id="subtotal">$0.00</span>

    <span id="cashback-label">Cashback:</span>
    <span id="cashback">$0.00</span>

    <span>Saldo Final:</span>
    <span id="final-balance">$0.00</span>
</div>

</div>

            </div>
        </div>
    </div>
</div>


        <!-- Columna derecha: Cargar JSON -->
        <div class="right-column styled-card">
            <div class="card cart">
            <form action="{{ route('transactions.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-box">
                    <div class="header">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <p>Selecciona un archivo JSON</p>
                    </div>
                    <label for="file" class="footer">
                        <svg fill="#000000" viewBox="0 0 32 32">
                            <path d="M15.331 6H8.5v20h15V14.154h-8.169z"></path>
                            <path d="M18.153 6h-.009v5.342H23.5v-.002z"></path>
                        </svg> 
                        <p id="file-name">No se ha seleccionado archivo</p>
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M5.16565 10.1534C5.07629 8.99181 5.99473 8 7.15975 8H16.8402C18.0053 8 18.9237 8.9918 18.8344 10.1534L18.142 19.1534C18.0619 20.1954 17.193 21 16.1479 21H7.85206C6.80699 21 5.93811 20.1954 5.85795 19.1534L5.16565 10.1534Z" stroke="#000000" stroke-width="2"></path>
                            <path d="M19.5 5H4.5" stroke="#000000" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M10 3C10 2.44772 10.4477 2 11 2H13C13.5523 2 14 2.44772 14 3V5H10V3Z" stroke="#000000" stroke-width="2"></path>
                        </svg>
                    </label>
                    <div class="text-center mt-3">
                    <button type="submit" class="btn-upload">Cargar</button>
                </div>
                    <input id="file" type="file" name="json_file" accept=".json" required>
                </div>
                
            </form>
            </div>
        </div>

    </div>

 {{-- Tabla con transacciones pendientes --}}
    {{-- Tabla con transacciones pendientes --}}
<div class="card mb-4">
    <div class="card-header header-process">
        <span>Transacciones Pendientes</span>
        <form action="{{ route('json.process') }}" method="POST">
            @csrf
            <button type="submit" class="btn-upload">Procesar Transacciones</button>
        </form>
    </div>

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
                            <td>
                                @if(isset($transaction['category']) && $transaction['category'] === 'transfer')
                                    De: {{ $transaction['sender_id'] }} <br>
                                    Para: {{ $transaction['receiver_id'] }}
                                @else
                                    {{ $transaction['user_id'] ?? 'No registrado' }}
                                @endif
                            </td>
                            <td>
                                @if(isset($transaction['category']) && $transaction['category'] === 'transfer')
                                    Transferencia
                                @else
                                    {{ isset($transaction['payment_method']) ? ucfirst($transaction['payment_method']) : 'N/A' }}
                                @endif
                            </td>
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

<script src="{{ asset('js/alerts.js') }}"></script>
<script src="{{ asset('js/resumen.js') }}"></script>
@endsection
