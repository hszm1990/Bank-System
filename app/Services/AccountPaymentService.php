<?php

namespace App\Services;

use App\DataTransferObjects\PaymentTransferDTO;

class AccountPaymentService extends BasePayment
{
    protected int $fee = 200;

    public function transfer(PaymentTransferDTO $paymentDto)
    {
    }
}
