<?php

namespace App\Repositories;

use App\Contracts\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends Repository implements TransactionRepositoryInterface
{

    public function model(): string
    {
        return Transaction::class;
    }

    public function create(array $transaction)
    {
        return $this->model->create([
            'source_card_id' => $transaction['source_card_id'],
            'destination_card_id' => $transaction['destination_card_id'],
            'amount' => $transaction['amount'],
            'type' => $transaction['type']
        ]);
    }

    public
    function mostUserTransactions(int $userCount,int $minute)
    {
        return Transaction::query()
            ->select('user_id', DB::raw('count(user_id) as total'))
            ->join('cards', 'cards.id', '=', 'source_card_id')
            ->join('accounts', 'accounts.id', '=', 'cards.account_id')
            ->where('transactions.created_at', '>=', now()->subMinutes($minute))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit($userCount)
            ->get();
    }

    public function getLatestTransactionsUser($userIds, $count = 10)
    {
        $subQuery = Transaction::query()
            ->selectRaw(
                'transactions.*, user_id ,ROW_NUMBER()
                over(partition by user_id ORDER BY transactions.created_at DESC) as rn'
            )
            ->join('cards', 'source_card_id', 'cards.id')
            ->join('accounts', 'account_id', 'accounts.id')
            ->whereIn('user_id', $userIds);
        return DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->select('user_id', 'source_card_id',
                'destination_card_id',
                'amount',
                'type', 'created_at')
            ->mergeBindings($subQuery->getQuery())
            ->where('rn', '<=', $count)
            ->get();
    }
}
