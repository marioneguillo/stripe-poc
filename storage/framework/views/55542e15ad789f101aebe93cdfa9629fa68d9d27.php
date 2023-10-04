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
            Método de pago
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap -m-4">
                        <div class="p-4 lg:w-1/2 md:w-full">
                            <div class="relative mb-4">
                                <input placeholder="Titular" type="email" id="card-holder-name" name="card-holder-name"
                                    class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 
                                    focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3
                                     leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-4 lg:w-1/2 md:w-full">
                            <div class="relative mb-4">
                                <select class="form-select appearance-none block w-full" id="country" name="country">
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Elements Placeholder -->
                    <div id="card-element"></div>

                    <button id="card-button" data-secret="<?php echo e($intent->client_secret); ?>"
                        class="text-white bg-indigo-500 border-0 py-2 px-6 mt-5 focus:outline-none hover:bg-indigo-600 rounded">
                        Actualizar método de pago
                    </button>
                </div>

                <form id="payment_method_form" method="post" action="<?php echo e(route('billing.payment_method')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="card_holder_name" name="card_holder_name" />
                    <input type="hidden" id="pm" name="pm" />
                    <input type="hidden" id="country_id" name="country_id" />
                </form>
            </div>
        </div>
    </div>

    <?php if(count($currentMethod) > 0): ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap -m-4">
                        <div class="p-4 md:w-1/3">
                            <div class="relative mb-4">
                                Metodos de pago guardados:
                            </div>
                        </div>
                        <div class="p-4 md:w-2/3">
                            <div class="relative mb-4">
                            <?php $__currentLoopData = $currentMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p>ID: 
                                <span><?php echo e($method['id']); ?></span>
                                </p>
                                <p> Tipo:
                                <span><?php echo e($method['brand']); ?></span>
                                </p>
                                <p> Número de tarjeta:
                                <span>**** **** ***** <?php echo e($method['last_four']); ?></span>
                                </p>
                                <p> Año de expiración: 
                                <span><?php echo e($method['exp_year']); ?></span>
                                </p>
                                <br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('<?php echo e(config('cashier.key')); ?>');

            const elements = stripe.elements();
            const cardElement = elements.create('card');

            cardElement.mount('#card-element');

            const country = document.getElementById('country');
            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            cardButton.addEventListener('click', async (e) => {
                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: cardHolderName.value
                            }
                        }
                    }
                );

                if (error) {
                    alert(error.message)
                } else {
                    document.getElementById("pm").value = setupIntent.payment_method;
                    document.getElementById("card_holder_name").value = cardHolderName.value;
                    document.getElementById("country_id").value = country.value;
                    document.getElementById("payment_method_form").submit();
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/front/billing/payment_method_form.blade.php ENDPATH**/ ?>