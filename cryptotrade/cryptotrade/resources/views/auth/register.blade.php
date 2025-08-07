<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 position-relative">

    <!-- Blob animado -->
    <div class="blob"></div>

    <!-- Tarjeta de registro -->
    <div class="card">
        <div class="login-title">BLOCKCHAIN</div>
        <div class="login-subtitle">Registro de Usuario</div>

        @if ($errors->any())
            <div class="alert-container">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nombre" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Correo electrónico" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
            </div>

            <button type="submit" class="btn btn-login">Registrarse</button>
        </form>

        <div class="small-text">¿Ya tienes cuenta?</div>

        <div class="register-link">
            <a href="{{ route('login') }}">Inicia sesión aquí</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scriptlogin.js') }}"></script>
</body>
</html>

