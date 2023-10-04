<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, [] + (isset($attributes) ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Planes disponibles
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 mx-auto">
            <table>
                <tr>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th>Subscripcion Id</th>
                    <th>Precio</th>
                    <th>Recibo</th>
                    <th>Factura</th>
                </tr>
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($invoice->id); ?></td>
                            <td><?php echo e($invoice->date()->toFormattedDateString()); ?></td>
                            <td><?php echo e($invoice->subscription); ?></td>
                            <td><?php echo e($invoice->total()); ?></td>
                            <td><a href="/user/invoice/<?php echo e($invoice->id); ?>">Download</a></td>
                            <td><a href="<?php echo e($invoice->invoice_pdf); ?>">Download</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        </section>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>



<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
#t01 {
  width: 100%;    
  background-color: #f1f1c1;
}
</style>
<?php /**PATH /var/www/html/resources/views/front/billing/invoices.blade.php ENDPATH**/ ?>