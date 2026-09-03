<?php

declare(strict_types=1);

namespace Modules\Faq\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Faq\Models\Faq;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's FAQ entries.
 *
 * Exposes the Faq module over MCP — lists question/answer pairs with their
 * active state, optionally filtered by a search term. Read-only.
 */
class FaqListTool extends BaseTool
{
    protected string $domain = 'faq';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'faq_list',
            'List the site FAQ entries (question, answer excerpt, active state). '
            . 'Optionally filter by a search term matching the question or answer.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the question or answer.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of FAQ entries to return (1-100). Default 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 30);
            if ($limit < 1 || $limit > 100) {
                $limit = 30;
            }

            $rows = Faq::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('question', 'like', "%{$term}%")
                            ->orWhere('answer', 'like', "%{$term}%");
                    });
                })
                ->orderBy('position')
                ->limit($limit)
                ->get(['id', 'question', 'answer', 'is_active', 'position'])
                ->map(function ($f) {
                    return [
                        'id' => $f->id,
                        'question' => $f->question,
                        'answer_excerpt' => mb_substr(trim(strip_tags((string) $f->answer)), 0, 200),
                        'active' => (int) $f->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'faqs' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read FAQ entries: ' . $e->getMessage());
        }
    }
}
