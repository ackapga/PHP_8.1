<?php

namespace Ackapga\Habrahabr\Commands;


use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Exceptions\CommandException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;

final class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    /**
     * @param Arguments $arguments
     * @return void
     * @throws CommandException
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     */
    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            throw new CommandException("Пользователь $username уже существует в БД!");
        }
        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            new Name($arguments->get('first_name'), $arguments->get('last_name'))
        ));
    }

    /**
     * @param string $username
     * @return bool
     */
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

}
