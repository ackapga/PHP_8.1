<?php

namespace Ackapga\Habrahabr\Blog\Interfaces;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Person\User;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}