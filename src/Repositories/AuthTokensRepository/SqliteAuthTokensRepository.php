<?php

namespace Ackapga\Habrahabr\Repositories\AuthTokensRepository;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\AuthTokenNotFoundException;
use Ackapga\Habrahabr\Exceptions\AuthTokensRepositoryException;
use Ackapga\Habrahabr\Interfaces\AuthTokensRepositoryInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use PDO;
use Ackapga\Habrahabr\Blog\AuthToken;
use PDOException;

class SqliteAuthTokensRepository implements AuthTokensRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    /**
     * @param AuthToken $authToken
     * @return void
     * @throws AuthTokensRepositoryException
     */
    public function save(AuthToken $authToken): void
    {
        $query = <<<'SQL'
                    INSERT INTO tokens (
                        token,
                        user_uuid,
                        expires_on
                    ) VALUES (
                        :token,
                        :user_uuid,
                        :expires_on
                    )
                    ON CONFLICT (token) DO UPDATE SET
                    expires_on = :expires_on
                    SQL;

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':token' => (string)$authToken,
                ':user_uuid' => (string)$authToken->userUuid(),
                ':expires_on' => $authToken->expiresOn()
                    ->format(DateTimeInterface::ATOM),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
    }

    /**
     * @param string $token
     * @return AuthToken
     * @throws AuthTokenNotFoundException
     * @throws AuthTokensRepositoryException
     */
    public function get(string $token): AuthToken
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE token = ?'
            );
            $statement->execute([$token]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }

        if (false === $result) {
            throw new AuthTokenNotFoundException("???? ???????? ?????????? ??????????: $token");
        }

        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), $e->getCode(), $e
            );
        }
    }

    /**
     * @param string $token
     * @return void
     */
    public function logout(string $token): void
    {
        $date = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);

        $statement = $this->connection->prepare(
            'UPDATE tokens SET expires_on = :new WHERE token = :token'
        );
        $statement->execute([
            'token' => $token,
            'new' => $date,
        ]);
    }
}
