<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageSubscriptionController extends Controller
{
    public function __invoke(Request $request)
    {
        $checkout = $request->user()
            ->organization
            ->newSubscription('default', config('stripe.price_id'))
            ->checkout();

        return Inertia::render('ManageSubscription', [
            'stripeKey' => config('cashier.key'),
            'checkoutSessionId' => $checkout->id,
        ]);
    }
}