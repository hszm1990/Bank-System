<?php

namespace App\Services;

use App\Contracts\AccountRepositoryInterface;
use App\Contracts\CardRepositoryInterface;
use App\Contracts\TransactionFeeRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\DataTransferObjects\PaymentEventDTO;
use App\DataTransferObjects\PaymentTransferDTO;
use App\Events\PaymentEvent;
use App\Exceptions\BalanceShortageException;
use App\Models\Transaction;

abstract class BasePayment
{
    public function __construct(
        private CardRepositoryInterface           $cardRepository,
        private AccountRepositoryInterface        $accountRepository,
        private TransactionRepositoryInterface    $transactionRepository,
        private TransactionFeeRepositoryInterface $transactionFeeRepository,
    )
    {
    }

    private int $amount;
    protected int $fee = 0;

    abstract public function transfer(PaymentTransferDTO $paymentDto);

    protected function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function setFee($fee): void
    {
        $this->fee = $fee;
    }

    protected function getAmount(): int
    {
        return $this->amount;
    }

    protected function getFee(): int
    {
        return $this->fee;
    }

    protected function getTotal(): int
    {
        return $this->getAmount() + $this->getFee();
    }

    protected function findCardsByNumber(array $numbers)
    {
        return $this->cardRepository->getByNumbers($numbers)->keyBy('card_number');
    }

    protected function findAccounts($ids)
    {
        return $this->accountRepository
            ->findByIds($ids)
            ->keyBy('id');
    }

    protected function findAccountsByNumber(array $numbers)
    {
        return $this->accountRepository
            ->findByNumbers($numbers)
            ->keyBy('account_number');
    }

    protected function incrementBalance($account)
    {
        return $this->accountRepository->incrementBalance($account, $this->getAmount());
    }

    protected function decrementBalance($account)
    {
        return $this->accountRepository->decrementBalance($account, $this->getTotal());
    }

    protected function updateBalance($sourceAccount, $destinationAccount): void
    {
        $this->decrementBalance($sourceAccount);
        $this->incrementBalance($destinationAccount);
    }

    /**
     * @throws BalanceShortageException
     */
    protected function checkBalance($account): void
    {
        if ($account->balance < $this->getAmount()) {
            throw new BalanceShortageException(trans("messages.shortage_balance"));
        }
    }

    protected function createTransaction(int $sourceCardId, int $destinationCardId, int $amount, $type)
    {
        return $this->transactionRepository->create([
            'source_card_id' => $sourceCardId,
            'destination_card_id' => $destinationCardId,
            'amount' => $amount,
            'type' => $type
        ]);
    }

    protected function createTransactionFee($transactionId)
    {
        return $this->transactionFeeRepository->create([
            'transaction_id' => $transactionId,
            'amount' => $this->getFee()
        ]);
    }

    protected function createSourceTransaction($sourceCardId, $destinationCardId)
    {
        return $this->createTransaction($sourceCardId, $destinationCardId, $this->getTotal(), Transaction::DEBIT);
    }

    protected function createDestinationTransaction($sourceCardId, $destinationCardId)
    {
        return $this->createTransaction($sourceCardId, $destinationCardId, $this->getAmount(), Transaction::CREDIT);
    }

    protected function createTransactions($sourceCardId, $destinationCardId): void
    {
        $transaction = $this->createSourceTransaction($sourceCardId, $destinationCardId);
        $this->createDestinationTransaction($destinationCardId, $sourceCardId);
        $this->createTransactionFee($transaction->id);
    }

    protected function dispatchEvent($sourceUser, $destinationUser): void
    {
        PaymentEvent::dispatch(new PaymentEventDTO(
            $sourceUser, $destinationUser, $this->getAmount()
        ));
    }
}
