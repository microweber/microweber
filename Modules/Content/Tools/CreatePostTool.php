<?php

namespace Modules\Content\Tools;

use NeuronAI\Tools\ToolProperty;
use NeuronAI\Tools\PropertyType;
use Modules\Post\Models\Post;

class CreatePostTool extends CreateContentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct($dependencies);
        $this->name = 'create_post';
        $this->description = 'Create new blog post';
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'The title of the blog post',
                required: true
            ),
            new ToolProperty(
                name: 'content_body',
                type: PropertyType::STRING,
                description: 'Full content body of the blog post',
                required: true
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'Post excerpt/description',
                required: false
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'URL slug for the post',
                required: false
            ),

            new ToolProperty(
                name: 'media_urls',
                type: PropertyType::STRING,
                description: 'Comma-separated list of media URLs to attach to the post',
                required: false
            ),
        ];
    }

    public function __invoke(...$args): string
    {


        // Extract parameters from args array using keys
        $title = $args['title'] ?? null;
        $content_body = $args['content_body'] ?? null;
        $description = $args['description'] ?? null;
        $url = $args['url'] ?? null;
        $media_urls = $args['media_urls'] ?? '';
        
        // Convert comma-separated string to array
        $media_urls_array = [];
        if (!empty($media_urls)) {
            $media_urls_array = array_map('trim', explode(',', $media_urls));
            $media_urls_array = array_filter($media_urls_array, function($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });
        }

        // Validate required parameters
        if (empty($title)) {
            return $this->handleError('Title is required for post creation.');
        }
        if (empty($content_body)) {
            return $this->handleError('Content body is required for post creation.');
        }

        // Generate URL if not provided
        if (empty($url) && !empty($title)) {
            $url = $this->generateSlug($title);
        }

        // Generate description if not provided
        if (empty($description) && !empty($content_body)) {
            $description = $this->generateExcerpt($content_body);
        }

        // Create the post data
        $postData = [
            'title' => $title,
            'content_body' => $content_body,
            'description' => $description,
            'url' => $url,
            'content_type' => 'post',
            'subtype' => 'post',
            'is_active' => 1,
            'created_by' => \user_id()
        ];

        $post = Post::create($postData);

        // Audit log
        $this->auditWriteOperation('create', 'post', $post->id, $postData);

        // Handle media URLs if provided
        if (!empty($media_urls_array)) {
            $this->attachMediaUrls($post->id, $media_urls_array);
        }

        return $this->handleSuccess("Blog post created successfully with ID: {$post->id}") .
            $this->formatContentDetails($post);
    }

    /**
     * Generate excerpt from content body
     */
    private function generateExcerpt(string $content, int $length = 200): string
    {
        // Strip HTML tags
        $text = strip_tags($content);

        // Trim whitespace
        $text = trim($text);

        // Truncate to specified length
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length);
            // Find last space to avoid cutting words
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = substr($text, 0, $lastSpace);
            }
            $text .= '...';
        }

        return $text;
    }
}
