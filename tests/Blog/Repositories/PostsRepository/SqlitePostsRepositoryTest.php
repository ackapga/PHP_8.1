<?php

namespace Blog\Repositories\PostsRepository;

use Ackapga\Habrahabr\Blog\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Blog\Exceptions\PostNotFoundException;
use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Blog\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once())->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':title' => 'title',
                ':text' => 'text',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $repository->save(new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'title',
                'text'
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

        $repository = new SqlitePostsRepository($connectionStub);

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Пост по UUID: 123e4567-e89b-12d3-a456-426614174000 не найден!');
        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

}