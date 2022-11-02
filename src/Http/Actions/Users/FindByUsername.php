<?php

namespace Ackapga\Habrahabr\Http\Actions\Users;

use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'username' => $user->getUsername(),
            'name' => $user->getName()->getFirstName() . ' ' . $user->getName()->getLastName(),
        ]);
    }
}
