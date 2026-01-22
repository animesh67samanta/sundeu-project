<?php

namespace App\Providers;

use App\Contracts\PaymentInterface;
use App\Services\PayPalService;
use Illuminate\Support\ServiceProvider;
use App\Services\StripeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->bind(PaymentInterface::class, StripeService::class);
        // $this->app->bind(PaymentInterface::class, PayPalService::class);

    }

    
    public function boot(): void
    {
        //
    }
}
