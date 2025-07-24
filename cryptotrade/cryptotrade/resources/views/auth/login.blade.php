<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>
<body>

  @if(session('error'))
    <div class="alert-container">
      {{ session('error') }}
    </div>
  @endif

  <div class="login-card">
    <div class="login-title">BLOCKCHAIN</div>
    <div class="login-subtitle">Iniciar sesion</div>

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf

      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus />
      </div>

      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Contraseña" required />
      </div>

      <button type="submit" class="btn btn-login">Continuar</button>
    </form>

    <div class="small-text">Debe de tener una cuenta para poder continuar.</div>

    <div class="register-link">
      ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/scriptlogin.js') }}"></script>
</body>
</html>