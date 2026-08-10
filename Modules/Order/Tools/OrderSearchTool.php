<?php

declare(strict_types=1);

namespace Modules\Order\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Order\Models\Order;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class OrderSearchTool extends BaseTool
{
    protected string $domain = 'shop';
    protected array $requiredPermissions = ['view orders'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'order_search',
            'Search for orders in Microweber CMS. Find orders by ID, customer email, status, or date range.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Search term to find in order ID, customer email, or order details.',
                required: false,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Order status to filter by. Common values: "new", "processing", "completed", "cancelled".',
                required: false,
            ),
            new ToolProperty(
                name: 'date_from',
                type: PropertyType::STRING,
                description: 'Start date for order search (YYYY-MM-DD format).',
                required: false,
            ),
            new ToolProperty(
                name: 'date_to',
                type: PropertyType::STRING,
                description: 'End date for order search (YYYY-MM-DD format).',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of results to return (1-50). Default is 10.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $search_term = $args['search_term'] ?? '';
        $status = $args['status'] ?? '';
        $date_from = $args['date_from'] ?? '';
        $date_to = $args['date_to'] ?? '';
        $limit = $args['limit'] ?? 10;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to search orders.');
        }

        // Validate limit
        $limit = max(1, min(50, $limit));

        try {
            $query = Order::query();

            // Search by order ID or customer details
            if (!empty($search_term)) {
                $query->where(function ($q) use ($search_term) {
                    $q->where('id', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('email', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('first_name', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('order_reference_id', 'LIKE', '%' . $search_term . '%');
                });
            }

            // Filter by status
            if (!empty($status)) {
                $query->where('order_status', $status);
            }

            // Filter by date range
            if (!empty($date_from)) {
                $query->whereDate('created_at', '>=', $date_from);
            }
            if (!empty($date_to)) {
                $query->whereDate('created_at', '<=', $date_to);
            }

            // Order by creation date (newest first)
            $query->orderBy('created_at', 'desc');

            $orders = $query->limit($limit)->get();

            if ($orders->isEmpty()) {
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                $statusInfo = !empty($status) ? " with status '{$status}'" : '';
                $dateInfo = '';
                if (!empty($date_from) || !empty($date_to)) {
                    $dateInfo = " in date range" . 
                        (!empty($date_from) ? " from {$date_from}" : '') .
                        (!empty($date_to) ? " to {$date_to}" : '');
                }
                
                return $this->formatAsHtmlTable(
                    [],
                    ['id' => 'Order ID', 'customer' => 'Customer', 'status' => 'Status'],
                    "No orders found{$searchInfo}{$statusInfo}{$dateInfo}.",
                    'order-search-empty'
                );
            }

            return $this->formatOrdersAsHtml($orders, $search_term, $status, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error searching orders: ' . $e->getMessage());
        }
    }

    protected function formatOrdersAsHtml($orders, string $search_term, string $status, int $limit): string
    {
        $totalFound = $orders->count();
        
        $searchInfo = !empty($search_term) ? "Search: \"{$search_term}\" " : '';
        $statusInfo = !empty($status) ? "Status: {$status} " : '';
        
        $header = "
        <div class='order-search-header mb-3'>
            <h4><i class='fas fa-shopping-cart text-primary me-2'></i>Order Search Results</h4>
            <p class='mb-2'>
                {$searchInfo}{$statusInfo}
                <strong>Found:</strong> {$totalFound} order(s)" . 
                ($totalFound >= $limit ? " (showing first {$limit})" : '') . "
            </p>
        </div>";

            $tableData = [];
            foreach ($orders as $order) {
                $statusBadge = $this->getOrderStatusBadge($order->order_status ?? 'new');
                $total = $order->amount ?? 0;
                $formattedTotal = number_format($total, 2) . ' ' . ($order->currency ?? 'EUR');
                
                $customerName = trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? ''));
                $customerInfo = ($customerName !== '' ? $customerName : 'Unknown') .
                           ($order->email ? "<br><small class='text-muted'>{$order->email}</small>" : '');
             
                $orderDate = $order->created_at ? 
                $order->created_at->format('M j, Y H:i') : 
                'Unknown';

            $tableData[] = [
                'id' => "<strong>#{$order->id}</strong>" . 
                       ($order->order_reference_id ? "<br><small class='text-muted'>{$order->order_reference_id}</small>" : ''),
                'customer' => $customerInfo,
                'status' => $statusBadge,
                'total' => "<strong>{$formattedTotal}</strong>",
                'date' => $orderDate,
            ];
        }

        $table = $this->formatAsHtmlTable(
            $tableData,
            [
                'id' => 'Order ID',
                'customer' => 'Customer',
                'status' => 'Status',
                'total' => 'Total',
                'date' => 'Date',
            ],
            '',
            'order-search-results'
        );

        return $header . $table;
    }

    protected function getOrderStatusBadge(string $status): string
    {
        $badgeClass = match($status) {
            'new' => 'bg-info',
            'processing' => 'bg-warning',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'refunded' => 'bg-secondary',
            default => 'bg-light text-dark'
        };

        return "<span class='badge {$badgeClass}'>" . ucfirst($status) . "</span>";
    }
}
