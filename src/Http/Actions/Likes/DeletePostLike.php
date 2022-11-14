<?php

namespace Ackapga\Habrahabr\Http\Actions\Likes;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\CommentNotFoundException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;

class DeletePostLike implements ActionInterface
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $postLikeUuid = new UUID($request->query('uuid'));

        } catch (HttpException|InvalidArgumentException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->likeRepository->remove($postLikeUuid);

        return new SuccessfulResponse(
            ['action' => 'Like: ' . $postLikeUuid . ' успешно удален!']
        );
    }
}