<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\Post;
use Ackapga\Habrahabr\Blog\UUID;

interface PostsRepositoryInterface
{
    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
}