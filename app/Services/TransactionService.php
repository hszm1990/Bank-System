<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;

class TransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private UserRepository        $userRepository,
    )
    {
    }

    public
    function getTopUsersTransaction(int $userCount, $transactionsCount, int $minute)
    {
        $topUserTransactions = $this->transactionRepository->mostUserTransactions($userCount, $minute)->keyBy('user_id');
        $users = $this->userRepository->findByIds($topUserTransactions->pluck('user_id')->toArray());
        $transactions = $this->transactionRepository->getLatestTransactionsUser($users->pluck('id'), $transactionsCount);
        return $users->map(function ($user) use ($transactions, $topUserTransactions) {
            $user->transactions = $transactions->where('user_id', $user->id)->values();
            $user->topTransactions = $topUserTransactions[$user->id]->total;
            return $user;
        })->sortByDesc('topTransactions')->values();
    }
}
