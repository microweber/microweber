<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Contracts;

/**
 * Interface for content repository operations.
 *
 * This interface abstracts content operations to allow the AI Tools
 * package to work with different content implementations.
 */
interface ContentRepositoryInterface
{
    /**
     * Create new content.
     *
     * @param array<string, mixed> $data
     * @return object|null
     */
    public function create(array $data): ?object;

    /**
     * Find content by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object;

    /**
     * Update existing content.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete content.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Search content with filters.
     *
     * @param array<string, mixed> $filters
     * @return list<object|array<string, mixed>>
     */
    public function search(array $filters): array;

    /**
     * Query builder for complex queries.
     *
     * @param string $contentType
     * @return object
     */
    public function query(string $contentType = 'content'): object;

    /**
     * Generate a unique slug from a title.
     *
     * @param string $title
     * @param string|null $existingSlug
     * @return string
     */
    public function generateSlug(string $title, ?string $existingSlug = null): string;
}
