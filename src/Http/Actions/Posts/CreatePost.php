<?php

namespace Ackapga\Habrahabr\Http\Actions\Posts;

use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {

        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException|InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
           $user = $this->usersRepository->get($authorUuid);

        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();
        try {

            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);
        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }

}