<?php

declare(strict_types=1);

namespace Modules\Currency\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Currency\Models\ExchangeRate;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read configured currency exchange rates.
 *
 * Exposes the Currency module over MCP — lists exchange rates (from/to, rate,
 * source, active), optionally filtered by a currency code. Read-only.
 */
class CurrencyRatesTool extends BaseTool
{
    protected string $domain = 'currency';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'currency_rates',
            'List configured currency exchange rates (from currency, to currency, '
            . 'rate, source, active state). Optionally filter by a currency code.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'currency',
                type: PropertyType::STRING,
                description: 'Optional currency code to filter by (matches either the '
                    . 'from or to currency), e.g. "USD".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of rates to return (1-200). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $code = strtoupper(trim((string) ($args['currency'] ?? '')));
            $limit = (int) ($args['limit'] ?? 50);
            if ($limit < 1 || $limit > 200) {
                $limit = 50;
            }

            $rows = ExchangeRate::query()
                ->when($code !== '', function ($q) use ($code) {
                    $q->where(function ($w) use ($code) {
                        $w->where('from_currency', $code)->orWhere('to_currency', $code);
                    });
                })
                ->orderBy('from_currency')
                ->limit($limit)
                ->get(['id', 'from_currency', 'to_currency', 'rate', 'source', 'is_active'])
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'from' => $r->from_currency,
                        'to' => $r->to_currency,
                        'rate' => (float) $r->rate,
                        'source' => $r->source,
                        'active' => (int) $r->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'rates' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read currency rates: ' . $e->getMessage());
        }
    }
}
