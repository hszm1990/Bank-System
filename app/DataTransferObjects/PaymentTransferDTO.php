<?php

namespace App\DataTransferObjects;

class PaymentTransferDTO
{
    public function __construct(
        public int $source_card_number,
        public int $destination_card_number,
        public int $amount
    )
    {
    }
}
