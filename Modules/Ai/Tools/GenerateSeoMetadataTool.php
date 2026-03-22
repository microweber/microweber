<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Ai\Facades\Ai;
use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Generate comprehensive SEO metadata for content items.
 * Creates optimized titles, descriptions, keywords, and Open Graph tags.
 */
class GenerateSeoMetadataTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['edit content'];
    protected ?int $maxTries = 500;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            name: 'generate_seo_metadata',
            description: 'Generate comprehensive SEO metadata including optimized titles, meta descriptions, keywords, Open Graph tags, and Twitter Cards for content items. Improves search engine visibility and social sharing.',
            dependencies: $dependencies
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'ID of the content item to generate SEO metadata for',
                required: true,
            ),
            new ToolProperty(
                name: 'target_keywords',
                type: PropertyType::STRING,
                description: 'Comma-separated primary keywords to optimize for (e.g., "wordpress hosting, web hosting, cheap hosting")',
                required: true,
            ),
            new ToolProperty(
                name: 'generate_title',
                type: PropertyType::BOOLEAN,
                description: 'Generate an optimized SEO title (50-60 chars). Default: true',
                required: false,
            ),
            new ToolProperty(
                name: 'generate_description',
                type: PropertyType::BOOLEAN,
                description: 'Generate an optimized meta description (150-160 chars). Default: true',
                required: false,
            ),
            new ToolProperty(
                name: 'generate_keywords',
                type: PropertyType::BOOLEAN,
                description: 'Generate relevant keywords list. Default: true',
                required: false,
            ),
            new ToolProperty(
                name: 'generate_og_tags',
                type: PropertyType::BOOLEAN,
                description: 'Generate Open Graph tags for social sharing. Default: true',
                required: false,
            ),
            new ToolProperty(
                name: 'auto_apply',
                type: PropertyType::BOOLEAN,
                description: 'Automatically apply the generated metadata to the content. If false, returns preview only. Default: true',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $contentId = $params['content_id'] ?? null;
        $targetKeywords = $params['target_keywords'] ?? '';
        $generateTitle = $params['generate_title'] ?? true;
        $generateDescription = $params['generate_description'] ?? true;
        $generateKeywords = $params['generate_keywords'] ?? true;
        $generateOgTags = $params['generate_og_tags'] ?? true;
        $autoApply = $params['auto_apply'] ?? true;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to generate SEO metadata.');
        }

        if (!$contentId) {
            return $this->handleError('Content ID is required.');
        }

        if (empty($targetKeywords)) {
            return $this->handleError('Target keywords are required for SEO optimization.');
        }

        try {
            $content = Content::find($contentId);
            if (!$content) {
                return $this->handleError("Content with ID {$contentId} not found.");
            }

            $seoData = $this->generateSeoMetadata(
                $content,
                $targetKeywords,
                $generateTitle,
                $generateDescription,
                $generateKeywords,
                $generateOgTags
            );

            if ($autoApply) {
                $this->applySeoMetadata($content, $seoData);
            }

            return $this->formatResults($content, $seoData, $targetKeywords, $autoApply);

        } catch (\Exception $e) {
            return $this->handleError('Error generating SEO metadata: ' . $e->getMessage());
        }
    }

    protected function generateSeoMetadata(
        $content,
        string $targetKeywords,
        bool $generateTitle,
        bool $generateDescription,
        bool $generateKeywords,
        bool $generateOgTags
    ): array {
        $contentText = $this->extractContentText($content);
        $keywordsArray = array_map('trim', explode(',', $targetKeywords));
        $primaryKeyword = $keywordsArray[0] ?? '';

        $seoData = [];

        if ($generateTitle) {
            $seoData['title'] = $this->generateSeoTitle($content, $primaryKeyword);
        }

        if ($generateDescription) {
            $seoData['description'] = $this->generateSeoDescription($content, $keywordsArray);
        }

        if ($generateKeywords) {
            $seoData['keywords'] = $this->generateKeywords($content, $contentText, $keywordsArray);
        }

        if ($generateOgTags) {
            $seoData['og_title'] = $seoData['title'] ?? $this->generateSeoTitle($content, $primaryKeyword);
            $seoData['og_description'] = $seoData['description'] ?? $this->generateSeoDescription($content, $keywordsArray);
            $seoData['og_type'] = $this->getOgType($content);
        }

        return $seoData;
    }

    protected function extractContentText($content): string
    {
        $text = '';
        if (!empty($content->content_body)) {
            $text .= strip_tags($content->content_body);
        } elseif (!empty($content->content)) {
            $text .= strip_tags($content->content);
        }
        if (!empty($content->description)) {
            $text .= ' ' . strip_tags($content->description);
        }
        return trim($text);
    }

    protected function generateSeoTitle($content, string $primaryKeyword): string
    {
        $prompt = "Create an SEO-optimized title (50-60 characters) for:\n\n";
        $prompt .= "Original Title: {$content->title}\n";
        $prompt .= "Primary Keyword: {$primaryKeyword}\n\n";
        $prompt .= "Requirements:\n";
        $prompt .= "- Include the primary keyword naturally\n";
        $prompt .= "- Make it compelling to encourage clicks\n";
        $prompt .= "- Keep it between 50-60 characters\n";
        $prompt .= "- Use power words when appropriate\n";
        $prompt .= "- Return only the title, nothing else";

        $response = Ai::sendToChat([
            ['role' => 'system', 'content' => 'You are an SEO expert who creates compelling, keyword-optimized titles.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        $title = is_array($response) ? $response['content'] ?? '' : $response;
        $title = strip_tags($title);
        $title = trim($title, '"');

        if (strlen($title) > 60) {
            $title = substr($title, 0, 60);
            $lastSpace = strrpos($title, ' ');
            if ($lastSpace !== false && $lastSpace > 40) {
                $title = substr($title, 0, $lastSpace);
            }
        }

        return $title;
    }

    protected function generateSeoDescription($content, array $keywords): string
    {
        $primaryKeyword = $keywords[0] ?? '';
        $secondaryKeywords = implode(', ', array_slice($keywords, 1, 2));

        $prompt = "Create an SEO-optimized meta description (150-160 characters) for:\n\n";
        $prompt .= "Title: {$content->title}\n";
        $prompt .= "Primary Keyword: {$primaryKeyword}\n";
        if (!empty($secondaryKeywords)) {
            $prompt .= "Secondary Keywords: {$secondaryKeywords}\n";
        }
        $prompt .= "\nRequirements:\n";
        $prompt .= "- Include the primary keyword naturally in the first 120 characters\n";
        $prompt .= "- Make it compelling with a subtle call-to-action\n";
        $prompt .= "- Keep it between 150-160 characters\n";
        $prompt .= "- Focus on the value/benefit to the reader\n";
        $prompt .= "- Return only the description, nothing else";

        $response = Ai::sendToChat([
            ['role' => 'system', 'content' => 'You are an SEO copywriter who creates compelling meta descriptions.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        $description = is_array($response) ? $response['content'] ?? '' : $response;
        $description = strip_tags($description);
        $description = trim($description, '"');

        if (strlen($description) > 160) {
            $description = substr($description, 0, 160);
            $lastSpace = strrpos($description, ' ');
            if ($lastSpace !== false) {
                $description = substr($description, 0, $lastSpace) . '...';
            }
        }

        return $description;
    }

    protected function generateKeywords($content, string $contentText, array $targetKeywords): string
    {
        $prompt = "Extract and suggest relevant SEO keywords for this content:\n\n";
        $prompt .= "Title: {$content->title}\n";
        $prompt .= "Target Keywords: " . implode(', ', $targetKeywords) . "\n";

        $context = strlen($contentText) > 1000 ? substr($contentText, 0, 1000) . '...' : $contentText;
        if (!empty($context)) {
            $prompt .= "Content: {$context}\n\n";
        }

        $prompt .= "Requirements:\n";
        $prompt .= "- Include the target keywords provided\n";
        $prompt .= "- Add 5-8 related long-tail keywords\n";
        $prompt .= "- Include location-based variations if relevant\n";
        $prompt .= "- Focus on keywords with commercial intent\n";
        $prompt .= "- Return as comma-separated list only, no explanations";

        $response = Ai::sendToChat([
            ['role' => 'system', 'content' => 'You are an SEO keyword research specialist.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        $keywords = is_array($response) ? $response['content'] ?? '' : $response;
        $keywords = strip_tags($keywords);
        $keywords = trim($keywords);

        $allKeywords = array_merge($targetKeywords, explode(',', $keywords));
        $allKeywords = array_map('trim', $allKeywords);
        $allKeywords = array_filter($allKeywords);
        $allKeywords = array_unique($allKeywords);

        return implode(', ', array_slice($allKeywords, 0, 15));
    }

    protected function getOgType($content): string
    {
        return match ($content->content_type) {
            'post' => 'article',
            'product' => 'product',
            default => 'website',
        };
    }

    protected function applySeoMetadata($content, array $seoData): void
    {
        $updateData = [];

        if (!empty($seoData['title'])) {
            $updateData['content_meta_title'] = $seoData['title'];
        }
        if (!empty($seoData['description'])) {
            $updateData['content_meta_description'] = $seoData['description'];
        }
        if (!empty($seoData['keywords'])) {
            $updateData['content_meta_keywords'] = $seoData['keywords'];
        }
        if (!empty($seoData['og_title'])) {
            $updateData['og_title'] = $seoData['og_title'];
        }
        if (!empty($seoData['og_description'])) {
            $updateData['og_description'] = $seoData['og_description'];
        }
        if (!empty($seoData['og_type'])) {
            $updateData['og_type'] = $seoData['og_type'];
        }

        if (!empty($updateData)) {
            $content->update($updateData);
        }
    }

    protected function formatResults($content, array $seoData, string $targetKeywords, bool $autoApply): string
    {
        $statusBadge = $autoApply
            ? '<span class="badge bg-success">Applied to Content</span>'
            : '<span class="badge bg-info">Preview Only</span>';

        $html = "<div class='seo-metadata-generator mb-4'>";
        $html .= "<div class='card'>";
        $html .= "<div class='card-header d-flex justify-content-between align-items-center'>";
        $html .= "<h5 class='mb-0'><i class='fas fa-search text-primary me-2'></i>SEO Metadata Generated</h5>";
        $html .= "{$statusBadge}";
        $html .= "</div>";
        $html .= "<div class='card-body'>";

        $html .= "<div class='mb-4'>";
        $html .= "<h6 class='text-muted'>Content: <strong>" . htmlspecialchars($content->title) . "</strong> (ID: {$content->id})</h6>";
        $html .= "<small class='text-muted'>Target Keywords: <code>" . htmlspecialchars($targetKeywords) . "</code></small>";
        $html .= "</div>";

        if (!empty($seoData['title'])) {
            $html .= $this->formatSeoField('Meta Title', $seoData['title'], 60, 'Recommended: 50-60 characters');
        }

        if (!empty($seoData['description'])) {
            $html .= $this->formatSeoField('Meta Description', $seoData['description'], 160, 'Recommended: 150-160 characters');
        }

        if (!empty($seoData['keywords'])) {
            $keywordsList = explode(', ', $seoData['keywords']);
            $keywordBadges = array_map(function ($kw) {
                return "<span class='badge bg-secondary me-1'>" . htmlspecialchars(trim($kw)) . "</span>";
            }, array_slice($keywordsList, 0, 10));

            $html .= "<div class='seo-field mb-3'>";
            $html .= "<label class='form-label fw-bold'>Keywords</label>";
            $html .= "<div class='p-2 bg-light rounded'>" . implode(' ', $keywordBadges);
            if (count($keywordsList) > 10) {
                $html .= " <span class='badge bg-light text-dark'>+" . (count($keywordsList) - 10) . " more</span>";
            }
            $html .= "</div></div>";
        }

        if (!empty($seoData['og_title'])) {
            $html .= "<div class='mt-4'><h6 class='border-bottom pb-2'><i class='fab fa-facebook text-primary me-2'></i>Open Graph Tags</h6>";
            $html .= $this->formatSeoField('og:title', $seoData['og_title']);
            $html .= $this->formatSeoField('og:description', $seoData['og_description']);
            $html .= $this->formatSeoField('og:type', $seoData['og_type']);
            $html .= "</div>";
        }

        $html .= "</div></div></div>";

        return $html;
    }

    protected function formatSeoField(string $label, string $value, ?int $maxLength = null, ?string $helpText = null): string
    {
        $charCount = strlen($value);
        $countBadge = '';

        if ($maxLength) {
            if ($charCount > $maxLength) {
                $countBadge = "<span class='badge bg-danger'>{$charCount}/{$maxLength} chars</span>";
            } elseif ($charCount > $maxLength * 0.9) {
                $countBadge = "<span class='badge bg-warning'>{$charCount}/{$maxLength} chars</span>";
            } else {
                $countBadge = "<span class='badge bg-success'>{$charCount}/{$maxLength} chars</span>";
            }
        }

        $html = "<div class='seo-field mb-3'>";
        $html .= "<div class='d-flex justify-content-between align-items-center mb-1'>";
        $html .= "<label class='form-label fw-bold mb-0'>" . htmlspecialchars($label) . "</label>";
        $html .= "{$countBadge}";
        $html .= "</div>";
        $html .= "<div class='form-control bg-light' style='min-height: 40px; white-space: pre-wrap;'>" . htmlspecialchars($value) . "</div>";
        if ($helpText) {
            $html .= "<div class='form-text text-muted'>{$helpText}</div>";
        }
        $html .= "</div>";

        return $html;
    }
}
