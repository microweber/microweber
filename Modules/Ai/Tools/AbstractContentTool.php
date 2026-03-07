<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Base\AbstractContentTool as BaseAbstractContentTool;
use Modules\Content\Models\Content;
use Modules\CustomFields\Models\CustomField;

/**
 * Abstract Content Tool - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools AbstractContentTool
 * while maintaining backward compatibility with Microweber-specific models.
 *
 * @deprecated Use MicroweberPackages\AiTools\Base\AbstractContentTool with ContentRepositoryInterface
 */
abstract class AbstractContentTool extends BaseAbstractContentTool
{
    protected string $contentType = 'content';
    protected array $requiredPermissions = ['view content'];

    /**
     * Build content query with Microweber models.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildContentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Content::query()
            ->where('is_deleted', 0)
            ->where('content_type', $this->contentType);

        return $query;
    }

    /**
     * Apply filters to query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $params): \Illuminate\Database\Eloquent\Builder
    {
        // Extract parameters
        $search_term = $params['search_term'] ?? '';
        $is_active = $params['is_active'] ?? 'all';
        $parent_id = $params['parent_id'] ?? null;
        $category_id = $params['category_id'] ?? null;
        $custom_fields = $params['custom_fields'] ?? '';
        $sort_by = $params['sort_by'] ?? 'position';

        // Filter by active status
        if ($is_active !== 'all') {
            $query->where('is_active', (int) $is_active);
        }

        // Filter by parent ID
        if ($parent_id !== null) {
            $query->where('parent', $parent_id);
        }

        // Filter by category
        if ($category_id) {
            $query->whereHas('categories', function ($q) use ($category_id) {
                $q->where('categories.id', $category_id);
            });
        }

        // Search using keyword filter trait
        if (!empty($search_term)) {
            $query->filter(['keyword' => $search_term]);
        }

        // Filter by custom fields
        if (!empty($custom_fields)) {
            $customFieldsArray = $this->parseCustomFields($custom_fields);
            if (!empty($customFieldsArray)) {
                $query->filter(['customFields' => $customFieldsArray]);
            }
        }

        // Sort results
        $validSortFields = ['title', 'created_at', 'updated_at', 'position'];
        if (in_array($sort_by, $validSortFields)) {
            $query->orderBy($sort_by, $sort_by === 'created_at' ? 'desc' : 'asc');
        } else {
            $query->orderBy('position', 'asc');
        }

        return $query;
    }

    /**
     * Format custom fields for display.
     *
     * @param Content $item
     * @return string
     */
    protected function formatCustomFields($item): string
    {
        $customFields = [];

        // Get custom fields for this content
        $fields = CustomField::where('rel_id', $item->id)
            ->where('rel_type', get_class($item))
            ->get();

        foreach ($fields as $field) {
            $value = is_array($field->value) ? implode(', ', $field->value) : $field->value;
            if ($value) {
                $customFields[] = "<small><strong>{$field->name}:</strong> {$value}</small>";
            }
        }

        return $customFields ? implode('<br>', $customFields) : '<small class="text-muted">No custom fields</small>';
    }

    /**
     * Get content by ID.
     *
     * @param int $id
     * @return Content|null
     */
    protected function getContentById(int $id): ?Content
    {
        return Content::where('id', $id)->first();
    }

    /**
     * Update content.
     *
     * @param Content $content
     * @param array $data
     * @return bool
     */
    protected function updateContent(Content $content, array $data): bool
    {
        try {
            // Update basic fields
            $fillableFields = ['title', 'content_body', 'description', 'url', 'is_active'];
            foreach ($fillableFields as $field) {
                if (isset($data[$field])) {
                    $content->$field = $data[$field];
                }
            }

            // Handle custom fields
            if (isset($data['custom_fields'])) {
                $customFieldsData = $this->parseCustomFields($data['custom_fields']);
                foreach ($customFieldsData as $fieldName => $fieldValue) {
                    $content->setCustomField([
                        'name' => $fieldName,
                        'name_key' => $fieldName,
                        'value' => [$fieldValue]
                    ]);
                }
            }

            return $content->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create new content.
     *
     * @param array $data
     * @return Content|null
     */
    protected function createContent(array $data): ?Content
    {
        try {
            $content = new Content();
            $content->content_type = $this->contentType;

            // Set basic fields
            $fillableFields = ['title', 'content_body', 'description', 'url', 'is_active', 'parent'];
            foreach ($fillableFields as $field) {
                if (isset($data[$field])) {
                    $content->$field = $data[$field];
                }
            }

            if ($content->save()) {
                // Handle custom fields after saving
                if (isset($data['custom_fields'])) {
                    $customFieldsData = $this->parseCustomFields($data['custom_fields']);
                    foreach ($customFieldsData as $fieldName => $fieldValue) {
                        $content->setCustomField([
                            'name' => $fieldName,
                            'name_key' => $fieldName,
                            'value' => [$fieldValue]
                        ]);
                    }
                    $content->save();
                }

                return $content;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Attach media URLs to content.
     *
     * @param int $contentId
     * @param array $mediaUrls
     * @return void
     */
    protected function attachMediaUrls(int $contentId, array $mediaUrls): void
    {
        if (empty($mediaUrls)) {
            return;
        }

        foreach ($mediaUrls as $mediaUrl) {
            if (!empty($mediaUrl) && filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
                // Save media URL as custom field or use Microweber's media functionality
                if (function_exists('save_custom_field')) {
                    save_custom_field([
                        'field' => 'media_url',
                        'value' => $mediaUrl,
                        'rel_type' => 'content',
                        'rel_id' => $contentId,
                        'type' => 'media'
                    ]);
                }
            }
        }
    }
}
