<?php

namespace Ackapga\Habrahabr\Blog\Repositories\PostsRepository;

use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\PostNotFoundException;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use PDO;
use PDOStatement;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
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
            'SELECT * FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getPost($statement, $uuid);
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
            ':uuid' => (string)$post->getUuid(),
            ':author_uuid' => (string)$post->getAuthorUuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    /**
     * @param PDOStatement $statement
     * @param string $uuid
     * @return Post
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     */
    private function getPost(PDOStatement $statement, string $uuid): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException(
                "Пост по UUID: " . $uuid . " не найден!"
            );
        }

        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']);
    }
}