<?php

namespace App\Processes\Results;

use App\Entities\User;

class UploadHomeOwnerResult
{
    /** @var User[] */
    private $users = [];

    /**
     * @param User $user
     */
    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
