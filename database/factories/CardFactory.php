<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'card_number' => $this->generate()
        ];
    }
    public function generate(): string
    {
        $card = Collection::times(15)->map(function () {
            return mt_rand(0, 9);
        });

        $ctr = $card->reverse()->map(function ($digit, $key) {
            if ($key % 2 === 0) {
                $digit *= 2;
                $digit = ($digit > 9) ? $digit - 9 : $digit;
            }
            return $digit;
        })->sum();
        $ctr = (10 - ($ctr % 10)) % 10;
        return $card->implode('') . $ctr;
    }
}
