<?php

namespace Modules\Content\Exceptions;

/**
 * Exception thrown when content is not found
 *
 * @package Modules\Content\Exceptions
 */
class ContentNotFoundException extends ContentException
{
    /**
     * Create exception for content not found by ID
     *
     * @param int $contentId
     * @return static
     */
    public static function byId(int $contentId): self
    {
        $message = sprintf('Content not found with ID: %d', $contentId);
        return new static($message, 404, ['content_id' => $contentId]);
    }

    /**
     * Create exception for content not found by URL
     *
     * @param string $url
     * @return static
     */
    public static function byUrl(string $url): self
    {
        $message = sprintf('Content not found for URL: %s', $url);
        return new static($message, 404, ['url' => $url]);
    }

    /**
     * Create exception for content not found by title
     *
     * @param string $title
     * @return static
     */
    public static function byTitle(string $title): self
    {
        $message = sprintf('Content not found with title: %s', $title);
        return new static($message, 404, ['title' => $title]);
    }

    /**
     * Create exception for parent content not found
     *
     * @param int $parentId
     * @return static
     */
    public static function parentNotFound(int $parentId): self
    {
        $message = sprintf('Parent content not found with ID: %d', $parentId);
        return new static($message, 404, ['parent_id' => $parentId]);
    }
}
