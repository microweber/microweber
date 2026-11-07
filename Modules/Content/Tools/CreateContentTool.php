<?php

namespace Modules\Content\Tools;

use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use NeuronAI\Tools\PropertyType;
use MicroweberPackages\AiTools\Base\BaseTool;
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
            // task-2026-06-06-mcpparent: root-level content MUST persist
            // parent=0, not NULL. The admin Pages listing / Live Edit
            // PageChip / page tree filter on `parent = 0`; without it,
            // tool-created pages render on the frontend but are invisible
            // in every admin page picker.
            'parent' => 0,
        ];

        // The template's content module renders the `content` field, and on a
        // Page/Post `content_body` mirrors it. A tool call that supplies only ONE
        // of them (the swarm's off-canvas lanes pass only `content_body`) would
        // leave the rendered `content` field empty, so the page shows the default
        // "My title" placeholder instead of the built HTML. Mirror whichever was
        // given into BOTH fields so the page actually renders its content.
        if (!empty($content) && !empty($content_body)) {
            $contentData['content'] = $content;
            $contentData['content_body'] = $content_body;
        } else {
            $html = !empty($content) ? $content : (!empty($content_body) ? $content_body : null);
            if (!empty($html)) {
                $contentData['content'] = $html;
                $contentData['content_body'] = $html;
            }
        }

        // Inherit the template of the page being EDITED, so a whole site built
        // from one open page (the swarm's off-canvas lanes create pages this way)
        // stays on ONE template. Without this a create_content page falls back to
        // the SITE DEFAULT template, which may differ from the page the build
        // started on — a different live_edit.css, so the new page loses the locked
        // design (e.g. a dark hero page beside white default pages). task-2026-11-07.
        try {
            if (empty($contentData['active_site_template']) && app()->bound('mw.ai.liveedit.context')) {
                $ctx = (array) app('mw.ai.liveedit.context');
                $editingTemplate = trim((string) ($ctx['editing_template'] ?? ''));
                if ($editingTemplate !== '') {
                    $contentData['active_site_template'] = $editingTemplate;
                }
            }
        } catch (\Throwable $e) {
            // Non-fatal — page just uses the site default template.
        }

        $contentData['created_by'] = \user_id();

        $newContent = Content::create($contentData);

        // Audit log
        $this->auditWriteOperation('create', 'content', $newContent->id, $contentData);

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

        // Check if Media model exists for relationship-based attachment
        $mediaModelClass = '\Modules\Media\Models\Media';
        $hasMediaModel = class_exists($mediaModelClass);

        foreach ($mediaUrls as $mediaUrl) {
            if (empty($mediaUrl) || !filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
                continue;
            }

            if ($hasMediaModel) {
                // Create media record using Eloquent model
                try {
                    $mediaModelClass::create([
                        'rel_type' => $this->contentMorphName(),
                        'rel_id' => $contentId,
                        'filename' => $mediaUrl,
                        'title' => basename($mediaUrl),
                        'media_type' => 'image',
                    ]);
                } catch (\Exception $e) {
                    // Fallback to save_media function if model creation fails
                    save_media([
                        'rel_type' => $this->contentMorphName(),
                        'rel_id' => $contentId,
                        'filename' => $mediaUrl,
                    ]);
                }
            } else {
                // Fallback to save_media function
                save_media([
                    'rel_type' => $this->contentMorphName(),
                    'rel_id' => $contentId,
                    'filename' => $mediaUrl,
                ]);
            }
        }
    }

    /**
     * Resolve the content morph type for media relations.
     */
    protected function contentMorphName(): string
    {
        if (function_exists('morph_name')) {
            return (string) morph_name('content');
        }

        return Content::class;
    }
}
