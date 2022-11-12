<?php

namespace Ackapga\Habrahabr\Blog;

use Ackapga\Habrahabr\Person\User;

class Like
{
    public function __construct(
        private UUID $uuid,
        private Post $post_uuid,
        private User $user_uuid,
    )
    {
    }

    public function __toString(): string
    {
        return $this->uuid;
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
    public function getUserUuid(): User
    {
        return $this->user_uuid;
    }

    /**
     * @param User $user_uuid
     */
    public function setUserUuid(User $user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

}