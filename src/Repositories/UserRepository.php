<?php

namespace App\Repositories;

use App\Entities\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function beginTransaction(): void
    {
        // TODO: Implement beginTransaction() method.
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function commitTransaction(): void
    {
        // TODO: Implement commitTransaction() method.
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function rollbackTransaction(): void
    {
        // TODO: Implement rollbackTransaction() method.
    }
}
