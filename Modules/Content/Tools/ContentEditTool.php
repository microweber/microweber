<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ContentEditTool extends AbstractContentTool
{
    protected string $domain = 'content';
    protected string $contentType = 'content';
    protected array $requiredPermissions = ['edit content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'content_edit',
            'Edit existing content items in Microweber CMS including updating title, content, description, status, and custom fields.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'id',
                type: PropertyType::INTEGER,
                description: 'ID of the content to edit.',
                required: true,
            ),
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'New title for the content.',
                required: false,
            ),
            new ToolProperty(
                name: 'content_body',
                type: PropertyType::STRING,
                description: 'New content/body text.',
                required: false,
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'New description/excerpt.',
                required: false,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'New URL slug for the content.',
                required: false,
            ),
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::STRING,
                description: 'Publication status. Options: "1" for published, "0" for unpublished.',
                required: false,
            ),
            new ToolProperty(
                name: 'custom_fields',
                type: PropertyType::STRING,
                description: 'Custom fields to update in format "field_name:value,field_name2:value2".',
                required: false,
            ),
            new ToolProperty(
                name: 'media_urls',
                type: PropertyType::STRING,
                description: 'Comma-separated list of media URLs to attach to the content',
                required: false
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $content_id = $args['id'] ?? null;
        $title = $args['title'] ?? '';
        $content_body = $args['content_body'] ?? '';
        $description = $args['description'] ?? '';
        $url = $args['url'] ?? '';
        $is_active = $args['is_active'] ?? '';
        $custom_fields = $args['custom_fields'] ?? '';
        $media_urls = $args['media_urls'] ?? '';
        
        // Convert comma-separated string to array
        $media_urls_array = [];
        if (!empty($media_urls)) {
            $media_urls_array = array_map('trim', explode(',', $media_urls));
            $media_urls_array = array_filter($media_urls_array, function($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });
        }

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to edit content.');
        }

        if (!$content_id) {
            return $this->handleError('Content ID is required.');
        }

        try {
            // Find the content
            $content = $this->getContentById($content_id);
            if (!$content) {
                return $this->handleError("Content with ID {$content_id} not found.");
            }

            // Prepare update data
            $updateData = [];
            if (!empty($title)) $updateData['title'] = $title;
            if (!empty($content_body)) $updateData['content_body'] = $content_body;
            if (!empty($description)) $updateData['description'] = $description;
            if (!empty($url)) $updateData['url'] = $url;
            if ($is_active !== '') $updateData['is_active'] = (int)$is_active;
            if (!empty($custom_fields)) $updateData['custom_fields'] = $custom_fields;

            if (empty($updateData)) {
                return $this->handleError('No fields to update provided.');
            }

            // Update the content
            $success = $this->updateContent($content, $updateData);

            if (!$success) {
                return $this->handleError('Failed to update content.');
            }

            // Audit log
            $this->auditWriteOperation('edit', 'content', $content_id, $updateData);

            // Handle media URLs if provided
            if (!empty($media_urls_array)) {
                $this->attachMediaUrls($content->id, $media_urls_array);
            }

            // Reload to get updated data
            $content->refresh();

            return $this->formatContentUpdateResult($content, $updateData);

        } catch (\Exception $e) {
            return $this->handleError('Error editing content: ' . $e->getMessage());
        }
    }

    protected function formatContentUpdateResult($content, array $updateData): string
    {
        $statusBadge = $this->getContentStatusBadge($content->is_active ?? 0);
        $typeBadge = $this->getContentTypeBadge($content->content_type ?? 'content');

        $updatedFields = [];
        foreach (array_keys($updateData) as $field) {
            $updatedFields[] = "<span class='badge bg-info'>" . ucfirst(str_replace('_', ' ', $field)) . "</span>";
        }
        $fieldsUpdated = implode(' ', $updatedFields);

        $customFieldsInfo = $this->formatCustomFields($content);

        return "
        <div class='content-edit-result'>
            <div class='alert alert-success'>
                <h5><i class='fas fa-check-circle me-2'></i>Content Updated Successfully</h5>
                <p class='mb-2'>Updated fields: {$fieldsUpdated}</p>
            </div>

            <div class='card'>
                <div class='card-header d-flex justify-content-between align-items-center'>
                    <h6 class='mb-0'><i class='fas fa-file-alt me-2'></i>Content Details</h6>
                    <div>
                        {$typeBadge}
                        {$statusBadge}
                    </div>
                </div>
                <div class='card-body'>
                    <table class='table table-sm'>
                        <tr>
                            <th width='150'>ID:</th>
                            <td>#{$content->id}</td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td>{$content->title}</td>
                        </tr>
                        <tr>
                            <th>URL:</th>
                            <td>" . ($content->url ?: '<em>No URL</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>" . ($content->description ?: '<em>No description</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Content:</th>
                            <td>" . ($content->content_body ? \Str::limit(strip_tags($content->content_body), 200) : '<em>No content</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Custom Fields:</th>
                            <td>{$customFieldsInfo}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>" . ($content->updated_at ? $content->updated_at->format('M j, Y H:i:s') : 'Unknown') . "</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>";
    }
}
