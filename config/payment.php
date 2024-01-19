<?php

return [
    'methods' => [
        'CardPayment' => App\Services\CardPaymentService::class,
        'AccountPayment' => App\Services\AccountPaymentService::class
    ],
    'min' => 1000,
    'max' => 10000000,
    'fee' => [
        'CardPayment' => 500,
        'AccountPayment' => 500
    ]
];
