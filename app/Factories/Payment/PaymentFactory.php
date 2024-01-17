<?php

namespace App\Factories\Payment;

class PaymentFactory
{
    public static array $instances;

    public static function make($object)
    {
        if (!array_key_exists($object, static::$instances)) {
            throw new \Exception('Method is not valid.');
        }
        return static::$instances[$object];
    }
}
