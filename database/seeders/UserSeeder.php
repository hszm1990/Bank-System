<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\TransactionFee;
use App\Models\User;
use Database\Factories\TransactionFeeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)
            ->has(Account::factory(2)
                ->has(Card::factory(3))
            )
            ->create();

        $cards = Card::all();

        for ($i = 0; $i < 50; $i++) {
            $transaction = Transaction::factory()->sequence([
                'source_card_id' => $cards->random()->id,
                'destination_card_id' => $cards->random()->id,
                'type' => 'credit'
            ])->create();

            Transaction::factory()->create([
                'source_card_id' => $transaction->destination_card_id,
                'destination_card_id' => $transaction->source_card_id,
                'amount' => $transaction->amount,
                'type' => 'debit',
            ]);

            TransactionFee::factory()->for($transaction)->create();
        }
    }
}
