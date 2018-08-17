<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stripe\Stripe;
use Stripe\Plan as StripePlan;
use Cache;
class Plan extends Model
{
    //

    public static function getStripePlans()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            // Fetch all the Plans and cache it
            return Cache::remember('stripe.plans', 60*24, function() {
                return StripePlan::all()->data;
            });
        } catch ( \Exception $e ) {
            return false;
        }
    }
}
