<?php

namespace Ackapga\Habrahabr\Person;

use Ackapga\Habrahabr\Blog\UUID;

class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $password,
        private Name $name
    )
    {
    }

    /**
     * @return UUID
     */
    public function getUuidUser(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuidUser(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

}