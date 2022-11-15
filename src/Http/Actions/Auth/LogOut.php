<?php

namespace Ackapga\Habrahabr\Http\Actions\Auth;

use Ackapga\Habrahabr\Blog\AuthToken;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Interfaces\AuthTokensRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\SuccessfulResponse;


class LogOut implements ActionInterface
{
    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        $token = $request->jsonBodyField('token');

        $this->authTokensRepository->logout($token);

        return new SuccessfulResponse([
            'token' => (string)$token,
        ]);
    }
}