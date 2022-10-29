<?php

namespace Ackapga\Habrahabr\Blog\Repositories;

use Ackapga\Habrahabr\Blog\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Blog\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Person\User;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @param UUID $uuid
     * @return User
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            if ((string)$user->getUuid() === (string)$uuid) {
                return $user;
            }
        }
        throw new UserNotFoundException("Пользователь с таким UUID не найден: $uuid");
    }

    /**
     * @param string $username
     * @return User
     * @throws UserNotFoundException
     */
    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("Пользователь с таким Именем не найден: $username");
    }

}