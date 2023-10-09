<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;
use function Illuminate\Events\queueable;


class Organization extends Model
{
    use HasFactory, Billable;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    protected static function booted(): void
    {
        static::updated(queueable(function (Organization $organization) {
            if ($organization->hasStripeId()) {
                $organization->syncStripeCustomerDetails();
            }
        }));
    }
}