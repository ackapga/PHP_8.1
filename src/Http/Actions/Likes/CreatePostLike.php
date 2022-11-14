<?php

namespace Ackapga\Habrahabr\Http\Actions\Likes;

use Ackapga\Habrahabr\Blog\PostLike;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\AppException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\LikeAlreadyExists;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\Auth\IdentificationInterface;
use Ackapga\Habrahabr\Interfaces\LikeRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;

class CreatePostLike implements ActionInterface
{

    public function __construct(
        private LikeRepositoryInterface  $likesRepository,
        private PostsRepositoryInterface $postsRepository,
        private IdentificationInterface  $identification,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        try {
            $user = $this->identification->user($request);
            $postUuid = new UUID($request->JsonBodyField('post_uuid'));
            $post = $this->postsRepository->get($postUuid);
        } catch (HttpException|InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->likesRepository->checkUserLikeForPostExists($postUuid, $user->getUuidUser());
        } catch (LikeAlreadyExists|\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newLikeUuid = UUID::random();

        $this->likesRepository->save(
            new PostLike(
                $newLikeUuid,
                $post,
                $user
            )
        );

        return new SuccessFulResponse(
            ['uuid' => (string)$newLikeUuid]
        );
    }
}