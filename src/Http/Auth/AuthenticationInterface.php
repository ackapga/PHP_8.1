<?php

namespace Ackapga\Habrahabr\Http\Auth;

use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Person\User;

interface AuthenticationInterface
{
    /**
     * Контракт описывает единственный метод, получающий пользователя из запроса.
     * @param Request $request
     * @return User
     */
    public function user(Request $request): User;
}