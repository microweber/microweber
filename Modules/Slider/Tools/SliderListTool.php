<?php

declare(strict_types=1);

namespace Modules\Slider\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Slider\Models\Slider;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's slider slides.
 *
 * Exposes the Slider module over MCP — lists slides (name, description, link,
 * button text, media), optionally filtered by a search term. Read-only.
 */
class SliderListTool extends BaseTool
{
    protected string $domain = 'slider';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'slider_list',
            'List the site slider slides (name, description excerpt, link, button '
            . 'text and whether media is set). Optionally filter by a search term.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the slide name or description.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of slides to return (1-100). Default 30.',
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

            $rows = Slider::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%");
                    });
                })
                ->orderBy('position')
                ->limit($limit)
                ->get(['id', 'name', 'description', 'link', 'button_text', 'media', 'position'])
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'description' => mb_substr(trim(strip_tags((string) $s->description)), 0, 160),
                        'link' => $s->link,
                        'button_text' => $s->button_text,
                        'has_media' => trim((string) $s->media) !== '',
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'slides' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read slider slides: ' . $e->getMessage());
        }
    }
}
