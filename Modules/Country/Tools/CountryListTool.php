<?php

declare(strict_types=1);

namespace Modules\Country\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Country\Models\Country;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the countries reference list.
 *
 * Exposes the Country module over MCP — looks up countries by name or ISO code
 * (with phone code), for shipping/billing/address contexts. Read-only.
 */
class CountryListTool extends BaseTool
{
    protected string $domain = 'country';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'country_list',
            'Look up countries (name, ISO code, phone code). Optionally filter by a '
            . 'search term matching the country name or code.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the country name or ISO code.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of countries to return (1-300). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 50);
            if ($limit < 1 || $limit > 300) {
                $limit = 50;
            }

            $rows = Country::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('code', 'like', "%{$term}%");
                    });
                })
                ->orderBy('name')
                ->limit($limit)
                ->get(['id', 'code', 'name', 'phonecode'])
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'code' => $c->code,
                        'name' => $c->name,
                        'phone_code' => $c->phonecode,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'countries' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read countries: ' . $e->getMessage());
        }
    }
}
