<?php

declare(strict_types=1);

namespace Modules\Tax\Tools;

use Modules\Tax\Services\TaxCalculator;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class TaxPreviewTool extends AbstractTaxTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'tax_preview',
            'Preview tax calculations for a subtotal and location using the current Microweber tax rules.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'amount', type: PropertyType::STRING, description: 'Subtotal amount to preview, for example 100 or 149.95.', required: true),
            new ToolProperty(name: 'country_code', type: PropertyType::STRING, description: 'Optional 2-letter country code such as US or GB.', required: false),
            new ToolProperty(name: 'state_code', type: PropertyType::STRING, description: 'Optional state or province code.', required: false),
            new ToolProperty(name: 'city', type: PropertyType::STRING, description: 'Optional city name.', required: false),
            new ToolProperty(name: 'zip_code', type: PropertyType::STRING, description: 'Optional ZIP or postal code.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $amount = (float) ($args['amount'] ?? 0);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to preview taxes.');
        }

        if ($amount <= 0) {
            return $this->handleError('Provide a positive amount to preview taxes.');
        }

        try {
            /** @var TaxCalculator $calculator */
            $calculator = app(TaxCalculator::class);
            $location = $calculator->validateLocation([
                'country_code' => $args['country_code'] ?? null,
                'state_code' => $args['state_code'] ?? null,
                'city' => $args['city'] ?? null,
                'zip_code' => $args['zip_code'] ?? null,
            ]);

            $result = $calculator->calculate($amount, $location);
            $breakdown = collect($result['breakdown'] ?? []);

            $summary = $this->formatAsHtmlTable(
                [[
                    'subtotal' => $this->formatMoney($amount),
                    'tax' => $this->formatMoney((float) ($result['amount'] ?? 0)),
                    'total' => $this->formatMoney($amount + (float) ($result['amount'] ?? 0)),
                    'matched_rules' => (string) $breakdown->count(),
                    'taxes_enabled' => $this->yesNoLabel($calculator->isEnabled()),
                    'location' => $this->describeLocation($location),
                ]],
                [
                    'subtotal' => 'Subtotal',
                    'tax' => 'Tax amount',
                    'total' => 'Total with tax',
                    'matched_rules' => 'Matched rules',
                    'taxes_enabled' => 'Taxes enabled',
                    'location' => 'Location',
                ],
                '',
                'tax-preview-summary'
            );

            $rows = $breakdown->map(fn (array $row): array => [
                'name' => (string) ($row['name'] ?? 'Tax rule'),
                'type' => $this->normalizeTaxType($row['type'] ?? ''),
                'rate' => $this->formatTaxRateValue((string) ($row['type'] ?? ''), $row['rate'] ?? 0),
                'amount' => $this->formatMoney((float) ($row['amount'] ?? 0)),
                'taxable_amount' => $this->formatMoney((float) ($row['taxable_amount'] ?? 0)),
                'compound' => $this->yesNoLabel((bool) ($row['compound'] ?? false)),
                'location' => (string) ($row['location'] ?? 'All locations'),
            ])->all();

            return '<h4>Tax preview</h4>'
                . $summary
                . '<h4>Applied tax rules</h4>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'name' => 'Rule',
                        'type' => 'Type',
                        'rate' => 'Rate',
                        'amount' => 'Tax amount',
                        'taxable_amount' => 'Taxable amount',
                        'compound' => 'Compound',
                        'location' => 'Location',
                    ],
                    'No tax rules matched this preview request.',
                    'tax-preview-breakdown'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error previewing taxes: ' . $exception->getMessage());
        }
    }
}
