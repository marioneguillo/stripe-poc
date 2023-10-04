<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


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
        });

    Route::group(["middleware" => "is_stripe_customer"], function () {
        Route::get('/billing/portal', function () {
            return auth()->user()->redirectToBillingPortal(route('dashboard'));
        })->name("billing.portal");
    });

    Route::get('/user/invoice/{invoice}', function (Request $request, $invoiceId) {
        return auth()->user()->downloadInvoice($invoiceId, [], 'my-invoice');
    });


    Route::get('/product-checkout', function (Request $request) {
        return $request->user()->checkout(['price_1Nx6vxGyN9hnCK0xyg0w60uU' => 6], []);
    });
});



Route::get("/home")->name("home");


require __DIR__ . '/auth.php';
