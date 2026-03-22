<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Ai\Facades\Ai;
use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Generate AI-powered descriptions for content items.
 * Automatically creates compelling summaries based on content analysis.
 */
class GenerateDescriptionTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['edit content'];
    protected ?int $maxTries = 500;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            name: 'generate_description',
            description: 'Generate AI-powered descriptions for content items. Analyzes content and creates compelling summaries that can be used as meta descriptions, excerpts, or promotional text.',
            dependencies: $dependencies
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'ID of the content item to generate a description for',
                required: true,
            ),
            new ToolProperty(
                name: 'description_type',
                type: PropertyType::STRING,
                description: 'Type of description to generate. Options: "meta" (SEO meta description, ~160 chars), "excerpt" (blog excerpt, ~250 chars), "summary" (detailed summary, ~500 chars), "promotional" (marketing copy, ~200 chars). Default: "meta"',
                required: false,
            ),
            new ToolProperty(
                name: 'tone',
                type: PropertyType::STRING,
                description: 'Tone of the description. Options: "professional", "casual", "persuasive", "informative", "enthusiastic", "formal". Default: "professional"',
                required: false,
            ),
            new ToolProperty(
                name: 'target_keywords',
                type: PropertyType::STRING,
                description: 'Comma-separated keywords to include in the description for SEO optimization',
                required: false,
            ),
            new ToolProperty(
                name: 'auto_update',
                type: PropertyType::BOOLEAN,
                description: 'Automatically update the content with the generated description. If false, returns the description without saving. Default: true',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $contentId = $params['content_id'] ?? null;
        $descriptionType = $params['description_type'] ?? 'meta';
        $tone = $params['tone'] ?? 'professional';
        $targetKeywords = $params['target_keywords'] ?? '';
        $autoUpdate = $params['auto_update'] ?? true;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to generate descriptions.');
        }

        if (!$contentId) {
            return $this->handleError('Content ID is required.');
        }

        try {
            $content = Content::find($contentId);
            if (!$content) {
                return $this->handleError("Content with ID {$contentId} not found.");
            }

            $description = $this->generateDescription($content, $descriptionType, $tone, $targetKeywords);

            if ($autoUpdate) {
                $updateField = $this->getUpdateField($descriptionType);
                $content->update([$updateField => $description]);
            }

            return $this->formatResults($content, $description, $descriptionType, $targetKeywords, $autoUpdate);

        } catch (\Exception $e) {
            return $this->handleError('Error generating description: ' . $e->getMessage());
        }
    }

    protected function generateDescription($content, string $type, string $tone, string $keywords): string
    {
        $contentText = $this->extractContentText($content);
        $maxLength = $this->getMaxLength($type);

        $prompt = $this->buildPrompt($content, $contentText, $type, $tone, $keywords, $maxLength);

        $response = Ai::sendToChat([
            ['role' => 'system', 'content' => 'You are an expert content writer specializing in creating compelling, engaging descriptions. You write clear, concise text that captures the essence of content while maintaining the requested tone.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        $description = is_array($response) ? $response['content'] ?? '' : $response;
        $description = strip_tags($description);
        $description = trim($description, '"');

        if (strlen($description) > $maxLength) {
            $description = substr($description, 0, $maxLength);
            $lastSpace = strrpos($description, ' ');
            if ($lastSpace !== false) {
                $description = substr($description, 0, $lastSpace);
            }
            $description .= '...';
        }

        return $description;
    }

    protected function extractContentText($content): string
    {
        $text = '';
        if (!empty($content->title)) {
            $text .= $content->title . '. ';
        }
        if (!empty($content->content_body)) {
            $text .= strip_tags($content->content_body);
        } elseif (!empty($content->content)) {
            $text .= strip_tags($content->content);
        }
        return trim($text);
    }

    protected function getMaxLength(string $type): int
    {
        return match ($type) {
            'meta' => 160,
            'excerpt' => 250,
            'promotional' => 200,
            'summary' => 500,
            default => 160,
        };
    }

    protected function getUpdateField(string $type): string
    {
        return match ($type) {
            'meta' => 'content_meta_description',
            'excerpt' => 'description',
            default => 'description',
        };
    }

    protected function buildPrompt($content, string $contentText, string $type, string $tone, string $keywords, int $maxLength): string
    {
        $typeDescriptions = [
            'meta' => 'SEO meta description that will appear in search results',
            'excerpt' => 'blog post excerpt for preview cards',
            'summary' => 'detailed summary of the content',
            'promotional' => 'promotional marketing copy',
        ];

        $typeDescription = $typeDescriptions[$type] ?? 'description';
        $prompt = "Create a compelling {$typeDescription} for the following content:\n\n";
        $prompt .= "Title: {$content->title}\n";
        $prompt .= "Type: {$content->content_type}\n";

        if (!empty($contentText)) {
            $context = strlen($contentText) > 2000 ? substr($contentText, 0, 2000) . '...' : $contentText;
            $prompt .= "Content:\n{$context}\n\n";
        }

        $prompt .= "Requirements:\n";
        $prompt .= "- Tone: {$tone}\n";
        $prompt .= "- Maximum length: {$maxLength} characters\n";

        if (!empty($keywords)) {
            $prompt .= "- Include these keywords naturally: {$keywords}\n";
        }

        if ($type === 'meta') {
            $prompt .= "- Write in a way that encourages clicks from search results\n";
            $prompt .= "- Include a subtle call-to-action or value proposition\n";
        } elseif ($type === 'promotional') {
            $prompt .= "- Focus on benefits and value proposition\n";
            $prompt .= "- Create urgency or excitement\n";
        }

        $prompt .= "\nProvide only the description text, nothing else.";

        return $prompt;
    }

    protected function formatResults($content, string $description, string $type, string $keywords, bool $autoUpdate): string
    {
        $typeLabels = [
            'meta' => 'SEO Meta Description',
            'excerpt' => 'Blog Excerpt',
            'summary' => 'Content Summary',
            'promotional' => 'Promotional Copy',
        ];

        $typeLabel = $typeLabels[$type] ?? ucfirst($type);
        $charCount = strlen($description);
        $statusBadge = $autoUpdate
            ? '<span class="badge bg-success">Saved to Content</span>'
            : '<span class="badge bg-info">Preview Only</span>';

        $html = "<div class='ai-description-generator mb-4'>";
        $html .= "<div class='card'>";
        $html .= "<div class='card-header d-flex justify-content-between align-items-center'>";
        $html .= "<h5 class='mb-0'><i class='fas fa-magic text-primary me-2'></i>{$typeLabel} Generated</h5>";
        $html .= "{$statusBadge}";
        $html .= "</div>";
        $html .= "<div class='card-body'>";

        $html .= "<div class='mb-3'>";
        $html .= "<h6 class='text-muted'>Content: <strong>" . htmlspecialchars($content->title) . "</strong> (ID: {$content->id})</h6>";
        if (!empty($keywords)) {
            $html .= "<small class='text-muted'>Target Keywords: <code>" . htmlspecialchars($keywords) . "</code></small>";
        }
        $html .= "</div>";

        $html .= "<div class='description-result p-3 bg-light rounded'>";
        $html .= "<div class='d-flex justify-content-between align-items-center mb-2'>";
        $html .= "<span class='badge bg-primary'>{$typeLabel}</span>";
        $html .= "<small class='text-muted'>{$charCount} characters</small>";
        $html .= "</div>";
        $html .= "<p class='mb-0' style='white-space: pre-wrap;'>" . htmlspecialchars($description) . "</p>";
        $html .= "</div>";

        if ($type === 'meta' && $charCount > 160) {
            $html .= "<div class='alert alert-warning mt-3 mb-0'>";
            $html .= "<i class='fas fa-exclamation-triangle me-2'></i>";
            $html .= "This description exceeds the recommended 160 character limit for SEO meta descriptions.";
            $html .= "</div>";
        }

        $html .= "</div></div></div>";

        return $html;
    }
}
