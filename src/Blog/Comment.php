<?php

namespace Ackapga\Habrahabr\Blog;



use Ackapga\Habrahabr\Person\User;

class Comment
{
    public function __construct(
        private UUID  $uuid,
        private Post  $post_uuid,
        private User  $author_uuid,
        private string $text,
    )
    {
    }

    public function __toString(): string
    {
        return $this->text;
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Post
     */
    public function getPostUuid(): Post
    {
        return $this->post_uuid;
    }

    /**
     * @param Post $post_uuid
     */
    public function setPostUuid(Post $post_uuid): void
    {
        $this->post_uuid = $post_uuid;
    }

    /**
     * @return User
     */
    public function getAuthorUuid(): User
    {
        return $this->author_uuid;
    }

    /**
     * @param User $author_uuid
     */
    public function setAuthorUuid(User $author_uuid): void
    {
        $this->author_uuid = $author_uuid;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

}