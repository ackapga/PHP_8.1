<?php

namespace Ackapga\Habrahabr\Http\Actions\Comments;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\CommentNotFoundException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;

class FindByUuidComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {

        try {
            $uuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $comment = $this->commentsRepository->get(new UUID($uuid));
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        } catch (InvalidArgumentException $e) {
        }

        return new SuccessfulResponse([
            'post_uuid' => (string)$comment->getPostUuid()->getUuidPost(),
            'author_uuid' => (string)$comment->getAuthorUuid()->getUuidUser(),
            'text' => $comment->getTextComment()
        ]);
    }
}