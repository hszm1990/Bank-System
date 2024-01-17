<?php

namespace App\Providers;

use App\Factories\Payment\PaymentFactory;
use App\Services\BasePayment;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        PaymentFactory::$instances = config('payment.methods');
        $this->app->singleton(BasePayment::class, function ($app, $params) {
            return $app->make(PaymentFactory::make($params['method']));
        });
    }
}
