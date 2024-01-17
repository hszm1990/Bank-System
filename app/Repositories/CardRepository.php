<?php

namespace App\Repositories;

use App\Contracts\CardRepositoryInterface;
use App\Models\Card;

class CardRepository extends Repository implements CardRepositoryInterface
{

    public function model(): string
    {
        return Card::class;
    }

    public function getByNumbers(array $numbers)
    {
        return $this->model->byCardNumbers($numbers)->get();
    }
}
