<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\Comment;
use Ackapga\Habrahabr\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function get(UUID $uuid): Comment;
    public function save(Comment $comment): void;
}