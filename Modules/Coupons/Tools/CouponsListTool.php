<?php

declare(strict_types=1);

namespace Modules\Coupons\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Coupons\Models\Coupon;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the shop's discount coupons.
 *
 * Exposes the Coupons module over MCP — lists coupons (code, discount, validity,
 * usage), optionally filtered by a search term or active state. Read-only.
 */
class CouponsListTool extends BaseTool
{
    protected string $domain = 'coupons';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'coupons_list',
            'List the shop discount coupons (code, discount type/value, validity '
            . 'window, active state and usage count). Optionally filter by a search '
            . 'term or active state.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the coupon name or code.',
                required: false,
            ),
            new ToolProperty(
                name: 'active_only',
                type: PropertyType::STRING,
                description: 'Set "yes" to return only active coupons. Default all.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of coupons to return (1-100). Default 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $activeOnly = strtolower(trim((string) ($args['active_only'] ?? ''))) === 'yes';
            $limit = (int) ($args['limit'] ?? 30);
            if ($limit < 1 || $limit > 100) {
                $limit = 30;
            }

            $rows = Coupon::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('coupon_name', 'like', "%{$term}%")
                            ->orWhere('coupon_code', 'like', "%{$term}%");
                    });
                })
                ->when($activeOnly, fn ($q) => $q->where('is_active', 1))
                ->orderByDesc('id')
                ->limit($limit)
                ->get([
                    'id', 'coupon_name', 'coupon_code', 'discount_type', 'discount_value',
                    'is_active', 'uses_per_coupon', 'times_used', 'valid_from', 'valid_to',
                ])
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->coupon_name,
                        'code' => $c->coupon_code,
                        'discount' => ['type' => $c->discount_type, 'value' => (float) $c->discount_value],
                        'active' => (int) $c->is_active === 1,
                        'usage' => ['used' => (int) $c->times_used, 'limit' => $c->uses_per_coupon !== null ? (int) $c->uses_per_coupon : null],
                        'valid_from' => (string) $c->valid_from,
                        'valid_to' => (string) $c->valid_to,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'coupons' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read coupons: ' . $e->getMessage());
        }
    }
}
