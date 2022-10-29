<?php

namespace Ackapga\Habrahabr\Blog;

class Comment
{
    public function __construct(
        private UUID  $uuid,
        private UUID  $post_uuid,
        private UUID  $author_uuid,
        private string $text,
    )
    {
    }

    public function __toString(): string
    {
        return $this->text;
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
     * @return UUID
     */
    public function getPostUuid(): UUID
    {
        return $this->post_uuid;
    }

    /**
     * @param UUID $post_uuid
     */
    public function setPostUuid(UUID $post_uuid): void
    {
        $this->post_uuid = $post_uuid;
    }

    /**
     * @return UUID
     */
    public function getAuthorUuid(): UUID
    {
        return $this->author_uuid;
    }

    /**
     * @param UUID $author_uuid
     */
    public function setAuthorUuid(UUID $author_uuid): void
    {
        $this->author_uuid = $author_uuid;
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