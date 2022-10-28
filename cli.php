<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ackapga\Habrahabr\Blog\{Post, Comment};
use Ackapga\Habrahabr\Person\User;

$faker = Faker\Factory::create('ru_RU');

$message = 'Введите один из аргументов' . PHP_EOL . 'user' . PHP_EOL . 'post' . PHP_EOL . 'comment';

if (empty($argv[1])) {
    die($message);
} else {
    $inputDate = $argv[1];
}

switch ($inputDate) {
    case 'user':
        echo new User((int)$faker->uuid, $faker->firstName, $faker->lastName());
        break;
    case 'post':
        echo new Post((int)$faker->uuid, (int)$faker->uuid(), $faker->realText(rand(20, 30)), $faker->realText('100'));
        break;
    case 'comment':
        echo new Comment((int)$faker->uuid, (int)$faker->uuid(), (int)$faker->uuid(), $faker->realText('50'));
        break;
    default:
        echo $message;
}

