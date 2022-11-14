<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\PostLike;
use Ackapga\Habrahabr\Blog\UUID;

interface LikeRepositoryInterface
{
    public function getByPostUuid(UUID $uuid): array;
    public function save(PostLike $like): void;
}
