<?php

namespace Ackapga\Habrahabr\Person;

use Ackapga\Habrahabr\Blog\UUID;

class User extends \Ackapga\Habrahabr\Blog\Post
{
    public function __construct(
        private UUID $uuid,
        private string $username,
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
     * @param Name $name
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

}