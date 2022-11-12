<?php

namespace Ackapga\Habrahabr\Interfaces;

use Ackapga\Habrahabr\Blog\Like;
use Ackapga\Habrahabr\Blog\UUID;

interface LikeRepositoryInterface
{
    public function getByPostUuid(UUID $uuid): array;
    public function save(Like $like): void;
}
