<?php

namespace Ackapga\Habrahabr\Repositories\LikesRepository;

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\LikeNotFoundException;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Person\User;
use Exception;
use PDO;

class SqliteLikeRepository implements LikeRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    /**
     * @param Like $like
     * @return void
     */
    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likePost (uuid, post_uuid, user_uuid)
                   VALUES (:uuid, :post_uuid, :user_uuid)'
        );

        $statement->execute([
            ':uuid' => (string)$like,
            ':post_uuid' => $like->getPostUuid()->getUuid(),
            ':user_uuid' => $like->getUserUuid()->getUuid(),
        ]);
    }

    /**
     * @param UUID $uuid
     * @return Like
     * @throws LikeNotFoundException
     */
    public function getByPostUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likePost WHERE post_uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            $message = 'Нету Лайков для этого поста: ' . $uuid;
            throw new LikeNotFoundException($message);
        }

        return $result;
    }

    /**
     * @param string $postUuid
     * @param string $userUuid
     * @return void
     * @throws Exception
     */
    public function checkUserLikeForPostExists(string $postUuid, string $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likePost WHERE post_uuid = :postUuid AND user_uuid = :userUuid'
        );

        $statement->execute(
            [
                ':postUuid' => $postUuid,
                ':userUuid' => $userUuid
            ]
        );

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new Exception(
                'Пользователи, лайкнул этот пост!'
            );
        }
    }

    /**
     * @param UUID $uuid
     * @return void
     */
    public function remove(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM likePost WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => $uuid
        ]);
    }

}