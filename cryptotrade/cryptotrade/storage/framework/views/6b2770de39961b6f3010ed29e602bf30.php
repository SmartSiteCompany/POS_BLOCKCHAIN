<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>" />
</head>
<body>

  <?php if(session('error')): ?>
    <div class="alert-container">
      <?php echo e(session('error')); ?>

    </div>
  <?php endif; ?>

  <div class="card login-card">
    <div class="bg"></div>
    <div class="blob"></div>

    <div class="content">
      <div class="login-title">BLOCKCHAIN</div>
      <div class="login-subtitle">Iniciar sesión</div>

      <form method="POST" action="<?php echo e(route('login.submit')); ?>">
        <?php echo csrf_field(); ?>

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
        ¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate aquí</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo e(asset('js/scriptlogin.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/fany/POS_BLOCKCHAIN/cryptotrade/cryptotrade/resources/views/auth/login.blade.php ENDPATH**/ ?>