<?php

namespace App\Http\Requests;

use App\Rules\CardValidator;
use App\Traits\ConvertNumericFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    use ConvertNumericFields;

    protected array $numericFields = [
        'source_card_number',
        'destination_card_number',
        'amount'
    ];
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_card_number' => [
                'bail', 'required',
                'numeric', 'digits:16',
                new CardValidator(),
                'exists:cards,card_number',

            ],
            'destination_card_number' => [
                'bail', 'different:source_card_number', 'required',
                'numeric', 'digits:16',
                new CardValidator(),
                'exists:cards,card_number'
            ],
            'amount' => [
                'required', 'numeric',
                'between:' . config('payment.min') . ',' . config('payment.max')
            ],
            'payment_method' => Rule::in(array_keys(config('payment.methods')))
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge($this->convertToEnglish());
    }
}
