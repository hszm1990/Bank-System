<?php

namespace App\Providers;

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
        $this->app->singleton(BasePayment::class, function ($app, $params) {
            if (!array_key_exists($params['method'], config('payment.methods'))) {
                throw new \Exception('Method is not valid.');
            }
            return $app->make(config('payment.methods')[$params['method']]);
        });
    }
}
