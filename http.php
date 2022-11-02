<?php

use Ackapga\Habrahabr\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Ackapga\Habrahabr\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Ackapga\Habrahabr\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Ackapga\Habrahabr\Http\Actions\Comments\CreateComment;
use Ackapga\Habrahabr\Http\Actions\Comments\FindByUuidComment;
use Ackapga\Habrahabr\Http\Actions\Posts\CreatePost;

use Ackapga\Habrahabr\Http\Actions\Posts\FindByUuid;
use Ackapga\Habrahabr\Http\Actions\Users\CreateUser;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Actions\Users\FindByUsername;

require_once __DIR__ . '/vendor/autoload.php';

$routes = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            )
        ),
        '/posts/show' => new FindByUuid(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
        ),
        '/comments/show' => new FindByUuidComment(
            new SqliteCommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
        ),
    ],
    'POST' => [
        '/users/create' => new CreateUser(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            )
        ),
        '/posts/create' => new CreatePost(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
        ),
        '/comments/create' => new CreateComment(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            ),
            new SqliteCommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/database.sqlite')
            )
        )
    ],
];


$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}
try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}


if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();