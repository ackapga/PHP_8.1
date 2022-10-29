<?php

namespace Ackapga\Habrahabr\Blog\Repositories\UsersRepository;

use Ackapga\Habrahabr\Blog\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use PDO;
use Ackapga\Habrahabr\Blog\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Blog\Exceptions\InvalidArgumentException;
use PDOStatement;


class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    /**
     * @param User $user
     * @return void
     * Метод сохранения пользователя в БД.
     */
    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
                   VALUES (:uuid, :username, :first_name, :last_name)'
        );

        $statement->execute([
            ':uuid' => (string)$user->getUuid(),
            ':username' => $user->getUsername(),
            ':first_name' => $user->getName()->getFirstName(),
            ':last_name' => $user->getName()->getLastName(),
        ]);
    }

    /**
     * @param UUID $uuid
     * @return User
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * Извлечь пользователя по UUID из БД.
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getUser($statement, $uuid);
    }

    /**
     * @param string $username
     * @return User
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * Извлечь пользователя по Username из БД.
     */
    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    /**
     * @param PDOStatement $statement
     * @param string $username
     * @return User
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * Закрытый метод класса для Save | Get
     */
    private function getUser(PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Не найден пользователь: $username"
            );
        }

        return new User(
            new UUID($result['uuid']),
            $result['username'],
            new Name($result['first_name'], $result['last_name'])
        );
    }

}