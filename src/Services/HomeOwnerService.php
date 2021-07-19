<?php

namespace App\Services;

use App\Entities\User;
use App\Exceptions\HomeOwnerProcessException;
use App\Repositories\Interfaces\UserRepositoryInterface;

class HomeOwnerService
{
    private const TITLES = [
        'Mr',
        'Mrs',
        'Mister',
        'Dr',
        'Ms',
        'Prof',
    ];

    private const SPLIT_HOME_OWNER = '/ (&|and) /';

    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * HomeOwnerService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $homeOwner
     * @return bool
     */
    public function canStoreHomeOwner(string $homeOwner): bool
    {
        if (empty($homeOwner)) {
            return false;
        }

        $homeOwner = trim($homeOwner);
        if (false === $this->isValidName($homeOwner)) {
            return false;
        }

        $homeOwners = preg_split(self::SPLIT_HOME_OWNER, $homeOwner);
        if (empty($homeOwners)) {
            return false;
        }
        foreach ($homeOwners as $homeOwnerSplit) {
            if ($this->homeOwnerHasTitle($homeOwnerSplit)) {
                return false;
            }
            $doesHomeOwnerInputHaveASecondName = $this->homeOwnerHasLastName($homeOwnerSplit) ||
                $this->homeOwnerHasLastName($homeOwner);
            if (false === $doesHomeOwnerInputHaveASecondName) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $homeOwner
     * @return User
     * @throws HomeOwnerProcessException
     */
    public function storeHomeOwner(string $homeOwner): User
    {
        $user = new User();
        $homeOwner = trim($homeOwner);
        $homeOwners = preg_split(self::SPLIT_HOME_OWNER, $homeOwner);
        if (empty($homeOwners)) {
            throw new HomeOwnerProcessException('Unable to save Home Owner');
        }
        foreach ($homeOwners as $homeOwnerSplit) {
            $user = new User();
            $user->setTitle($this->getHomeOwnersTitle($homeOwnerSplit));
            $homeOwnerSplit = str_replace($user->getTitle(), '', $homeOwnerSplit);
            if (!empty($homeOwnerSplit)) {
                $user->setFirstName($this->getHomeOwnersFirstName($homeOwnerSplit));
                $user->setInitial($this->getHomeOwnersInitial($homeOwnerSplit));
            }
            $user->setLastName($this->getHomeOwnersLastName($homeOwnerSplit, $homeOwner));
            $this->userRepository->save($user);
        }
        return $user;
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->userRepository->beginTransaction();
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function commitTransaction(): void
    {
        $this->userRepository->commitTransaction();
    }

    /**
     * Note this won't do anything
     * @return void
     */
    public function rollbackTransaction(): void
    {
        $this->userRepository->rollbackTransaction();
    }

    /**
     * @param string $homeOwner
     * @return bool
     */
    private function isValidName(string $homeOwner): bool
    {
        return (bool) preg_match('~[0-9]+~', $homeOwner);
    }

    /**
     * @param string $homeOwner
     * @return bool
     */
    private function homeOwnerHasTitle(string $homeOwner): bool
    {
        foreach (self::TITLES as $titles) {
            if (false !== strpos($homeOwner, $titles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $homeOwner
     * @return bool
     */
    private function homeOwnerHasLastName(string $homeOwner): bool
    {
        $homeOwnerDetails = explode(' ', $homeOwner);
        $homeOwnerLastName = trim(end($homeOwnerDetails));
        return !empty($homeOwnerLastName);
    }

    /**
     * @param string $homeOwner
     * @return string
     * @throws HomeOwnerProcessException
     */
    private function getHomeOwnersTitle(string $homeOwner): string
    {
        $homeOwnerValues = explode(' ', $homeOwner);
        foreach ($homeOwnerValues as $homeOwnerValue) {
            if (in_array(trim($homeOwnerValue), self::TITLES)) {
                return $homeOwnerValue;
            }
        }
        throw new HomeOwnerProcessException('Unable to add Title for HomeOwner');
    }

    /**
     * @param string $homeOwner
     * @return string|null
     */
    private function getHomeOwnersFirstName(string $homeOwner): ?string
    {
        $homeOwnerValues = explode($homeOwner, ' ');
        if (empty($homeOwnerValues)) {
            return null;
        }
        $homeOwnerFirstName = array_pop($homeOwnerValues);
        $homeOwnerFirstName = trim($homeOwnerFirstName, '.');
        $hasFirstName = !empty($homeOwnerFirstName) || strlen($homeOwnerFirstName) > 1;
        return $hasFirstName ? $homeOwnerFirstName : null;
    }

    /**
     * @param string $homeOwner
     * @return string|null
     */
    private function getHomeOwnersInitial(string $homeOwner): ?string
    {
        $homeOwnerValues = explode($homeOwner, ' ');
        if (empty($homeOwnerValues)) {
            return null;
        }
        $homeOwnerInitial = array_pop($homeOwnerValues);
        $homeOwnerInitial = trim($homeOwnerInitial, '.');
        $hasFirstName = !empty($homeOwnerInitial) && strlen($homeOwnerInitial) === 1;
        return $hasFirstName ? $homeOwnerInitial : null;
    }

    /**
     * @param string|null $homeOwner
     * @param string $homeOwners
     * @return string
     * @throws HomeOwnerProcessException
     */
    private function getHomeOwnersLastName(?string $homeOwner, string $homeOwners): string
    {
        if (empty($homeOwner)) {
            $homeOwnersDetails = explode(' ', $homeOwners);
            $homeOwnersLastName = trim(end($homeOwnersDetails));
            if (!empty($homeOwnersLastName)) {
                return $homeOwnersLastName;
            }
            throw new HomeOwnerProcessException('Unable to add Last Name for HomeOwner');
        }
        $homeOwnerDetails = explode(' ', $homeOwner);
        $homeOwnerLastName = trim(end($homeOwnerDetails));
        if (!empty($homeOwnerLastName)) {
            return $homeOwnerLastName;
        }
        $homeOwnersDetails = explode(' ', $homeOwners);
        $homeOwnersLastName = trim(end($homeOwnersDetails));
        if (!empty($homeOwnersLastName)) {
            return $homeOwnersLastName;
        }
        throw new HomeOwnerProcessException('Unable to add Last Name for HomeOwner');
    }
}
