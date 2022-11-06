<?php

namespace Blog\Commands;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Commands\Arguments;
use Ackapga\Habrahabr\Commands\CreateUserCommand;
use Ackapga\Habrahabr\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Exceptions\CommandException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
        };
    }

    /**
     * @throws InvalidArgumentException
     * @throws CommandException
     */
    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('Не указан обязательный аргумент: last_name - чтобы добавить пользователя!');
        $command->handle(new Arguments([
            'username' => 'ackapga',
            'first_name' => 'Ackap',
        ]));
    }

    /**
     * @throws InvalidArgumentException
     * @throws CommandException
     */
    public function testItRequiresFirstName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('Не указан обязательный аргумент: first_name - чтобы добавить пользователя!');
        $command->handle(new Arguments(['username' => 'ackapga']));
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     * @throws CommandException
     */
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                return new User(UUID::random(), "ackapga", new Name("Ackap", "Maemgenov"));
            }
        };

        $command = new CreateUserCommand($usersRepository);
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("Пользователь ackapga уже существует в БД!");
        $command->handle(new Arguments(['username' => 'ackapga']));
    }


    /**
     * @throws ArgumentsException
     * @throws InvalidArgumentException
     * @throws CommandException
     */
    public function testItSavesUserToRepository(): void
    {

        $usersRepository = new class implements UsersRepositoryInterface {

            private bool $called = false;

            public function save(User $user): void
            {
                $this->called = true;
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUserCommand($usersRepository);
        $command->handle(new Arguments([
            'username' => 'ackapga',
            'first_name' => 'Ackap',
            'last_name' => 'Maemgenov',
        ]));

        $this->assertTrue($usersRepository->wasCalled());
    }


}

