<?php

namespace App\Processes\Results;

use App\Entities\User;

class UploadHomeOwnerResult
{
    /** @var User[] */
    private array $users = [];

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

    /**
     * @return array<int, array<string, string|null>>
     */
    public function serialise(): array
    {
        $userData = [];
        foreach ($this->getUsers() as $user) {
            $userData[] = [
              'title' => $user->getTitle(),
              'first_name' => $user->getFirstName(),
              'initial' => $user->getInitial(),
              'last_name' => $user->getLastName(),
            ];
        }
        return $userData;
    }
}
