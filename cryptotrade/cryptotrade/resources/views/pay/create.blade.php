<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function toggleUserOptions() {
        const isRegistered = document.querySelector('input[name="is_registered"]:checked').value;
        const show = isRegistered == '1';

        document.getElementById('user_id_group').style.display = show ? 'block' : 'none';
        document.getElementById('payment_method_group').style.display = show ? 'block' : 'none';

        // Activar o desactivar "required"
        document.querySelectorAll('input[name="payment_method"]').forEach(el => {
            el.required = show;
        });
        document.querySelector('input[name="user_id"]').required = show;
    }
</script>

</head>
<body>
<div class="container mt-5">
    <h2>Formulario de Pago</h2>

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif


    

    <form method="POST" action="{{ route('pay.store') }}" class="card p-4 bg-white shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Monto a pagar:</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">¿Eres un usuario registrado?</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_registered" value="1" onchange="toggleUserOptions()" required>
                <label class="form-check-label">Sí</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_registered" value="0" onchange="toggleUserOptions()" required>
                <label class="form-check-label">No</label>
            </div>
        </div>

        <div class="mb-3" id="user_id_group" style="display:none;">
            <label class="form-label">ID del Usuario Registrado:</label>
            <input type="number" name="user_id" class="form-control">
        </div>

        <div class="mb-3" id="payment_method_group" style="display:none;">
            <label class="form-label">Método de pago:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" value="credits" required>
                <label class="form-check-label">Créditos</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" value="cash" required>
                <label class="form-check-label">Efectivo</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Pagar</button>
    </form>
</div>

<script src="{{ asset('js/alerts.js') }}"></script>
</body>
</html>
