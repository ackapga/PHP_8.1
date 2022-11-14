<?php

namespace Ackapga\Habrahabr\Http\Auth;

use Ackapga\Habrahabr\Exceptions\AuthException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Person\User;

class PasswordAuthentication implements AuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
// 1. Идентифицируем пользователя
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
// 2. Аутентифицируем пользователя. Проверяем, что предъявленный пароль соответствует сохранённому в БД
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!$user->checkPassword($password)) {
            throw new AuthException('Неправильный пароль!');
        }

        return $user;
    }
}
