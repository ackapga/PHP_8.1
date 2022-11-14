<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
    // Метод сохранения токена
    public function save(AuthToken $authToken): void;

    // Метод получения токена
    public function get(string $token): AuthToken;
}