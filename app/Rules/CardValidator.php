<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CardValidator implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->checksumTest($value)) {
            $fail('validation.valid_card_number')->translate();
        }
    }
    private function checksumTest($cardNumber): bool
    {
        $checksum = 0;
        $len = strlen($cardNumber);
        for ($i = 2 - ($len % 2); $i <= $len; $i += 2) {
            $checksum += $cardNumber[$i - 1];
        }
        for ($i = $len % 2 + 1; $i < $len; $i += 2) {
            $digit = $cardNumber[$i - 1] * 2;
            if ($digit < 10) {
                $checksum += $digit;
            } else {
                $checksum += $digit - 9;
            }
        }

        return ($checksum % 10) === 0;
    }
}
