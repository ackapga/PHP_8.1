<?php

use Person\{Name, Person};
use Blog\Post;

spl_autoload_register('loader');

function loader ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        include __DIR__ . '/src/' . $file;
}

$post = new Post(
    new Person(
        new Name('Ackap', 'Ga'),
        new DateTimeImmutable()
    ), 'Hello world!'
);

echo $post;