<?php

use App\Services\AccountPaymentService;
use App\Services\CardPaymentService;

return [
    'methods' => [
        'CardPayment' => CardPaymentService::class,
        'AccountPayment' => AccountPaymentService::class
    ],
    'min' => 1000,
    'max' => 10000000,

];
