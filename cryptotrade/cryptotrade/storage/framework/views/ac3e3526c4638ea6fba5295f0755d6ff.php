<?php $__env->startSection('content'); ?>
<body class="bg-light">
    <div class="vista-usuarios container mt-5">
        <h2 class="mb-4">Usuarios</h2>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

       

        <table class="table table-bordered table-striped mt-3">
            <thead class="encabezado-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->id); ?></td>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>$<?php echo e(number_format($user->balance, 2)); ?></td>
                        <td class="d-flex gap-1">
                            <!-- Botón de Editar (corregido) -->
                            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="action-button btn-alter text-decoration-none">
                                <div class="svg-wrapper-1">
                                    <div class="svg-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="icon">
                                            <path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41L18.37 3.29c-.39-.39-1.02-.39-1.41 0L15.13 5.12l3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </div>
                                </div>
                                <span>Editar</span>
                            </a>

                            <!-- Botón de Eliminar -->
                            <?php if($user->kind != 2): ?>
                                <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="action-button btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <div class="svg-wrapper-1">
                                            <div class="svg-wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M9 3v1H4v2h16V4h-5V3H9zm2 4v12h2V7h-2zM7 7v12h2V7H7zm8 0v12h2V7h-2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span>Eliminar</span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <script src="<?php echo e(asset('js/alerts.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fany/POS_BLOCKCHAIN/cryptotrade/cryptotrade/resources/views/users/index.blade.php ENDPATH**/ ?>