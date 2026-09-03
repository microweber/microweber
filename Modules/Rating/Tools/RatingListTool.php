<?php

declare(strict_types=1);

namespace Modules\Rating\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Rating\Models\Rating;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read content/product ratings.
 *
 * Exposes the Rating module over MCP — recent ratings (score, comment, the
 * content they are on), optionally filtered to one content id (with an average).
 * Read-only.
 */
class RatingListTool extends BaseTool
{
    protected string $domain = 'rating';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'rating_list',
            'List recent content/product ratings (score, comment excerpt, the '
            . 'content id rated). Optionally filter to one content id — then an '
            . 'average and count are also returned.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'rel_id',
                type: PropertyType::INTEGER,
                description: 'Optional content id to list ratings for a single item.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of ratings to return (1-100). Default 25.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $relId = (array_key_exists('rel_id', $args) && $args['rel_id']) ? (int) $args['rel_id'] : null;
            $limit = (int) ($args['limit'] ?? 25);
            if ($limit < 1 || $limit > 100) {
                $limit = 25;
            }

            $base = Rating::query()->when($relId !== null, fn ($q) => $q->where('rel_id', $relId));

            $rows = (clone $base)->orderByDesc('created_at')->limit($limit)
                ->get(['id', 'rel_id', 'rating', 'comment', 'created_at'])
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'content_id' => (int) $r->rel_id,
                        'rating' => (float) $r->rating,
                        'comment' => mb_substr(trim(strip_tags((string) $r->comment)), 0, 140),
                        'created_at' => (string) $r->created_at,
                    ];
                })->all();

            $out = ['count' => count($rows), 'ratings' => $rows];
            if ($relId !== null) {
                $out['content_id'] = $relId;
                $out['average'] = round((float) (clone $base)->avg('rating'), 2);
                $out['total_ratings'] = (clone $base)->count();
            }

            return json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read ratings: ' . $e->getMessage());
        }
    }
}
