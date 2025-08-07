<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Gestión de Transacciones JSON</h1>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            <?php echo e(session('info')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            <?php echo session('error'); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow"
                style="z-index: 1050;" role="alert">
            <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    
    <div class="card mb-4">
        <div class="card-header">Nueva Transacción</div>
        <div class="card-body">
            <form action="<?php echo e(route('json.save')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="amount" class="form-label">Monto:</label>
                    <input type="number" name="amount" id="amount" class="form-control" required step="0.01" min="0.01">
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">ID de Usuario (opcional):</label>
                    <input type="number" name="user_id" id="user_id" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Método de Pago:</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                      <option value="Efectivo">Efectivo</option>  
                      <option value="Crédito">Crédito</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Guardar en JSON</button>
            </form>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header">Transacciones Pendientes</div>
        <div class="card-body">
            <?php if(!empty($transactions)): ?>
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
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e(number_format($transaction['amount'], 2)); ?></td>
                                <td><?php echo e($transaction['user_id'] ?? 'No registrado'); ?></td>
                                <td><?php echo e(ucfirst($transaction['payment_method'])); ?></td>
                                <td>
                                    <?php if(isset($transaction['created_at'])): ?>
                                        <?php echo e(\Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i')); ?>

                                    <?php else: ?>
                                        Sin fecha
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No hay transacciones pendientes.</p>
            <?php endif; ?>
        </div>
    </div>

    
    <form action="<?php echo e(route('json.process')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-success">Procesar Transacciones</button>
    </form>

   

<script src="<?php echo e(asset('js/alerts.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fany/POS_BLOCKCHAIN/cryptotrade/cryptotrade/resources/views/pay/json.blade.php ENDPATH**/ ?>