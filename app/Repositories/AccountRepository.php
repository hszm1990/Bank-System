<?php

namespace App\Repositories;

use App\Contracts\AccountRepositoryInterface;
use App\Models\Account;

class AccountRepository extends Repository implements AccountRepositoryInterface
{

    public function model(): string
    {
        return Account::class;
    }

    public function findByIds($ids)
    {
        return $this->model->findMany($ids);
    }

    public function findByNumbers(array $numbers)
    {
        return $this->model->whereIn('account_number', $numbers)->get();
    }

    public function incrementBalance($account, $amount)
    {
        return $account->increment('balance', $amount);
    }

    public function decrementBalance($account, $amount)
    {
        return $account->decrement('balance', $amount);
    }
}
