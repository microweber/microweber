<?php

declare(strict_types=1);

namespace Modules\Order\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Order\Models\Order;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP write tool: change an order's status. Saving an Order with a changed
 * order_status auto-logs an OrderStatusHistory row (Order::boot), so the change
 * is audited. Like the other MCP write tools, this has no per-tool permission
 * gate — the MCP server's client-token / allowed-modules layer authorises it.
 */
class OrderStatusUpdateTool extends BaseTool
{
    protected string $domain = 'shop';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'order_update_status',
            'Change an order\'s status. Allowed values come from the shop\'s order '
            . 'status list (e.g. new, pending, completed). The change is recorded in '
            . 'the order status history.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'order_id',
                type: PropertyType::INTEGER,
                description: 'The order ID to update.',
                required: true,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'The new status: one of the configured order statuses (new, pending, completed).',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $orderId = (int) ($args['order_id'] ?? 0);
        $status = strtolower(trim((string) ($args['status'] ?? '')));

        if ($orderId <= 0) {
            return $this->handleError('A valid order_id is required.');
        }

        $allowed = array_keys(Order::getOrderStatuses());
        if ($status === '' || !in_array($status, $allowed, true)) {
            return $this->handleError('status must be one of: ' . implode(', ', $allowed) . '.');
        }

        try {
            $order = Order::find($orderId);
            if (!$order) {
                return $this->handleError("Order with ID {$orderId} not found.");
            }

            $previous = (string) ($order->order_status ?? '');
            if ($previous === $status) {
                return $this->handleSuccess("Order #{$orderId} is already \"{$status}\"; no change made.");
            }

            $order->order_status = $status;
            $order->save();

            return $this->handleSuccess(
                "Order #{$orderId} status changed from \"{$previous}\" to \"{$status}\" (recorded in history)."
            );
        } catch (\Throwable $e) {
            return $this->handleError('Error updating order status: ' . $e->getMessage());
        }
    }
}
