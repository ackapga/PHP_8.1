<?php

use Ackapga\Habrahabr\Container\DIContainer;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Repositories\LikesRepository\SqliteLikeRepository;
use Ackapga\Habrahabr\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Repositories\UsersRepository\SqliteUsersRepository;

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

$container->bind(
    LikeRepositoryInterface::class,
    SqliteLikeRepository::class
);

return $container;