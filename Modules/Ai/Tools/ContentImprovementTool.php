<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Ai\Facades\Ai;
use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * AI-powered content improvement tool.
 * Analyzes content and provides suggestions for better readability, engagement, and SEO.
 */
class ContentImprovementTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['edit content'];
    protected ?int $maxTries = 500;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            name: 'content_improvement',
            description: 'Analyze and improve content quality with AI-powered suggestions. Provides recommendations for better readability, engagement, structure, grammar, and SEO optimization. Can optionally apply improvements automatically.',
            dependencies: $dependencies
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'ID of the content item to analyze and improve',
                required: true,
            ),
            new ToolProperty(
                name: 'analysis_type',
                type: PropertyType::STRING,
                description: 'Type of analysis to perform. Options: "readability" (reading level and clarity), "seo" (SEO optimization), "engagement" (hooks and calls-to-action), "grammar" (spelling and grammar), "structure" (headings and formatting), "comprehensive" (all aspects). Default: "comprehensive"',
                required: false,
            ),
            new ToolProperty(
                name: 'target_audience',
                type: PropertyType::STRING,
                description: 'Target audience for the content. Options: "general", "beginners", "experts", "technical", "business", "casual". Default: "general"',
                required: false,
            ),
            new ToolProperty(
                name: 'improvement_level',
                type: PropertyType::STRING,
                description: 'Level of changes to suggest. Options: "conservative" (minor fixes only), "moderate" (rewording and restructuring), "aggressive" (major rewrites). Default: "moderate"',
                required: false,
            ),
            new ToolProperty(
                name: 'auto_apply',
                type: PropertyType::BOOLEAN,
                description: 'Automatically apply suggested improvements to the content. If false, returns suggestions only. Default: false',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $contentId = $params['content_id'] ?? null;
        $analysisType = $params['analysis_type'] ?? 'comprehensive';
        $targetAudience = $params['target_audience'] ?? 'general';
        $improvementLevel = $params['improvement_level'] ?? 'moderate';
        $autoApply = $params['auto_apply'] ?? false;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to improve content.');
        }

        if (!$contentId) {
            return $this->handleError('Content ID is required.');
        }

        try {
            $content = Content::find($contentId);
            if (!$content) {
                return $this->handleError("Content with ID {$contentId} not found.");
            }

            $improvements = $this->analyzeAndImprove(
                $content,
                $analysisType,
                $targetAudience,
                $improvementLevel
            );

            if ($autoApply && !empty($improvements['improved_content'])) {
                $this->applyImprovements($content, $improvements);
            }

            return $this->formatResults($content, $improvements, $analysisType, $autoApply);

        } catch (\Exception $e) {
            return $this->handleError('Error analyzing content: ' . $e->getMessage());
        }
    }

    protected function analyzeAndImprove(
        $content,
        string $analysisType,
        string $targetAudience,
        string $improvementLevel
    ): array {
        $contentText = $this->extractContentText($content);

        if (empty($contentText)) {
            return [
                'error' => 'No content body found to analyze.',
                'suggestions' => [],
            ];
        }

        $prompt = $this->buildAnalysisPrompt(
            $content->title,
            $contentText,
            $analysisType,
            $targetAudience,
            $improvementLevel
        );

        $response = Ai::sendToChat([
            ['role' => 'system', 'content' => 'You are an expert content editor and SEO specialist. Analyze content and provide specific, actionable improvements in JSON format.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        $analysis = $this->parseAnalysisResponse(is_array($response) ? $response['content'] ?? '' : $response);

        $wordCount = str_word_count(strip_tags($contentText));
        $readabilityScore = $this->calculateReadabilityScore($contentText);

        return [
            'original_word_count' => $wordCount,
            'readability_score' => $readabilityScore,
            'suggestions' => $analysis['suggestions'] ?? [],
            'improved_content' => $analysis['improved_content'] ?? null,
            'summary' => $analysis['summary'] ?? 'Analysis completed.',
        ];
    }

    protected function extractContentText($content): string
    {
        $text = '';
        if (!empty($content->content_body)) {
            $text .= $content->content_body;
        } elseif (!empty($content->content)) {
            $text .= $content->content;
        }
        return trim($text);
    }

    protected function calculateReadabilityScore(string $text): int
    {
        $text = strip_tags($text);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($text);
        $syllables = $this->estimateSyllables($text);

        if ($words === 0) {
            return 0;
        }

        $avgSentenceLength = count($sentences) > 0 ? $words / count($sentences) : 0;
        $avgSyllablesPerWord = $words > 0 ? $syllables / $words : 0;

        $fleschScore = 206.835 - (1.015 * $avgSentenceLength) - (84.6 * $avgSyllablesPerWord);

        return (int) max(0, min(100, round($fleschScore)));
    }

    protected function estimateSyllables(string $text): int
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z]/', ' ', $text);
        $words = explode(' ', $text);
        $syllables = 0;

        foreach ($words as $word) {
            if (empty($word)) {
                continue;
            }
            $word = preg_replace('/e$/', '', $word);
            $matches = [];
            preg_match_all('/[aeiouy]{1,2}/', $word, $matches);
            $count = count($matches[0]);
            $syllables += max(1, $count);
        }

        return $syllables;
    }

    protected function buildAnalysisPrompt(
        string $title,
        string $contentText,
        string $analysisType,
        string $targetAudience,
        string $improvementLevel
    ): string {
        $analysisFocus = match ($analysisType) {
            'readability' => 'Focus on reading level, sentence length, paragraph structure, and clarity.',
            'seo' => 'Focus on keyword usage, heading structure, meta optimization, and search visibility.',
            'engagement' => 'Focus on hooks, calls-to-action, emotional appeal, and reader engagement.',
            'grammar' => 'Focus on grammar, spelling, punctuation, and language correctness.',
            'structure' => 'Focus on heading hierarchy, section organization, and content flow.',
            default => 'Analyze all aspects: readability, SEO, engagement, grammar, and structure.',
        };

        $levelInstruction = match ($improvementLevel) {
            'conservative' => 'Suggest only minor fixes - typos, grammar errors, and obvious improvements.',
            'moderate' => 'Suggest rewording for clarity, better structure, and improved flow while maintaining the original meaning.',
            'aggressive' => 'Feel free to rewrite sections for maximum impact, better structure, and higher engagement.',
            default => 'Suggest moderate improvements that enhance clarity and engagement.',
        };

        $audienceInstruction = match ($targetAudience) {
            'beginners' => 'Target beginners with simple language and clear explanations.',
            'experts' => 'Target experts with technical depth and industry terminology.',
            'technical' => 'Target technical readers with precise terminology and detailed explanations.',
            'business' => 'Target business professionals with professional tone and ROI focus.',
            'casual' => 'Use a conversational, friendly tone suitable for casual readers.',
            default => 'Target a general audience with accessible language.',
        };

        $prompt = "Analyze and improve this content:\n\n";
        $prompt .= "TITLE: {$title}\n\n";

        $context = strlen($contentText) > 3000 ? substr($contentText, 0, 3000) . '...' : $contentText;
        $prompt .= "CONTENT:\n{$context}\n\n";

        $prompt .= "INSTRUCTIONS:\n";
        $prompt .= "1. {$analysisFocus}\n";
        $prompt .= "2. {$levelInstruction}\n";
        $prompt .= "3. {$audienceInstruction}\n\n";

        $prompt .= "Return a JSON response with this structure:\n";
        $prompt .= "{\n";
        $prompt .= '  "summary": "Brief summary of the analysis",' . "\n";
        $prompt .= '  "suggestions": [' . "\n";
        $prompt .= '    {"type": "readability|seo|engagement|grammar|structure", "priority": "high|medium|low", "issue": "Description", "recommendation": "How to fix"}' . "\n";
        $prompt .= '  ],' . "\n";
        $prompt .= '  "improved_content": "The improved version of the content (if applicable)"' . "\n";
        $prompt .= "}\n\n";
        $prompt .= "Return ONLY valid JSON, no other text.";

        return $prompt;
    }

    protected function parseAnalysisResponse(string $response): array
    {
        $json = preg_replace('/^```json\s*|\s*```$/', '', trim($response));

        $data = json_decode($json, true);

        if (!$data || !is_array($data)) {
            return [
                'summary' => 'Analysis completed but could not parse detailed suggestions.',
                'suggestions' => [
                    [
                        'type' => 'general',
                        'priority' => 'medium',
                        'issue' => 'Could not parse AI response',
                        'recommendation' => 'Please review the content manually.',
                    ],
                ],
                'improved_content' => null,
            ];
        }

        return $data;
    }

    protected function applyImprovements($content, array $improvements): void
    {
        if (!empty($improvements['improved_content'])) {
            if (!empty($content->content_body)) {
                $content->update(['content_body' => $improvements['improved_content']]);
            } elseif (!empty($content->content)) {
                $content->update(['content' => $improvements['improved_content']]);
            }
        }
    }

    protected function formatResults($content, array $improvements, string $analysisType, bool $autoApply): string
    {
        if (isset($improvements['error'])) {
            return $this->handleError($improvements['error']);
        }

        $statusBadge = $autoApply
            ? '<span class="badge bg-success">Improvements Applied</span>'
            : '<span class="badge bg-info">Suggestions Only</span>';

        $analysisLabel = ucfirst($analysisType);
        $wordCount = $improvements['original_word_count'] ?? 0;
        $readabilityScore = $improvements['readability_score'] ?? 0;

        $readabilityBadge = $this->getReadabilityBadge($readabilityScore);

        $html = "<div class='content-improvement-tool mb-4'>";
        $html .= "<div class='card'>";
        $html .= "<div class='card-header d-flex justify-content-between align-items-center'>";
        $html .= "<h5 class='mb-0'><i class='fas fa-magic text-primary me-2'></i>{$analysisLabel} Analysis Results</h5>";
        $html .= "{$statusBadge}";
        $html .= "</div>";
        $html .= "<div class='card-body'>";

        $html .= "<div class='row mb-4'>";
        $html .= "<div class='col-md-6'>";
        $html .= "<h6 class='text-muted'>Content: <strong>" . htmlspecialchars($content->title) . "</strong></h6>";
        $html .= "<small class='text-muted'>Type: {$content->content_type}</small>";
        $html .= "</div>";
        $html .= "<div class='col-md-6 text-md-end'>";
        $html .= "<span class='badge bg-light text-dark me-2'>{$wordCount} words</span>";
        $html .= "<span class='badge bg-light text-dark me-2'>Readability: {$readabilityScore}/100</span>";
        $html .= "{$readabilityBadge}";
        $html .= "</div>";
        $html .= "</div>";

        $html .= "<div class='alert alert-info mb-4'>";
        $html .= "<h6 class='alert-heading'><i class='fas fa-info-circle me-2'></i>Summary</h6>";
        $html .= "<p class='mb-0'>" . htmlspecialchars($improvements['summary']) . "</p>";
        $html .= "</div>";

        if (!empty($improvements['suggestions'])) {
            $html .= "<h6 class='border-bottom pb-2 mb-3'><i class='fas fa-lightbulb text-warning me-2'></i>Improvement Suggestions</h6>";

            $grouped = [];
            foreach ($improvements['suggestions'] as $suggestion) {
                $type = $suggestion['type'] ?? 'general';
                $grouped[$type][] = $suggestion;
            }

            foreach ($grouped as $type => $suggestions) {
                $html .= "<div class='suggestion-group mb-3'>";
                $html .= "<h6 class='text-muted mb-2'>" . ucfirst($type) . "</h6>";

                foreach ($suggestions as $suggestion) {
                    $priority = $suggestion['priority'] ?? 'medium';
                    $priorityBadge = $this->getPriorityBadge($priority);

                    $html .= "<div class='suggestion-item p-3 bg-light rounded mb-2'>";
                    $html .= "<div class='d-flex justify-content-between align-items-start mb-1'>";
                    $html .= "<strong>" . htmlspecialchars($suggestion['issue'] ?? 'Issue') . "</strong>";
                    $html .= "{$priorityBadge}";
                    $html .= "</div>";
                    $html .= "<p class='mb-0 text-muted'>" . htmlspecialchars($suggestion['recommendation'] ?? '') . "</p>";
                    $html .= "</div>";
                }

                $html .= "</div>";
            }
        }

        if (!empty($improvements['improved_content']) && !$autoApply) {
            $html .= "<h6 class='border-bottom pb-2 mb-3 mt-4'><i class='fas fa-file-alt text-success me-2'></i>Improved Content Preview</h6>";
            $html .= "<div class='p-3 bg-light rounded' style='max-height: 300px; overflow-y: auto;'>";
            $html .= "<pre class='mb-0' style='white-space: pre-wrap;'>" . htmlspecialchars(substr($improvements['improved_content'], 0, 2000)) . "</pre>";
            if (strlen($improvements['improved_content']) > 2000) {
                $html .= "<p class='text-muted mt-2'>... (content truncated)</p>";
            }
            $html .= "</div>";
        }

        $html .= "</div></div></div>";

        return $html;
    }

    protected function getReadabilityBadge(int $score): string
    {
        if ($score >= 70) {
            return '<span class="badge bg-success">Easy</span>';
        } elseif ($score >= 50) {
            return '<span class="badge bg-info">Moderate</span>';
        } elseif ($score >= 30) {
            return '<span class="badge bg-warning">Difficult</span>';
        } else {
            return '<span class="badge bg-danger">Very Difficult</span>';
        }
    }

    protected function getPriorityBadge(string $priority): string
    {
        return match ($priority) {
            'high' => '<span class="badge bg-danger">High</span>',
            'medium' => '<span class="badge bg-warning">Medium</span>',
            default => '<span class="badge bg-secondary">Low</span>',
        };
    }
}
