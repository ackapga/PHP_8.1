<?php

use Ackapga\Habrahabr\Commands\CreateUserCommand;
use Ackapga\Habrahabr\Commands\Arguments;
use Ackapga\Habrahabr\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Exceptions\CommandException;

$container = include __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (ArgumentsException|CommandException $e) {
    echo "{$e->getMessage()}\n";
}
