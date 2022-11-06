<?php

use Ackapga\Habrahabr\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Ackapga\Habrahabr\Container\DIContainer;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/database.sqlite')
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    SqliteCommentsRepository::class
);

return $container;