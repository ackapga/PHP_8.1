<?php

namespace Ackapga\Habrahabr\Http\Actions\Likes;

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\AppException;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
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
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->JsonBodyField('post_uuid');
            $userUuid = $request->jsonBodyField('user_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }



        try {
            $newLikeUuid = UUID::random();
            $post = $this->postsRepository->get(new UUID($postUuid));
            $user = $this->usersRepository->get(new UUID($userUuid));
        } catch (AppException $e) {
            return new ErrorResponse($e->getMessage());
        } catch (InvalidArgumentException $e) {
        }

        $this->likesRepository->save(new Like(
            $newLikeUuid,
            $post,
            $user
        ));

        return new SuccessFulResponse(
            ['uuid' => (string)$newLikeUuid]
        );
    }
}