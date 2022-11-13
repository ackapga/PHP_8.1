<?php

namespace Ackapga\Habrahabr\Http\Actions\Posts;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\PostNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;

class FindByUuidPost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
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
            $post = $this->postsRepository->get(new UUID($uuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        } catch (InvalidArgumentException $e) {
        }

        return new SuccessfulResponse([
            'author_uuid' => (string)$post->getAuthorUuid()->getUuidUser(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}