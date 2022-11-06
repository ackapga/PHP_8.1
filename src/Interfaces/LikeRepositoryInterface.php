<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\UUID;

interface LikeRepositoryInterface
{
    public function getByPostUuid(UUID $uuid): Like;
    public function save(Like $comment): void;
}
