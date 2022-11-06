<?php

namespace Ackapga\Habrahabr\Http\Actions\Comments;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\CommentNotFoundException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;

class DeleteComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        try {
            $commentUuid= $request->query('uuid');
            $this->commentsRepository->get(new UUID($commentUuid));

        } catch (CommentNotFoundException|HttpException|InvalidArgumentException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->commentsRepository->delete(new UUID($commentUuid));

        return new SuccessfulResponse([
            'uuid' => $commentUuid
        ]);
    }
}