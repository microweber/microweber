<?php

declare(strict_types=1);

namespace Modules\Customer\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Customer\Models\Customer;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class CustomerLookupTool extends BaseTool
{
    protected string $domain = 'customer';
    protected array $requiredPermissions = ['view_customers'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'customer_lookup',
            'Look up customer information by email, phone, name, or ID.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::from('string'),
                description: 'Search term: customer ID, email, phone, or name to search for.',
                required: true,
            ),
            new ToolProperty(
                name: 'search_type',
                type: PropertyType::from('string'),
                description: 'Type of search: "id", "email", "phone", "name", or "all" (default).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $search_term = $args['search_term'] ?? '';
        $search_type = $args['search_type'] ?? 'all';
        
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to view customer information.');
        }

        if (empty($search_term)) {
            return $this->handleError('Search term cannot be empty. Please provide a customer ID, email, phone, or name.');
        }

        try {
            $query = Customer::query();

            switch ($search_type) {
                case 'id':
                    if (!is_numeric($search_term)) {
                        return $this->handleError('Customer ID must be a number.');
                    }
                    $query->where('id', $search_term);
                    break;

                case 'email':
                    $query->where('email', 'LIKE', '%' . $search_term . '%');
                    break;

                case 'phone':
                    $query->where('phone', 'LIKE', '%' . $search_term . '%');
                    break;

                case 'name':
                    $query->where(function ($q) use ($search_term) {
                        $q->where('name', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('first_name', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('last_name', 'LIKE', '%' . $search_term . '%');
                    });
                    break;

                case 'all':
                default:
                    $query->where(function ($q) use ($search_term) {
                        if (is_numeric($search_term)) {
                            $q->where('id', $search_term);
                        }
                        $q->orWhere('email', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('phone', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('name', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('first_name', 'LIKE', '%' . $search_term . '%')
                          ->orWhere('last_name', 'LIKE', '%' . $search_term . '%');
                    });
                    break;
            }

            $customers = $query->with(['addresses', 'orders'])->limit(10)->get();

            if ($customers->isEmpty()) {
                return $this->handleError("No customers found matching '{$search_term}'.");
            }

            return $this->formatCustomersAsHtml($customers);

        } catch (\Exception $e) {
            return $this->handleError('Error searching for customers: ' . $e->getMessage());
        }
    }

    private function formatCustomersAsHtml($customers): string
    {
        $html = '<div class="customers-results">';
        
        foreach ($customers as $customer) {
            $html .= '<div class="card mb-3">';
            $html .= '<div class="card-header">';
            $html .= '<h5 class="mb-0">Customer #' . $customer->id . '</h5>';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            
            // Basic customer info table
            $html .= '<table class="table table-sm mb-3">';
            $html .= '<tbody>';
            $html .= '<tr><th width="120">Name:</th><td>' . htmlspecialchars($customer->getFullName()) . '</td></tr>';
            $html .= '<tr><th>Email:</th><td>' . htmlspecialchars($customer->getEmail()) . '</td></tr>';
            $html .= '<tr><th>Phone:</th><td>' . htmlspecialchars($customer->getPhone()) . '</td></tr>';
            $html .= '<tr><th>Status:</th><td><span class="badge badge-' . ($customer->status === 'active' ? 'success' : 'secondary') . '">' . htmlspecialchars($customer->status) . '</span></td></tr>';
            $html .= '<tr><th>Registered:</th><td>' . $customer->created_at->format('Y-m-d H:i:s') . '</td></tr>';
            $html .= '</tbody>';
            $html .= '</table>';
            
            // Addresses
            if ($customer->addresses && $customer->addresses->count() > 0) {
                $html .= '<h6>Addresses:</h6>';
                $html .= '<div class="row">';
                foreach ($customer->addresses as $address) {
                    $html .= '<div class="col-md-6 mb-2">';
                    $html .= '<div class="card card-body small">';
                    $html .= '<strong>' . ucfirst($address->type ?? 'default') . '</strong><br>';
                    if ($address->address_street_1) {
                        $html .= htmlspecialchars($address->address_street_1) . '<br>';
                    }
                    if ($address->city) {
                        $html .= htmlspecialchars($address->city);
                        if ($address->zip) {
                            $html .= ' ' . htmlspecialchars($address->zip);
                        }
                    }
                    $html .= '</div></div>';
                }
                $html .= '</div>';
            }
            
            // Order summary
            if ($customer->orders && $customer->orders->count() > 0) {
                $html .= '<h6>Recent Orders:</h6>';
                $html .= '<div class="table-responsive">';
                $html .= '<table class="table table-sm">';
                $html .= '<thead><tr><th>Order #</th><th>Date</th><th>Status</th><th>Total</th></tr></thead>';
                $html .= '<tbody>';
                foreach ($customer->orders->take(5) as $order) {
                    $html .= '<tr>';
                    $html .= '<td>#' . $order->id . '</td>';
                    $html .= '<td>' . $order->created_at->format('Y-m-d') . '</td>';
                    $html .= '<td><span class="badge badge-info">' . htmlspecialchars($order->order_status ?? 'pending') . '</span></td>';
                    $html .= '<td>' . $this->formatMoney($order->amount ?? 0) . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody></table>';
                $html .= '</div>';
                
                if ($customer->orders->count() > 5) {
                    $html .= '<small class="text-muted">Showing 5 most recent orders of ' . $customer->orders->count() . ' total.</small>';
                }
            }
            
            $html .= '</div></div>';
        }
        
        $html .= '</div>';
        
        if ($customers->count() === 10) {
            $html .= '<div class="alert alert-info">Showing first 10 results. Use more specific search terms to narrow results.</div>';
        }
        
        return $html;
    }
}
