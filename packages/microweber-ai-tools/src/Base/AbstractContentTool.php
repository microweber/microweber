<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Base;

use MicroweberPackages\AiTools\Contracts\ContentRepositoryInterface;
use MicroweberPackages\AiTools\Contracts\ToolInterface;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Abstract base class for content-related tools.
 *
 * Provides content-specific functionality:
 * - Content querying and filtering
 * - Custom field handling
 * - Content formatting
 * - CRUD operations through repository
 */
abstract class AbstractContentTool extends BaseTool implements ToolInterface
{
    protected string $contentType = 'content';
    protected array $requiredPermissions = ['view content'];

    /**
     * @param array<string, mixed> $dependencies
     */
    public function __construct(
        string $name,
        string $description,
        array $dependencies = [],
        protected ?ContentRepositoryInterface $contentRepository = null
    ) {
        parent::__construct($name, $description, $dependencies);
    }

    /**
     * Set the content repository.
     *
     * @param ContentRepositoryInterface $repository
     * @return void
     */
    public function setContentRepository(ContentRepositoryInterface $repository): void
    {
        $this->contentRepository = $repository;
    }

    /**
     * Get base properties common to content tools.
     *
     * @return array
     */
    /**
     * @return list<object>
     */
    protected function getBaseProperties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Search term to find in title, content, or description.',
                required: false,
            ),
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::STRING,
                description: 'Filter by publication status. Options: "1" for published, "0" for unpublished, or "all" for both.',
                required: false,
            ),
            new ToolProperty(
                name: 'parent_id',
                type: PropertyType::INTEGER,
                description: 'Filter by parent page ID. Use 0 for top-level content.',
                required: false,
            ),
            new ToolProperty(
                name: 'category_id',
                type: PropertyType::INTEGER,
                description: 'Filter by category ID.',
                required: false,
            ),
            new ToolProperty(
                name: 'custom_fields',
                type: PropertyType::STRING,
                description: 'Filter by custom fields in format "field_name:value,field_name2:value2".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of results to return (1-100). Default is 20.',
                required: false,
            ),
            new ToolProperty(
                name: 'sort_by',
                type: PropertyType::STRING,
                description: 'Sort results by field. Options: "title", "created_at", "updated_at", "position". Default is "position".',
                required: false,
            ),
        ];
    }

    /**
     * Parse custom fields string into array.
     *
     * @param string $customFields
     * @return array
     */
    /**
     * @return array<string, string>
     */
    protected function parseCustomFields(string $customFields): array
    {
        /** @var array<string, string> $result */
        $result = [];
        $pairs = explode(',', $customFields);

        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if (str_contains($pair, ':')) {
                [$field, $value] = explode(':', $pair, 2);
                $result[trim($field)] = trim($value);
            }
        }

        return $result;
    }

    /**
     * Format custom fields for display.
     *
     * @param object $item
     * @return string
     */
    protected function formatCustomFields(object $item): string
    {
        // This should be implemented by the consuming application
        // through the content repository
        return '<small class="text-muted">No custom fields</small>';
    }

    /**
     * Get status badge HTML.
     *
     * @param mixed $isActive
     * @return string
     */
    protected function getContentStatusBadge(mixed $isActive): string
    {
        return $isActive
            ? "<span class='badge bg-success'>Published</span>"
            : "<span class='badge bg-warning'>Unpublished</span>";
    }

    /**
     * Get content type badge HTML.
     *
     * @param string $contentType
     * @return string
     */
    protected function getContentTypeBadge(string $contentType): string
    {
        $badgeClass = match ($contentType) {
            'page' => 'bg-primary',
            'post' => 'bg-info',
            'product' => 'bg-success',
            default => 'bg-secondary'
        };

        return "<span class='badge {$badgeClass}'>" . ucfirst($contentType) . '</span>';
    }

    /**
     * Search for content.
     *
     * @param array $filters
     * @return array
     */
    /**
     * @param array<string, mixed> $filters
     * @return list<object|array<string, mixed>>
     */
    protected function searchContent(array $filters): array
    {
        if ($this->contentRepository === null) {
            throw new \RuntimeException('Content repository not set');
        }

        return $this->contentRepository->search($filters);
    }

    /**
     * Get content by ID.
     *
     * @param int $id
     * @return object|null
     */
    protected function getContentById(int $id): ?object
    {
        if ($this->contentRepository === null) {
            throw new \RuntimeException('Content repository not set');
        }

        return $this->contentRepository->findById($id);
    }

    /**
     * Create new content.
     *
     * @param array $data
     * @return object|null
     */
    /**
     * @param array<string, mixed> $data
     */
    protected function createContent(array $data): ?object
    {
        if ($this->contentRepository === null) {
            throw new \RuntimeException('Content repository not set');
        }

        return $this->contentRepository->create($data);
    }

    /**
     * Update existing content.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    /**
     * @param array<string, mixed> $data
     */
    protected function updateContent(int $id, array $data): bool
    {
        if ($this->contentRepository === null) {
            throw new \RuntimeException('Content repository not set');
        }

        return $this->contentRepository->update($id, $data);
    }

    /**
     * Generate a unique slug from a title.
     *
     * @param string $title
     * @param string|null $existingSlug
     * @return string
     */
    protected function generateSlug(string $title, ?string $existingSlug = null): string
    {
        if ($this->contentRepository === null) {
            // Fallback implementation
            $slug = strtolower(trim($title));
            $slug = (string) preg_replace('/[^a-z0-9-]/', '-', $slug);
            $slug = (string) preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');

            return $slug;
        }

        return $this->contentRepository->generateSlug($title, $existingSlug);
    }
}
