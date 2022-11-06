<?php

use Ackapga\Habrahabr\Exceptions\AppException;
use Ackapga\Habrahabr\Http\Actions\Comments\CreateComment;
use Ackapga\Habrahabr\Http\Actions\Comments\DeleteComment;
use Ackapga\Habrahabr\Http\Actions\Comments\FindByUuidComment;
use Ackapga\Habrahabr\Http\Actions\Posts\CreatePost;
use Ackapga\Habrahabr\Http\Actions\Posts\DeletePost;
use Ackapga\Habrahabr\Http\Actions\Posts\FindByUuid;
use Ackapga\Habrahabr\Http\Actions\Users\CreateUser;
use Ackapga\Habrahabr\Http\Actions\Users\DeleteUser;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Actions\Users\FindByUsername;

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
        '/users/show' => FindByUsername::class,

        '/posts/show' => FindByUuid::class,
        '/comments/show' => FindByUuidComment::class,
    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/comments/create' => CreateComment::class,
    ],
    'DELETE' => [
        '/users' => DeleteUser::class,
        '/posts' => DeletePost::class,
        '/comments' => DeleteComment::class,
    ],
];


if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
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