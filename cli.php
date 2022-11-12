<?php

use Ackapga\Habrahabr\Commands\Arguments;
use Ackapga\Habrahabr\Commands\CreateUserCommand;
use Ackapga\Habrahabr\Exceptions\AppException;

$container = include __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    echo "{$e->getMessage()}\n";
}

/*
 * $likeRepository = new SqliteLikeRepository(new PDO('sqlite:' . __DIR__ . '/database.sqlite'));
$user = new User(UUID::random(), 'ackapga', new Name('Ackap', 'Maemgenov'));
$post = new Post(UUID::random(), $user, '$faker->realText(rand(20, 30))', '$faker->realText(100)');
$likeRepository->save(new Like(
    UUID::random(),
    $post,
    $user
));
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