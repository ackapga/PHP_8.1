<?php

namespace Ackapga\Habrahabr\Http\Auth;

use Ackapga\Habrahabr\Exceptions\AuthException;
use Ackapga\Habrahabr\Exceptions\AuthTokenNotFoundException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Interfaces\AuthTokensRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Person\User;
use DateTimeImmutable;
use function Ackapga\Habrahabr\Http\Actions\Auth\mb_substr;
use function Ackapga\Habrahabr\Http\Actions\Auth\str_starts_with;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    /**
     * @param Request $request
     * @return User
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Неверный токен: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Плохой токен: [$token]");
        }

        if ($authToken->expiresOn() <= new DateTimeImmutable()) {
            throw new AuthException("Срок действия токена истек: [$token]");
        }

        $userUuid = $authToken->userUuid();

        return $this->usersRepository->get($userUuid);
    }
}