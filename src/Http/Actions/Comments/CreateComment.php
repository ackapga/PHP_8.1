<?php

namespace Ackapga\Habrahabr\Http\Actions\Comments;

use Ackapga\Habrahabr\Blog\Comment;
use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\HttpException;
use Ackapga\Habrahabr\Exceptions\InvalidArgumentException;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Http\Actions\ActionInterface;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\CommentsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\PostsRepositoryInterface;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface    $usersRepository,
        private PostsRepositoryInterface    $postsRepository,
        private CommentsRepositoryInterface $commentsRepository
    )
    {
    }

    /**
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {

        $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        $post = $this->postsRepository->get($postUuid);


        $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        $author = $this->usersRepository->get($authorUuid);


        $newCommentUuid = UUID::random();

        try {
            $comment = new Comment(
                $newCommentUuid,
                $post,
                $author,
                $request['text']
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);
        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }

}