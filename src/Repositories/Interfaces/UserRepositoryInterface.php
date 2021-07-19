<?php

namespace App\Repositories\Interfaces;

use App\Entities\User;

interface UserRepositoryInterface
{

    /**
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * @return void
     */
    public function commitTransaction(): void;

    /**
     * @return void
     */
    public function rollbackTransaction(): void;

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;
}
