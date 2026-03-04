<?php

namespace Modules\Ai\Tools;

use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use NeuronAI\Tools\PropertyType;
use Modules\Ai\Tools\BaseTool;
use Modules\Content\Models\Content;

class CreateContentTool extends BaseTool
{
    protected ?int $maxTries = 5000;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            name: 'create_content',
            description: 'Create new content/page in the CMS',
            dependencies: $dependencies
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'The title of the content/page',
                required: true
            ),
            new ToolProperty(
                name: 'content',
                type: PropertyType::STRING,
                description: 'Main content text',
                required: false
            ),
            new ToolProperty(
                name: 'content_body',
                type: PropertyType::STRING,
                description: 'Full content body',
                required: false
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'URL slug for the content',
                required: false
            ),
            new ToolProperty(
                name: 'content_type',
                type: PropertyType::STRING,
                description: 'Type of content (page, post, product)',
                required: false
            ),
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::BOOLEAN,
                description: 'Whether the content is active',
                required: false
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
        // Extract parameters - args could be passed as an associative array
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $title = $params['title'] ?? null;
        $content = $params['content'] ?? null;
        $content_body = $params['content_body'] ?? null;
        $url = $params['url'] ?? null;
        $content_type = $params['content_type'] ?? 'page';
        $is_active = $params['is_active'] ?? true;
        $media_urls = $params['media_urls'] ?? '';

        // Convert comma-separated string to array
        $media_urls_array = [];
        if (!empty($media_urls)) {
            $media_urls_array = array_map('trim', explode(',', $media_urls));
            $media_urls_array = array_filter($media_urls_array, function ($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });
        }

        // Validate required parameters
        if (empty($title)) {
            return $this->handleError('Title is required for content creation.');
        }

        // Generate URL if not provided
        if (empty($url) && !empty($title)) {
            $url = $this->generateSlug($title);
        }

        // Create the content
        $contentData = [
            'title' => $title,
            'content_type' => $content_type,
            'subtype' => 'static',
            'url' => $url,
            'is_active' => $is_active ? 1 : 0,
        ];

        if (!empty($content)) {
            $contentData['content'] = $content;
        }

        if (!empty($content_body)) {
            $contentData['content_body'] = $content_body;
        }

        $contentData['created_by'] = user_id();

        $newContent = Content::create($contentData);

        // Handle media URLs if provided
        if (!empty($media_urls_array)) {
            $this->attachMediaUrls($newContent->id, $media_urls_array);
        }

        return $this->handleSuccess("Content created successfully with ID: {$newContent->id}") .
            $this->formatContentDetails($newContent);
    }

    /**
     * Generate a URL slug from title
     */
    protected function generateSlug(string $title): string
    {
        // Basic slug generation - convert to lowercase, replace spaces with hyphens
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;

        while (Content::where('url', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function formatContentDetails($content): string
    {
        return '
        <div class="card mt-3">
            <div class="card-header">
                <h5>Content Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> ' . $content->id . '</p>
                        <p><strong>Title:</strong> ' . htmlspecialchars($content->title) . '</p>
                        <p><strong>URL:</strong> ' . htmlspecialchars($content->url) . '</p>
                        <p><strong>Type:</strong> ' . htmlspecialchars($content->content_type) . '</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> ' . ($content->is_active ? 'Published' : 'Draft') . '</p>
                        <p><strong>Created:</strong> ' . $content->created_at->format('Y-m-d H:i:s') . '</p>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Attach media URLs to content
     */
    protected function attachMediaUrls(int $contentId, array $mediaUrls): void
    {
        if (empty($mediaUrls)) {
            return;
        }


        foreach ($mediaUrls as $mediaUrl) {
            if (!empty($mediaUrl) && filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
                save_media(array(
                    'rel_type' => morph_name('content'),
                    'rel_id' => $contentId,
                    'filename' => $mediaUrl,
                ));
            }
        }
    }
}
