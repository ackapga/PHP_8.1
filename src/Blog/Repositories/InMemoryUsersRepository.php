<?php

namespace Ackapga\Habrahabr\Blog\Repositories;

use Ackapga\Habrahabr\Blog\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Person\User;

class InMemoryUsersRepository                                       // Работает с app.php
{
    private array $users = [];

public function save(User $user): void                              // Сохраняет в Массив
    {
        $this->users[] = $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): User                              // Берет значения, если нет выкидывает Исключения!
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("Пользователь с ID: $id не найден.");
    }

}