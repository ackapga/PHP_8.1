<?php

namespace Ackapga\Habrahabr\Repositories\LikesRepository;

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\LikeNotFoundException;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Person\User;
use PDO;

class SqliteLikeRepository implements LikeRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likePost (uuid, post_uuid, user_uuid)
                   VALUES (:uuid, :post_uuid, :user_uuid)'
        );

        $statement->execute([
            ':uuid' => $like,
            ':post_uuid' => $like->getPostUuid()->getUuid(),
            ':user_uuid' => $like->getUserUuid()->getUuid(),
        ]);
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getByPostUuid(UUID $uuid): Like
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likePost WHERE post_uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new LikeNotFoundException(
                "Не найден Like: $uuid"
            );
        }
        return $result;
    }

    public function checkUserLikeForPostExists(string $postUuid, string $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT *
            FROM postsLikes
            WHERE 
                post_uuid = :postUuid AND user_uuid = :userUuid'
        );

        $statement->execute(
            [
                ':postUuid' => $postUuid,
                ':userUuid' => $userUuid
            ]
        );

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new \Exception(
                'Пользователи, лайкнувшие этот пост, уже существуют!'
            );
        }
    }

    public function remove(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE
                   FROM postsLikes
                   WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => $uuid
        ]);
    }

}