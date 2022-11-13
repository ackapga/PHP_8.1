<?php

namespace Ackapga\Habrahabr\Http\Actions\Likes;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\LikeNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;

class FindByUuidPostLikes implements ActionInterface
{

    public function __construct(
        private LikeRepositoryInterface $likeRepository
    )
    {
    }
    public function handle(Request $request): Response
    {
        try {
            $uuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException|InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $likes = $this->likeRepository->getByPostUuid($uuid);
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $outputMas = [];

        foreach ($likes as $like) {
            $outputMas[] = [
                'uuid' => $like['uuid'],
                'user_uuid' => $like['user_uuid']
            ];
        }

        return new SuccessFulResponse(
            ['post_uuid' => $outputMas]
        );
    }
}