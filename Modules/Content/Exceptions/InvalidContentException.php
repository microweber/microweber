<?php

namespace Modules\Content\Exceptions;

/**
 * Exception thrown when content data is invalid
 *
 * @package Modules\Content\Exceptions
 */
class InvalidContentException extends ContentException
{
    /**
     * Create exception for missing required field
     *
     * @param string $field
     * @return static
     */
    public static function missingRequiredField(string $field): self
    {
        $message = sprintf('Content is missing required field: %s', $field);
        return new static($message, 400, ['field' => $field]);
    }

    /**
     * Create exception for invalid content type
     *
     * @param string $contentType
     * @param array $allowedTypes
     * @return static
     */
    public static function invalidContentType(string $contentType, array $allowedTypes = []): self
    {
        $message = sprintf('Invalid content type: %s', $contentType);
        if (!empty($allowedTypes)) {
            $message .= sprintf('. Allowed types: %s', implode(', ', $allowedTypes));
        }
        return new static($message, 400, [
            'content_type' => $contentType,
            'allowed_types' => $allowedTypes,
        ]);
    }

    /**
     * Create exception for invalid URL
     *
     * @param string $url
     * @param string $reason
     * @return static
     */
    public static function invalidUrl(string $url, string $reason = ''): self
    {
        $message = sprintf('Invalid URL: %s', $url);
        if ($reason) {
            $message .= sprintf(' (%s)', $reason);
        }
        return new static($message, 400, ['url' => $url, 'reason' => $reason]);
    }

    /**
     * Create exception for duplicate URL
     *
     * @param string $url
     * @return static
     */
    public static function duplicateUrl(string $url): self
    {
        $message = sprintf('Content with URL already exists: %s', $url);
        return new static($message, 409, ['url' => $url]);
    }

    /**
     * Create exception for circular parent reference
     *
     * @param int $contentId
     * @param int $parentId
     * @return static
     */
    public static function circularParentReference(int $contentId, int $parentId): self
    {
        $message = sprintf('Cannot set content %d as its own parent (parent ID: %d)', $contentId, $parentId);
        return new static($message, 400, [
            'content_id' => $contentId,
            'parent_id' => $parentId,
        ]);
    }

    /**
     * Create exception for unauthorized save operation
     *
     * @param string $reason
     * @return static
     */
    public static function unauthorizedSave(string $reason = ''): self
    {
        $message = 'You are not authorized to save content';
        if ($reason) {
            $message .= sprintf(': %s', $reason);
        }
        return new static($message, 403, ['reason' => $reason]);
    }
}
