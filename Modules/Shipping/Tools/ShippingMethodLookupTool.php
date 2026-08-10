<?php

declare(strict_types=1);

namespace Modules\Shipping\Tools;

use Modules\Shipping\Models\ShippingProvider;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ShippingMethodLookupTool extends AbstractShippingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'shipping_method_lookup',
            'Search configured shipping methods by provider, status, or default selection without exposing raw settings.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'provider_id', type: PropertyType::INTEGER, description: 'Optional shipping provider ID to fetch directly.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional provider name or code search term.', required: false),
            new ToolProperty(name: 'provider', type: PropertyType::STRING, description: 'Optional driver code such as flat_rate or shipping_to_country.', required: false),
            new ToolProperty(name: 'is_active', type: PropertyType::STRING, description: 'Optional yes/no active-state filter.', required: false),
            new ToolProperty(name: 'is_default', type: PropertyType::STRING, description: 'Optional yes/no default-provider filter.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum shipping methods to return (1-50). Default is 20.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $providerId = isset($args['provider_id']) ? (int) $args['provider_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $providerCode = $this->normalizeProviderCode($args['provider'] ?? '');
        $activeFilter = $this->normalizeNullableBoolean($args['is_active'] ?? null);
        $defaultFilter = $this->normalizeNullableBoolean($args['is_default'] ?? null);
        $limit = $this->safeLimit($args['limit'] ?? 20, 20, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view shipping methods.');
        }

        try {
            $query = ShippingProvider::query();

            if ($providerId !== null && $providerId > 0) {
                $query->where('id', $providerId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('provider', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($providerCode !== '') {
                $query->where('provider', $providerCode);
            }

            if ($activeFilter !== null) {
                $query->where('is_active', $activeFilter ? 1 : 0);
            }

            if ($defaultFilter !== null) {
                $query->where('is_default', $defaultFilter ? 1 : 0);
            }

            $providers = $query
                ->orderByDesc('is_default')
                ->orderBy('position')
                ->orderBy('id')
                ->limit($limit)
                ->get();

            $summaryBits = [];
            if ($searchTerm !== '') {
                $summaryBits[] = 'Search: "' . e($searchTerm) . '"';
            }
            if ($providerCode !== '') {
                $summaryBits[] = 'Driver: ' . e($providerCode);
            }

            $header = '<h4>Shipping method lookup</h4><p>'
                . ($summaryBits !== [] ? implode(' | ', $summaryBits) . ' | ' : '')
                . '<strong>Found:</strong> ' . $providers->count() . ' shipping method(s)</p>';

            $rows = $providers->map(function (ShippingProvider $provider): array {
                return [
                    'method' => $this->shippingProviderTitle($provider),
                    'status' => ((int) $provider->is_active === 1 ? 'Active' : 'Inactive'),
                    'default' => $this->yesNoLabel((int) $provider->is_default === 1),
                    'summary' => $this->shippingMethodSummary($provider),
                    'zones' => (string) count($this->countryZones($provider, true)),
                    'position' => (string) ($provider->position ?? 0),
                ];
            })->all();

            return $header . $this->formatAsHtmlTable(
                $rows,
                [
                    'method' => 'Method',
                    'status' => 'Status',
                    'default' => 'Default',
                    'summary' => 'Configuration summary',
                    'zones' => 'Zone count',
                    'position' => 'Position',
                ],
                'No shipping methods matched the requested filters.',
                'shipping-method-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading shipping methods: ' . $exception->getMessage());
        }
    }
}
