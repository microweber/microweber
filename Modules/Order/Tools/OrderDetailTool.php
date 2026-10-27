<?php

declare(strict_types=1);

namespace Modules\Order\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Order\Models\Order;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP read tool: full detail for a single order — header fields, customer,
 * totals, status and its line items. Complements order.lookup (OrderSearchTool),
 * which only lists matching orders.
 */
class OrderDetailTool extends BaseTool
{
    protected string $domain = 'shop';
    protected array $requiredPermissions = ['view orders'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'order_detail',
            'Get the full detail of a single order by its ID: customer, totals, '
            . 'status and the line items it contains.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'order_id',
                type: PropertyType::INTEGER,
                description: 'The order ID to retrieve.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to view orders.');
        }

        $orderId = (int) ($args['order_id'] ?? 0);
        if ($orderId <= 0) {
            return $this->handleError('A valid order_id is required.');
        }

        try {
            $order = Order::find($orderId);
            if (!$order) {
                return $this->handleError("Order with ID {$orderId} not found.");
            }

            $header = [
                'Order ID' => (string) $order->id,
                'Reference' => (string) ($order->order_reference_id ?? ''),
                'Status' => (string) ($order->order_status ?? ''),
                'Customer' => method_exists($order, 'customerName') ? (string) $order->customerName() : trim((string) ($order->first_name ?? '') . ' ' . (string) ($order->last_name ?? '')),
                'Email' => (string) ($order->email ?? ''),
                'Amount' => (string) ($order->amount ?? ''),
                'Currency' => (string) ($order->currency ?? ''),
                'Placed' => (string) ($order->created_at ?? ''),
            ];

            $items = Order::getOrderItems($orderId);
            $rows = [];
            foreach ($items as $item) {
                $item = (array) $item;
                $rows[] = [
                    'title' => (string) ($item['title'] ?? 'Item'),
                    'qty' => (string) ($item['qty'] ?? ''),
                    'price' => (string) ($item['price'] ?? ''),
                ];
            }

            $out = '<div class="order-detail mb-3"><h4>Order #' . (int) $order->id . '</h4><ul>';
            foreach ($header as $label => $value) {
                $out .= '<li><strong>' . htmlspecialchars($label) . ':</strong> ' . htmlspecialchars($value) . '</li>';
            }
            $out .= '</ul></div>';

            $out .= $this->formatAsHtmlTable(
                $rows,
                ['title' => 'Product', 'qty' => 'Qty', 'price' => 'Price'],
                'This order has no line items.',
                'order-items'
            );

            return $out;
        } catch (\Throwable $e) {
            return $this->handleError('Error loading order: ' . $e->getMessage());
        }
    }
}
