<?php

namespace Ackapga\Habrahabr\Blog;

use Ackapga\Habrahabr\Person\User;

class Like
{
    public function __construct(
        private UUID $uuid,
        private Post $post_uuid,
        private User $user_uuid,
        private bool $isLike
    )
    {
    }

}