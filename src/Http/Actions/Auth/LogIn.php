<?php

namespace Ackapga\Habrahabr\Http\Actions\Auth;

use Ackapga\Habrahabr\Blog\AuthToken;
use Ackapga\Habrahabr\Exceptions\AuthException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\Auth\PasswordAuthenticationInterface;
use Ackapga\Habrahabr\Interfaces\AuthTokensRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use DateTimeImmutable;
use Exception;

class LogIn implements ActionInterface
{
    public function __construct(
        private PasswordAuthenticationInterface $passwordAuthentication,
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {

        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authToken = new AuthToken(
            bin2hex(random_bytes(40)),
            $user->getUuidUser(),
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => (string)$authToken,
        ]);
    }
}
