<?php

declare(strict_types=1);

namespace Modules\Shipping\Tools;

use Modules\Shipping\Models\ShippingProvider;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ShippingZoneSummaryTool extends AbstractShippingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'shipping_zone_summary',
            'Summarize country-based shipping zones and pricing rules without exposing raw provider settings.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'provider_id', type: PropertyType::INTEGER, description: 'Optional shipping provider ID to inspect.', required: false),
            new ToolProperty(name: 'provider', type: PropertyType::STRING, description: 'Optional provider code filter such as shipping_to_country.', required: false),
            new ToolProperty(name: 'country', type: PropertyType::STRING, description: 'Optional country or Worldwide zone filter.', required: false),
            new ToolProperty(name: 'include_inactive_zones', type: PropertyType::STRING, description: 'Set to yes to include inactive zones.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum zone rows to include (1-100). Default is 20.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $providerId = isset($args['provider_id']) ? (int) $args['provider_id'] : null;
        $providerCode = $this->normalizeProviderCode($args['provider'] ?? '');
        $countryFilter = trim((string) ($args['country'] ?? ''));
        $includeInactive = $this->normalizeBooleanString($args['include_inactive_zones'] ?? false, false);
        $limit = $this->safeLimit($args['limit'] ?? 20, 20, 100);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view shipping zones.');
        }

        try {
            $query = ShippingProvider::query();

            if ($providerId !== null && $providerId > 0) {
                $query->where('id', $providerId);
            }

            if ($providerCode !== '') {
                $query->where('provider', $providerCode);
            } else {
                $query->where('provider', 'shipping_to_country');
            }

            $providers = $query->orderBy('position')->orderBy('id')->get();

            $rows = [];
            foreach ($providers as $provider) {
                foreach ($this->countryZones($provider, $includeInactive) as $zone) {
                    if ($countryFilter !== '' && stripos((string) $zone['country'], $countryFilter) === false) {
                        continue;
                    }

                    $rows[] = [
                        'provider' => $this->shippingProviderTitle($provider),
                        'country' => (string) $zone['country'],
                        'type' => $this->zoneTypeLabel((string) $zone['type']),
                        'costs' => $this->zoneCostSummary($zone),
                        'status' => $this->yesNoLabel((bool) ($zone['is_active'] ?? false)),
                    ];
                }
            }

            $rows = array_slice($rows, 0, $limit);

            return '<h4>Shipping zone summary</h4><p><strong>Found:</strong> ' . count($rows) . ' zone(s)</p>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'provider' => 'Provider',
                        'country' => 'Country',
                        'type' => 'Rule type',
                        'costs' => 'Cost summary',
                        'status' => 'Active',
                    ],
                    'No shipping zones matched the requested filters.',
                    'shipping-zone-summary-results'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading shipping zones: ' . $exception->getMessage());
        }
    }
}
