<?php

namespace App\Entities;

class User
{
    /** @var string */
    private $title;

    /** @var null|string */
    private $initial = null;

    /** @var null|string */
    private $firstName = null;

    /** @var string */
    private $lastName;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getInitial(): ?string
    {
        return $this->initial;
    }

    /**
     * @param string|null $initial
     */
    public function setInitial(?string $initial): void
    {
        $this->initial = $initial;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
