<?php

namespace App\Services;

use App\DataTransferObjects\PaymentTransferDTO;
use App\Exceptions\BalanceShortageException;
use Illuminate\Support\Facades\DB;

class CardPaymentService extends BasePayment
{
    protected int $fee = 500;

    /**
     * @throws BalanceShortageException
     */
    public function transfer(PaymentTransferDTO $paymentDto): void
    {
        DB::transaction(function () use ($paymentDto) {
            $sourceCardNumber = $paymentDto->source_card_number;
            $destinationCardNumber = $paymentDto->destination_card_number;
            $this->setAmount($paymentDto->amount);

            $cards = $this->findCardsByNumber([$sourceCardNumber, $destinationCardNumber]);
            $sourceCard = $cards[$sourceCardNumber];
            $destinationCard = $cards[$destinationCardNumber];

            $accounts = $this->findAccounts($cards->pluck('account_id')->toArray());
            $sourceAccount = $accounts[$sourceCard->account_id];
            $destinationAccount = $accounts[$destinationCard->account_id];

            $this->checkBalance($sourceAccount);

            $this->updateBalance($sourceAccount, $destinationAccount);

            $this->createTransactions($sourceCard->id, $destinationCard->id);

            $this->dispatchEvent($sourceAccount->user, $destinationAccount->user);
        });
    }
}
