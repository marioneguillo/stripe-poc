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

    <div class="py-6">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 mx-auto">
                <div class="flex flex-wrap -m-4">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 xl:w-1/3 md:w-1/3 w-full">
                            <div
                                class="h-full p-6 rounded-lg border-2 border-blue-500 flex flex-col relative overflow-hidden">
                                <?php if($plan->interval_count !== 3): ?>
                                <!--
                                <span
                                        class="bg-purple-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">
                                        POPULAR
                                    </span>
                                -->
                                <?php endif; ?>
                                <h2 class="text-sm tracking-widest title-font mb-1 font-medium">
                                    <?php echo e(getPlanNameByStripePlan($plan)); ?>

                                </h2>
                                <h1
                                    class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                                    <span><?php echo e(formatCurrency($plan->amount / 100)); ?></span>
                                </h1>
                                <p class="flex items-center text-gray-600 mb-2">
                                    <span
                                        class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-blue-400 text-white rounded-full flex-shrink-0">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2.5" class="w-3 h-3"
                                            viewBox="0 0 24 24">
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                    </span>
                                    Acceso a...
                                </p>
                                <form method="post" action="<?php echo e(route('billing.process_subscription')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="price_id" value="<?php echo e($plan->id); ?>" />
                                    <button type="submit"
                                        class="flex items-center mt-auto text-white bg-blue-400 border-0 py-2 px-4 w-full focus:outline-none hover:bg-blue-400 rounded">
                                        Contratar
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-auto"
                                            viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    </div>



    <div class="py-6">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 mx-auto">
                <div class="flex flex-wrap -m-4">
                        <div class="p-4 xl:w-1/3 md:w-1/3 w-full">
                            <div
                                class="h-full p-6 rounded-lg border-2 border-blue-500 flex flex-col relative overflow-hidden">
                                <h1 class="text-xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                                    <span> Añadir candidaturas extras</span>
                                </h1>
                                <p class="flex items-center text-gray-600 mb-2">
                                    <span
                                        class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2.5" class="w-3 h-3"
                                            viewBox="0 0 24 24">
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                    </span>
                                    € 7 por candidatura
                                </p>

                                <form method="post" action="<?php echo e(route('billing.process_extra_subscription')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="price_id" value="price_1Nx6vxGyN9hnCK0xyg0w60uU" />
                                    <input type="number" name="quantity" id="quantity">
                                    <button type="submit"
                                        class="flex items-center mt-4 text-white bg-blue-400 border-0 py-2 px-4 w-full focus:outline-none hover:bg-blue-400 rounded">
                                        Pagar
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-auto"
                                            viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </section>
    </div>



 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/front/billing/plans.blade.php ENDPATH**/ ?>