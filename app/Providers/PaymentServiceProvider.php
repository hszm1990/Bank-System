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
        $this->app->bind(BasePayment::class, function ($app, $params) {
            $method = $params['method'];
            if (!array_key_exists($method, $methods = config('payment.methods'))) {
                throw new \Exception('Method is not valid.');
            }
            $paymentMethod = $app->make($methods[$method]);
            $paymentMethod->setFee(config('payment.fee')[$method]);
            return $paymentMethod;
        });
    }
}
