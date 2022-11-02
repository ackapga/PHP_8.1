<?php

use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use Ackapga\Habrahabr\Blog\Comment;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/database.sqlite');

$faker = Faker\Factory::create('ru_RU');

$usersRepository = new SqliteUsersRepository($connection);
$postsRepository = new SqlitePostsRepository($connection);
$commentsRepository = new SqliteCommentsRepository($connection);

/*     -----   Добавлять пользователя через Консоль.

$command = new CreateUserCommand($usersRepository);
try { // Работа в Консоли(Командная строка)
    $command->handle(Arguments::fromArgv($argv));
} catch (ArgumentsException|CommandException|InvalidArgumentException $exception) {
    echo "{$exception->getMessage()}\n";
}
*/

/*     //-----   Сохранять посты и Извлекать по UUID из БД.
$user = new User(UUID::random(), 'ackapga', new Name('Ackap', 'Maemgenov'));
$postsRepository->save(new Post(
        UUID::random(),
        $user,
        $faker->realText(rand(20, 30)),
        $faker->realText(100)
    )
);

$post = $postsRepository->get(new UUID('9259ada4-65ac-4a93-8558-1ae383ec9b18'));
echo $post;
echo $post->getAuthorUuid()->getName();
*/


/*      //-----   Сохранять комменты и Извлекать по UUID из БД.
$user = new User(UUID::random(), 'ackapga', new Name('Ackap', 'Maemgenov'));
$post = new Post(UUID::random(), $user, $faker->realText(rand(20, 30)), $faker->realText(100));

$commentsRepository->save(
    new Comment(
        UUID::random(),
        $post,
        $user,
        $faker->realText(rand(30, 50)),
    ));
$c =  $commentsRepository->get(new UUID('b9b80e56-4dee-4fbc-acd0-4914e5e6e526'));
print_r($c);
*/



