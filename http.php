<?php

use Ackapga\Habrahabr\Exceptions\AppException;
use Ackapga\Habrahabr\Http\Actions\Comments\CreateComment;
use Ackapga\Habrahabr\Http\Actions\Comments\DeleteComment;
use Ackapga\Habrahabr\Http\Actions\Comments\FindByUuidComment;
use Ackapga\Habrahabr\Http\Actions\Likes\CreatePostLike;
use Ackapga\Habrahabr\Http\Actions\Likes\DeletePostLike;
use Ackapga\Habrahabr\Http\Actions\Likes\FindByUuidPostLikes;
use Ackapga\Habrahabr\Http\Actions\Posts\CreatePost;
use Ackapga\Habrahabr\Http\Actions\Posts\DeletePost;
use Ackapga\Habrahabr\Http\Actions\Posts\FindByUuidPost;
use Ackapga\Habrahabr\Http\Actions\Users\CreateUser;
use Ackapga\Habrahabr\Http\Actions\Users\DeleteUser;
use Ackapga\Habrahabr\Http\Actions\Users\FindByUuidUser;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Actions\Users\FindByUsername;
use Ackapga\Habrahabr\Exceptions\HttpException;

$container = require __DIR__ . '/bootstrap.php';

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


$routes = [
    'GET' => [
        '/users/show.uuid' => FindByUuidUser::class,
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuidPost::class,
        '/comments/show' => FindByUuidComment::class,
        '/likes/post/show' => FindByUuidPostLikes::class,
    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/comments/create' => CreateComment::class,
        '/likes/create' => CreatePostLike::class,
    ],
    'DELETE' => [
        '/users' => DeleteUser::class,
        '/posts' => DeletePost::class,
        '/comments' => DeleteComment::class,
        '/likes/posts' => DeletePostLike::class,
    ],
];


if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Метод не найден: $method $path"))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Роут не найден: $method $path"))->send();
    return;
}

$actionClassName = $routes[$method][$path];
$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();