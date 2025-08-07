<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Crear Usuario</h2>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>


        <form method="POST" action="<?php echo e(route('users.store')); ?>" class="card p-4 shadow-sm bg-white">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="kind">Tipo de usuario:</label>
                <select name="kind" id="kind" class="form-control">
                    <option value="1" selected>Usuario</option>
                    <option value="2">Super Usuario</option>
                </select>
            </div>

        
            <div class="d-flex">
                <button type="submit" class="btn btn-primary me-2">Guardar</button>
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <script src="<?php echo e(asset('js/alerts.js')); ?>"></script>

</body>
</html>
<?php /**PATH /home/fany/POS_BLOCKCHAIN/cryptotrade/cryptotrade/resources/views/users/create.blade.php ENDPATH**/ ?>