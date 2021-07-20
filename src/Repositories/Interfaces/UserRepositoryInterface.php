<?php

namespace App\Repositories\Interfaces;

use App\Entities\User;

interface UserRepositoryInterface extends TransactionInterface
{
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;
}
