<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Tax\Models\TaxRate;
use Modules\Tax\Models\TaxType;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class TaxRuleLookupTool extends AbstractTaxTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'tax_rule_lookup',
            'Search modern tax rates and legacy tax rules by name, country, status, or identifier.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'rule_id', type: PropertyType::INTEGER, description: 'Optional tax rule identifier to fetch directly.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional rule name or description search term.', required: false),
            new ToolProperty(name: 'country_code', type: PropertyType::STRING, description: 'Optional country-code filter for location-based tax rates.', required: false),
            new ToolProperty(name: 'is_active', type: PropertyType::STRING, description: 'Optional yes/no active-state filter for tax rates.', required: false),
            new ToolProperty(name: 'include_legacy', type: PropertyType::STRING, description: 'Set to no to hide legacy tax types. Defaults to yes.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum tax rules to return (1-100). Default is 20.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $ruleId = isset($args['rule_id']) ? (int) $args['rule_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $countryCode = strtoupper(trim((string) ($args['country_code'] ?? '')));
        $activeFilter = $this->normalizeNullableBoolean($args['is_active'] ?? null);
        $includeLegacy = $this->normalizeBooleanString($args['include_legacy'] ?? true, true);
        $limit = $this->safeLimit($args['limit'] ?? 20, 20, 100);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view tax rules.');
        }

        try {
            $rateQuery = TaxRate::query()->with('country');

            if ($ruleId !== null && $ruleId > 0) {
                $rateQuery->where('id', $ruleId);
            }

            if ($searchTerm !== '') {
                $rateQuery->where(function ($builder) use ($searchTerm): void {
                    $builder->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('state_code', 'like', '%' . $searchTerm . '%')
                        ->orWhere('city', 'like', '%' . $searchTerm . '%')
                        ->orWhere('zip_code_pattern', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($countryCode !== '') {
                $rateQuery->where('country_code', $countryCode);
            }

            if ($activeFilter !== null) {
                $rateQuery->where('is_active', $activeFilter);
            }

            $rates = $rateQuery
                ->orderByDesc('priority')
                ->orderByDesc('is_default')
                ->orderBy('id')
                ->limit($limit)
                ->get();

            $rows = $rates->map(function (TaxRate $rate): array {
                return [
                    'rule' => 'Tax Rate #' . $rate->id . ' ' . $rate->name,
                    'source' => 'Location rule',
                    'rate' => $rate->formatted_rate,
                    'location' => $rate->location_description,
                    'status' => $this->taxRuleStatus($rate),
                    'priority' => (string) $rate->priority,
                    'default' => $this->yesNoLabel((bool) $rate->is_default),
                    'window' => $this->validityWindow($rate->valid_from, $rate->valid_until),
                ];
            })->all();

            if ($includeLegacy && count($rows) < $limit) {
                $legacyQuery = TaxType::query();

                if ($ruleId !== null && $ruleId > 0) {
                    $legacyQuery->where('id', $ruleId);
                }

                if ($searchTerm !== '') {
                    $legacyQuery->where(function ($builder) use ($searchTerm): void {
                        $builder->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('description', 'like', '%' . $searchTerm . '%');
                    });
                }

                $legacyRows = $legacyQuery
                    ->orderBy('id')
                    ->limit(max(0, $limit - count($rows)))
                    ->get()
                    ->map(function (TaxType $taxType): array {
                        return [
                            'rule' => 'Legacy Tax #' . $taxType->id . ' ' . $taxType->name,
                            'source' => 'Legacy fallback',
                            'rate' => $this->formatTaxRateValue((string) $taxType->type, $taxType->rate),
                            'location' => 'All locations',
                            'status' => 'Legacy',
                            'priority' => 'n/a',
                            'default' => 'n/a',
                            'window' => 'Legacy fallback',
                        ];
                    })
                    ->all();

                $rows = array_merge($rows, $legacyRows);
            }

            return '<h4>Tax rule lookup</h4><p><strong>Found:</strong> ' . count($rows) . ' tax rule(s)</p>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'rule' => 'Rule',
                        'source' => 'Source',
                        'rate' => 'Rate',
                        'location' => 'Location',
                        'status' => 'Status',
                        'priority' => 'Priority',
                        'default' => 'Default',
                        'window' => 'Validity',
                    ],
                    'No tax rules matched the requested filters.',
                    'tax-rule-lookup-results'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading tax rules: ' . $exception->getMessage());
        }
    }
}
