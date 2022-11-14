<?php

use Ackapga\Habrahabr\Container\DIContainer;
use Ackapga\Habrahabr\Http\Actions\Auth\BearerTokenAuthentication;
use Ackapga\Habrahabr\Http\Auth\PasswordAuthentication;
use Ackapga\Habrahabr\Http\Auth\PasswordAuthenticationInterface;
use Ackapga\Habrahabr\Http\Auth\TokenAuthenticationInterface;
use Ackapga\Habrahabr\Interfaces\AuthTokensRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use Ackapga\Habrahabr\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Repositories\LikesRepository\SqliteLikeRepository;
use Ackapga\Habrahabr\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Repositories\UsersRepository\SqliteUsersRepository;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

Dotenv::createImmutable(__DIR__)->safeLoad();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
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

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);
$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);
$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);

return $container;