<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ManageSubscriptionController;


Route::get('/', function () {
    return view('welcome');
});

Route::group(["middleware" => "auth"], function () {
    Route::get("/dashboard", function () {
        return view('dashboard');
    })->name("dashboard");

    Route::controller(BillingController::class)
        ->as("billing.")
        ->prefix("billing")
        ->group(function () {
            Route::get("/payment-method", "paymentMethodForm")->name("payment_method_form");
            Route::post("/payment-method", "processPaymentMethod")->name("payment_method");
            Route::get("/plans", "plans")->name("plans");
            Route::get("/plans-embebed", "plansEmbebed")->name("plansEmbebed");
            Route::post("/subscribe", "processSubscription")->name("process_subscription");
            Route::post("/extra-subscribe", "processExtraSubscription")->name("process_extra_subscription");
            Route::get("/subscription", "mySubscription")->name("my_subscription")->middleware("is_stripe_customer");
            Route::get("/invoices", "invoices")->name("invoices")->middleware("is_stripe_customer");
            Route::get("/create-customer", "createCustomer")->name("createCustomer");
            Route::get("/info-customer", "getInfoCustomer")->name("getInfoCustomer");

        });

    Route::group(["middleware" => "is_stripe_customer"], function () {
        Route::get('/billing/portal', function () {
            return auth()->user()->organization->redirectToBillingPortal(route('dashboard'));
        })->name("billing.portal");
    });

    Route::get('/user/invoice/{invoice}', function (Request $request, $invoiceId) {
        return auth()->user()->downloadInvoice($invoiceId, [], 'my-invoice');
    });


    Route::get('/product-checkout', function (Request $request) {
        return $request->user()->checkout(['price_1Ny9T9GyN9hnCK0xkJcjLw3t' => 7], []);
    });

});



Route::get("/home")->name("home");
Route::get('/subscription', ManageSubscriptionController::class)->name('subscription');


require __DIR__ . '/auth.php';
