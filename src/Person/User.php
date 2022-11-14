<?php

namespace Ackapga\Habrahabr\Person;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;

class User
{
    public function __construct(
        private UUID   $uuid,
        private string $username,
        private string $hashedPassword,
        private Name   $name
    )
    {
    }

    // Функция для вычисления хеша
    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $password . $uuid);
    }

    // Функция для проверки предъявленного пароля
    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    // Функция для создания нового пользователя

    /**
     * @throws InvalidArgumentException
     */
    public static function createFrom(
        string $username,
        string $password,
        Name   $name
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            self::hash($password, $uuid),
            $name
        );
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
        return $this->hashedPassword;
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
        $this->hashedPassword = $password;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

}