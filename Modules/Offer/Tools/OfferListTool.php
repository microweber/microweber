<?php

declare(strict_types=1);

namespace Modules\Offer\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Offer\Models\Offer;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the shop's product special offers.
 *
 * Exposes the Offer module over MCP — lists product offers (product, offer
 * price, expiry, active), optionally filtered to a product or active offers.
 * Read-only.
 */
class OfferListTool extends BaseTool
{
    protected string $domain = 'offer';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'offer_list',
            'List the shop product special offers (product id, offer price, expiry, '
            . 'active state). Optionally filter to a product id or active offers only.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'product_id',
                type: PropertyType::INTEGER,
                description: 'Optional product id to list offers for a single product.',
                required: false,
            ),
            new ToolProperty(
                name: 'active_only',
                type: PropertyType::STRING,
                description: 'Set "yes" to return only active offers. Default all.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of offers to return (1-100). Default 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $activeOnly = strtolower(trim((string) ($args['active_only'] ?? ''))) === 'yes';
            $limit = (int) ($args['limit'] ?? 30);
            if ($limit < 1 || $limit > 100) {
                $limit = 30;
            }

            $rows = Offer::query()
                ->when(array_key_exists('product_id', $args) && $args['product_id'],
                    fn ($q) => $q->where('product_id', (int) $args['product_id']))
                ->when($activeOnly, fn ($q) => $q->where('is_active', 1))
                ->orderByDesc('id')
                ->limit($limit)
                ->get(['id', 'product_id', 'price_id', 'offer_price', 'expires_at', 'is_active'])
                ->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'product_id' => (int) $o->product_id,
                        'offer_price' => (float) $o->offer_price,
                        'expires_at' => (string) $o->expires_at,
                        'active' => (int) $o->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'offers' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read offers: ' . $e->getMessage());
        }
    }
}
