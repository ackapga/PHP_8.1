<?php

namespace Ackapga\Habrahabr\Blog;

use Ackapga\Habrahabr\Person\User;

class Post
{
    public function __construct(
        private UUID   $uuid,
        private User   $author_uuid,
        private string $title,
        private string $text,
    )
    {
    }

    public function __toString(): string
    {
        return $this->title . PHP_EOL . $this->text;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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