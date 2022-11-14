<?php

namespace Ackapga\Habrahabr\Http\Auth;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\AuthException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Person\User;

class JsonBodyUuidAuthentication implements AuthenticationInterface
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
        try {
            $userUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException|InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->usersRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }

}