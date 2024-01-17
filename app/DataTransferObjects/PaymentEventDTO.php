<?php

namespace App\DataTransferObjects;

use App\Models\User;

class PaymentEventDTO
{
    public function __construct(
        public User $sourceUser,
        public User $destinationUser,
        public int $amount
    )
    {
    }
}
