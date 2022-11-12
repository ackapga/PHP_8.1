<?php

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Commands\CreateUserCommand;
use Ackapga\Habrahabr\Commands\Arguments;
use Ackapga\Habrahabr\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Exceptions\CommandException;
use Ackapga\Habrahabr\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Repositories\LikesRepository\SqliteLikeRepository;
use Ackapga\Habrahabr\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Repositories\UsersRepository\SqliteUsersRepository;

$container = include __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (ArgumentsException|CommandException $e) {
    echo "{$e->getMessage()}\n";
}
