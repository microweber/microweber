<?php

declare(strict_types=1);

namespace Modules\Cart\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Cart\Models\Cart;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP read tool: inspect cart line items. The cart is a state manager over
 * Content/Product rows — each cart row is one line item keyed to an order
 * (order_id, after checkout) or a live/abandoned session (session_id). Given one
 * of those, list its items; with neither, list the most recent cart rows.
 */
class CartLookupTool extends BaseTool
{
    protected string $domain = 'shop';
    protected array $requiredPermissions = ['view orders'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'cart_lookup',
            'Inspect cart contents. Pass order_id to see a placed order\'s cart, '
            . 'session_id to see a live/abandoned cart, or neither to list the most '
            . 'recent cart line items.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'order_id',
                type: PropertyType::INTEGER,
                description: 'Show the cart line items belonging to this order.',
                required: false,
            ),
            new ToolProperty(
                name: 'session_id',
                type: PropertyType::STRING,
                description: 'Show the cart line items for this (live/abandoned) session.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of line items to return (1-100). Default 50.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to view carts.');
        }

        $orderId = (int) ($args['order_id'] ?? 0);
        $sessionId = trim((string) ($args['session_id'] ?? ''));
        $limit = (int) ($args['limit'] ?? 50);
        $limit = max(1, min(100, $limit));

        try {
            $query = Cart::query();
            $scope = 'most recent';
            if ($orderId > 0) {
                $query->where('order_id', $orderId);
                $scope = "order #{$orderId}";
            } elseif ($sessionId !== '') {
                $query->where('session_id', $sessionId);
                $scope = "session {$sessionId}";
            }

            $items = $query->orderByDesc('id')->limit($limit)->get();

            if ($items->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Product', 'qty' => 'Qty', 'price' => 'Price'],
                    "No cart items found for {$scope}.",
                    'cart-empty'
                );
            }

            $rows = [];
            $total = 0.0;
            foreach ($items as $item) {
                $lineQty = (float) ($item->qty ?? 0);
                $linePrice = (float) ($item->price ?? 0);
                $total += $lineQty * $linePrice;
                $rows[] = [
                    'title' => (string) ($item->title ?? 'Item'),
                    'qty' => (string) ($item->qty ?? ''),
                    'price' => (string) ($item->price ?? ''),
                ];
            }

            $header = '<div class="cart-lookup mb-2"><strong>Cart (' . htmlspecialchars($scope) . '):</strong> '
                . count($rows) . ' line item(s), computed total ' . number_format($total, 2) . '</div>';

            return $header . $this->formatAsHtmlTable(
                $rows,
                ['title' => 'Product', 'qty' => 'Qty', 'price' => 'Price'],
                'No cart items.',
                'cart-items'
            );
        } catch (\Throwable $e) {
            return $this->handleError('Error loading cart: ' . $e->getMessage());
        }
    }
}
