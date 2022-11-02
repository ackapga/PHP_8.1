<?php

namespace Ackapga\Habrahabr\Http\Actions\Users;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;

class DeleteUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        try {
            $userUuid= $request->query('uuid');
            $this->usersRepository->get(new UUID($userUuid));

        } catch (UserNotFoundException|HttpException|InvalidArgumentException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->usersRepository->delete(new UUID($userUuid));

        return new SuccessfulResponse([
            'uuid' => $userUuid
        ]);
    }
}