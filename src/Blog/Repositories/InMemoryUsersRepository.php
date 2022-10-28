<?php

namespace Ackapga\Habrahabr\Blog\Repositories;

use Ackapga\Habrahabr\Blog\User;
use Ackapga\Habrahabr\Blog\Exceptions\UserNotFoundException;

class InMemoryUsersRepository
{
    /**
     * @var User[]
     */
    private array $users = [];

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->users[] = $user;
    }
    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function get(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("Пользователь с ID: $id не найден.");
    }

}