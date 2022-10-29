<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ackapga\Habrahabr\Blog\Commands\Arguments;
use Ackapga\Habrahabr\Blog\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Blog\Exceptions\CommandException;
use Ackapga\Habrahabr\Blog\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Ackapga\Habrahabr\Blog\Commands\CreateUserCommand;

$usersRepository = new SqliteUsersRepository(
    new PDO('sqlite:' . __DIR__ . '/database.sqlite')
);
$command = new CreateUserCommand($usersRepository);

try { // Работа в Консоли(Командная строка)
    $command->handle(Arguments::fromArgv($argv));
} catch (ArgumentsException|CommandException|InvalidArgumentException $exception) {
    echo "{$exception->getMessage()}\n";
}
