<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends Repository implements UserRepositoryInterface
{

    public function model()
    {
        return User::class;
    }

    public function findByIds(array $ids)
    {
        return $this->model->find($ids);
    }
}
