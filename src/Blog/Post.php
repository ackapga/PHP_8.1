<?php

namespace Ackapga\Habrahabr\Blog;

class Post
{
    public function __construct(
        private ?UUID   $uuid = null,
        private ?UUID   $author_uuid = null,
        private ?string $title = null,
        private ?string $text = null,
    )
    {
    }

    public function __toString(): string
    {
        return $this->title . PHP_EOL . $this->text;
    }

    /**
     * @return UUID|null
     */
    public function getUuid(): ?UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID|null $uuid
     */
    public function setUuid(?UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UUID|null
     */
    public function getAuthorUuid(): ?UUID
    {
        return $this->author_uuid;
    }

    /**
     * @param UUID|null $author_uuid
     */
    public function setAuthorUuid(?UUID $author_uuid): void
    {
        $this->author_uuid = $author_uuid;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

}