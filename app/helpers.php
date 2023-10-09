<?php

use App\Models\Organization;
use Stripe\Plan;
use Stripe\StripeClient;

/**
 * @param $amount
 * @return int|string
 */
function formatCurrency($amount): int|string
{

    if (!$amount) {
        $amount = 0;
    }

    if (!is_numeric($amount)) {
        return $amount;
    }

    return (new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY))->formatCurrency(
        $amount,
        config("cashier.currency"),
    );
}

function getPlanNameByStripePlan(Plan $plan): string
{

    if ($plan->interval_count === 3) {
        return "Trimestral";
    } else {
        if ($plan->interval === "year") {
            return "Anual";
        } else {
            return "Mensual";
        }
    }
}

function getSubscriptionNameForUser(): string
{

    if (isSubscribed()) {
        $subscription = auth()->user()->organization->subscription();
        $key = config('cashier.secret');
        $stripe = new StripeClient($key);
        $plan = $stripe->plans->retrieve($subscription->stripe_price);

        return getPlanNameByStripePlan($plan);
    }

    return "N/D";
}


function isSubscribed(): bool
{
    // Determine if the Organization model has a given subscription.
    return auth()->check() && auth()->user()->organization->subscribed();
}