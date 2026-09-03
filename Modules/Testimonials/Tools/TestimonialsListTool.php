<?php

declare(strict_types=1);

namespace Modules\Testimonials\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Testimonials\Models\Testimonial;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's testimonials.
 *
 * Exposes the Testimonials module over MCP — lists testimonials (author, role,
 * company, excerpt), optionally filtered by a search term. Read-only.
 */
class TestimonialsListTool extends BaseTool
{
    protected string $domain = 'testimonials';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'testimonials_list',
            'List the site testimonials (author name, role, company and a text '
            . 'excerpt). Optionally filter by a search term.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against author name, company or content.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of testimonials to return (1-100). Default 25.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 25);
            if ($limit < 1 || $limit > 100) {
                $limit = 25;
            }

            $rows = Testimonial::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('client_company', 'like', "%{$term}%")
                            ->orWhere('content', 'like', "%{$term}%");
                    });
                })
                ->orderBy('position')
                ->limit($limit)
                ->get(['id', 'name', 'client_role', 'client_company', 'content', 'position'])
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'author' => $t->name,
                        'role' => $t->client_role,
                        'company' => $t->client_company,
                        'excerpt' => mb_substr(trim(strip_tags((string) $t->content)), 0, 160),
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'testimonials' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read testimonials: ' . $e->getMessage());
        }
    }
}
