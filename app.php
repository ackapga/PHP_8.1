<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ackapga\Habrahabr\Blog\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Blog\Repositories\InMemoryUsersRepository;
use Ackapga\Habrahabr\Person\User;

try {

    $rep = new InMemoryUsersRepository();
    $user1 = new User(1, "Ember Song", "Ember");
    $user2 = new User(2, "Иван Иванов", "Ivan");

    $rep->save($user1);
    $rep->save($user2);

    echo $rep->get(1) . PHP_EOL;                                        // Найдет пользователя №1
    echo $rep->get(2) . PHP_EOL;                                        // Найдет пользователя №2
    echo $rep->get(23);                                                 // Бросит исключения! пользователю №23

} catch (UserNotFoundException $exception) {
    echo $exception->getMessage();
} catch (Exception $exception) {
    print_r($exception->getTrace());
}