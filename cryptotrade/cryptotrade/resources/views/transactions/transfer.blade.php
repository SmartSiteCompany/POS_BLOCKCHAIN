@extends('layouts.app')

@section('content')
<div class="transfer-container mt-5">
    <h2 class="transfer-title">Transferir monedas</h2>

    <div class="remitente-info mb-4 d-flex justify-content-between align-items-center">
        <div class="sender-name">
            <h5 class="mb-1"><strong>Remitente:</strong> <span id="sender-name">{{ $users->first()->name }}</span></h5>
        </div>
        <div class="sender-balance text-end">
            <p class="mb-0"><strong>Saldo actual:</strong> $<span id="sender-balance">{{ number_format($users->first()->balance, 2) }}</span></p>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow" style="z-index: 1050;" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    {{-- Mensajes de alerta --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
        style="z-index: 1050;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
        style="z-index: 1050;" role="alert">
        {!! session('error') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <!-- Vista animada de transferencia -->
    <div class="transfer-visual mb-5">
        <!-- Usuario Origen (Izquierda) -->
        <div class="usuario-box text-center origen">
            <img id="from-photo" src="{{ asset('images/usuario.png') }}" class="user-photo mb-2" alt="Remitente">
            <div><strong id="from-name">{{ $users->first()->name }}</strong></div>
            <small class="text-muted">Cuenta Origen</small>
        </div>

        <!-- Flecha animada (Centro) -->
        <div class="arrow-container">
            <div id="arrowAnim">
                <div class="arrowSliding"><div class="arrow"></div></div>
                <div class="arrowSliding delay1"><div class="arrow"></div></div>
                <div class="arrowSliding delay2"><div class="arrow"></div></div>
                <div class="arrowSliding delay3"><div class="arrow"></div></div>
            </div>
        </div>

        <!-- Usuario Destino (Derecha) -->
        <div class="usuario-box text-center destino">
            <img id="to-photo" src="{{ asset('images/usuario.png') }}" class="user-photo mb-2" alt="Destinatario">
            <h5> <span id="recipient-name">---</span></h5>
            <small class="text-muted">Cuenta Destino</small>
        </div>
    </div>

    <form method="POST" action="{{ route('transactions.transfer') }}" class="transfer-form">
        @csrf

        <!-- Remitente (autenticado) -->
        <input type="hidden" name="from_user" value="{{ auth()->user()->id }}">

        <!-- Destinatario (rellenado dinámicamente) -->
        <input type="hidden" name="to_user" id="to-user-hidden">

        <div class="form-group">
    <label for="recipient-id" class="form-label">ID del destinatario</label>
    
    <input type="number" class="form-input" id="recipient-id" placeholder="Escribe el ID del destinatario">

    <button type="button" id="buscar-destinatario" class="btn-lupa" title="Buscar">
        <!-- Ícono SVG de lupa -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#555" viewBox="0 0 24 24">
            <path d="M10 2a8 8 0 105.293 14.293l5.707 5.707 1.414-1.414-5.707-5.707A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z"/>
        </svg>
    </button>
</div>

        <div class="form-group">
            <label for="amount">Cantidad</label>
            <input type="number" name="amount" step="0.01" min="0.01" required class="form-input">
        </div>

        <button type="submit" class="btn-submit">Transferir</button>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('buscar-destinatario').addEventListener('click', async function () {
    const id = document.getElementById('recipient-id').value;

    if (!id) {
        alert('Ingresa un ID válido.');
        return;
    }

    try {
        const response = await fetch(`/buscar-usuario/${id}`);
        if (!response.ok) throw new Error('No encontrado');

        const data = await response.json();
        document.getElementById('recipient-name').textContent = data.name;
        document.getElementById('to-user-hidden').value = data.id;
    } catch (err) {
        alert('Error: ' + err.message);
        document.getElementById('recipient-name').textContent = '---';
        document.getElementById('to-user-hidden').value = '';
    }
});
</script>

<script src="{{ asset('js/alerts.js') }}"></script>
@endpush
