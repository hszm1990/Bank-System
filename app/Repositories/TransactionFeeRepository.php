<?php

namespace App\Repositories;

use App\Contracts\TransactionFeeRepositoryInterface;
use App\Models\TransactionFee;

class TransactionFeeRepository extends Repository implements TransactionFeeRepositoryInterface
{
    public function model(): string
    {
        return TransactionFee::class;
    }

    public function create(array $transactionFee)
    {
        return $this->model->create([
            'transaction_id' => $transactionFee['transaction_id'],
            'amount' => $transactionFee['amount'],
        ]);
    }
}
