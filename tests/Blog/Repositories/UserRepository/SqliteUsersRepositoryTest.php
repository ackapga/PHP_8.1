<?php

namespace Blog\Repositories\UserRepository;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use Ackapga\Habrahabr\Repositories\UsersRepository\SqliteUsersRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{
    public function testItSavesUserToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once())->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':username' => 'ackapga',
                ':first_name' => 'Ackap',
                ':last_name' => 'Maemgenov',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub);

        $repository->save(new User(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'ackapga',
                new Name('Ackap', 'Maemgenov')
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementMock = $this->createStub(PDOStatement::class);

        $statementMock->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Не найден пользователь: ackapga');
        $repository->getByUsername('ackapga');
    }
}