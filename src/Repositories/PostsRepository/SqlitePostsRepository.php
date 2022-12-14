<?php

namespace Ackapga\Habrahabr\Repositories\PostsRepository;

use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\PostNotFoundException;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use PDO;
use PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
                   VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$post->getUuidPost(),
            ':author_uuid' => (string)$post->getAuthorUuid()->getUuidUser(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    /**
     * @param UUID $uuid
     * @return Post
     * @throws InvalidArgumentException
     * @throws PostNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts LEFT JOIN users
                    ON posts.author_uuid = users.uuid
                    WHERE posts.uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getPost($statement, $uuid);
    }

    /**
     * @param PDOStatement $statement
     * @param string $uuid
     * @return Post
     * @throws InvalidArgumentException
     * @throws PostNotFoundException
     */
    private function getPost(PDOStatement $statement, string $uuid): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException(
                "???????? ???? UUID: " . $uuid . " ???? ????????????!"
            );
        }

        $user = new User(
            new UUID($result['author_uuid']),
            $result['username'],
            $result['password'],
            new Name($result['first_name'], $result['last_name']
            )
        );

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']);
    }

    /**
     * @param UUID $uuid
     * @return void
     */
    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare('DELETE FROM posts WHERE uuid = :uuid');
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
    }

}