<?php

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/database.sqlite');

$faker = Faker\Factory::create('ru_RU');

use Ackapga\Habrahabr\Blog\Commands\Arguments;
use Ackapga\Habrahabr\Blog\Commands\CreateUserCommand;
use Ackapga\Habrahabr\Blog\Exceptions\ArgumentsException;
use Ackapga\Habrahabr\Blog\Exceptions\CommandException;
use Ackapga\Habrahabr\Blog\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Person\User;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\Comment;
use Ackapga\Habrahabr\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Ackapga\Habrahabr\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

/*     -----   Добавлять пользователя через Консоль.

$usersRepository = new SqliteUsersRepository($connection);
$command = new CreateUserCommand($usersRepository);
try { // Работа в Консоли(Командная строка)
    $command->handle(Arguments::fromArgv($argv));
} catch (ArgumentsException|CommandException|InvalidArgumentException $exception) {
    echo "{$exception->getMessage()}\n";
}
*/

/*     -----   Сохранять посты и Извлекать по UUID из БД.

$postsRepository = new SqlitePostsRepository($connection);
$postsRepository->save(
    new Post(
        UUID::random(),
        UUID::random(),
        $faker->realText(rand(20, 30)),
        $faker->realText(100)
    )
);

$getPost = $postsRepository->get(new UUID('f1ea4840-4ddb-469d-8a9e-d08f24420e38'));
print_r($getPost);
*/

/*     -----   Сохранять комменты и Извлекать по UUID из БД.
$commentsRepository = new SqliteCommentsRepository($connection);
$commentsRepository->save(
    new Comment(
        UUID::random(),
        UUID::random(),
        UUID::random(),
        $faker->realText(rand(30, 50)),
    ));
$getComment = $commentsRepository->get(new UUID('b9b80e56-4dee-4fbc-acd0-4914e5e6e526'));
print_r($getComment);
*/
