<?php

namespace Ackapga\Habrahabr\Blog\Repositories\CommentsRepository;

use Ackapga\Habrahabr\Blog\Comment;
use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\CommentNotFoundException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use PDO;
use PDOStatement;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    /**
     * @param UUID $uuid
     * @return Comment
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments LEFT JOIN posts
                    ON comments.post_uuid = posts.uuid
                    LEFT JOIN users
                    ON posts.author_uuid = users.uuid
                    WHERE comments.uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getComment($statement, $uuid);
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
                   VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$comment->getUuid(),
            ':post_uuid' => (string)$comment->getPostUuid()->getUuid(),
            ':author_uuid' => (string)$comment->getAuthorUuid()->getUuid(),
            ':text' => $comment->getText(),
        ]);
    }

    /**
     * @param PDOStatement $statement
     * @param string $uuid
     * @return Comment
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    private function getComment(PDOStatement $statement, string $uuid): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new CommentNotFoundException(
                "Пост по UUID: " . $uuid . " не найден!"
            );
        }

        $user = new User(
            new UUID($result['author_uuid']),
            $result['username'],
            new Name($result['first_name'], $result['last_name']
            )
        );

        $post = new Post(
            new UUID($result['post_uuid']),
            $user,
            $result['title'],
            $result['text']

        );

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $user,
            $result['text']);
    }
}