<?php

namespace Modules\Tax\Services;

use Illuminate\Support\Facades\Log;
use Modules\Tax\Models\TaxRate;
use Modules\Tax\Models\TaxType;

/**
 * Tax Calculator Service
 *
 * Calculates taxes based on location and product information.
 * Supports location-based tax rules, multiple tax rates, and compound taxes.
 */
class TaxCalculator
{
    /**
     * @var array|null Cached tax rates for the current request
     */
    protected ?array $cachedRates = null;

    /**
     * Calculate taxes for an order/cart
     *
     * @param float $amount The subtotal amount to calculate tax on
     * @param array $location Location data with keys: country_code, state_code, city, zip_code
     * @param array $context Additional context (products, categories, customer_group, etc.)
     * @return array Tax calculation result with 'amount' and 'breakdown'
     */
    public function calculate(float $amount, array $location = [], array $context = []): array
    {
        if ($amount <= 0) {
            return [
                'amount' => 0.00,
                'breakdown' => [],
            ];
        }

        // Get applicable tax rates
        $taxRates = $this->getApplicableTaxRates($location, $context);

        // If no tax rates found, check if we should fall back to legacy TaxTypes
        // Only fall back if there are no TaxRates at all in the system
        if (empty($taxRates)) {
            $hasTaxRates = TaxRate::query()->exists();
            if (!$hasTaxRates) {
                return $this->calculateFromTaxTypes($amount);
            }
            // TaxRates exist but none matched - return zero tax
            return [
                'amount' => 0.00,
                'breakdown' => [],
            ];
        }

        // Calculate taxes - apply non-compound taxes first, then compound taxes
        $breakdown = [];
        $totalTax = 0.00;
        $runningTotal = $amount;

        // First pass: Apply non-compound taxes
        foreach ($taxRates as $taxRate) {
            if ($taxRate->compound_tax) {
                continue;
            }

            // Calculate tax for this rate
            $taxAmount = $taxRate->calculate($runningTotal);

            if ($taxAmount > 0) {
                $breakdown[] = [
                    'rate_id' => $taxRate->id,
                    'name' => $taxRate->name,
                    'rate' => $taxRate->rate,
                    'type' => $taxRate->type,
                    'amount' => $taxAmount,
                    'taxable_amount' => $runningTotal,
                    'compound' => false,
                    'location' => $taxRate->location_description,
                ];

                $totalTax += $taxAmount;
            }
        }

        // Add non-compound taxes to running total for compound calculations
        $runningTotal += $totalTax;

        // Second pass: Apply compound taxes
        foreach ($taxRates as $taxRate) {
            if (!$taxRate->compound_tax) {
                continue;
            }

            // Calculate tax for this rate on the updated running total
            $taxAmount = $taxRate->calculate($runningTotal);

            if ($taxAmount > 0) {
                $breakdown[] = [
                    'rate_id' => $taxRate->id,
                    'name' => $taxRate->name,
                    'rate' => $taxRate->rate,
                    'type' => $taxRate->type,
                    'amount' => $taxAmount,
                    'taxable_amount' => $runningTotal,
                    'compound' => true,
                    'location' => $taxRate->location_description,
                ];

                $totalTax += $taxAmount;
                $runningTotal += $taxAmount;
            }
        }

        // Round to 2 decimal places
        $totalTax = round($totalTax, 2);

        return [
            'amount' => $totalTax,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate tax using legacy TaxTypes (backward compatibility)
     *
     * @param float $amount The subtotal amount
     * @return array Tax calculation result
     */
    protected function calculateFromTaxTypes(float $amount): array
    {
        $taxTypes = TaxType::all();
        $breakdown = [];
        $totalTax = 0.00;

        foreach ($taxTypes as $taxType) {
            $taxAmount = 0;

            if ($taxType->type === 'fixed') {
                $taxAmount = floatval($taxType->rate);
            } elseif ($taxType->type === 'percent') {
                $taxAmount = $amount * (floatval($taxType->rate) / 100);
            }

            if ($taxAmount > 0) {
                $breakdown[] = [
                    'rate_id' => $taxType->id,
                    'name' => $taxType->name,
                    'rate' => $taxType->rate,
                    'type' => $taxType->type === 'percent' ? 'percentage' : 'fixed',
                    'amount' => $taxAmount,
                    'taxable_amount' => $amount,
                    'compound' => $taxType->compound_tax ?? false,
                    'location' => 'All locations',
                ];

                $totalTax += $taxAmount;
            }
        }

        return [
            'amount' => round($totalTax, 2),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate tax amount only (simplified)
     *
     * @param float $amount The subtotal amount
     * @param array $location Location data
     * @param array $context Additional context
     * @return float The calculated tax amount
     */
    public function calculateAmount(float $amount, array $location = [], array $context = []): float
    {
        $result = $this->calculate($amount, $location, $context);

        return $result['amount'];
    }

    /**
     * Get applicable tax rates for a location
     *
     * @param array $location Location data
     * @param array $context Additional context
     * @return array Array of TaxRate models
     */
    public function getApplicableTaxRates(array $location = [], array $context = []): array
    {
        $cacheKey = $this->getCacheKey($location, $context);

        if (isset($this->cachedRates[$cacheKey])) {
            return $this->cachedRates[$cacheKey];
        }

        $query = TaxRate::query()
            ->active()
            ->currentlyValid();

        // Apply location filters
        $this->applyLocationFilters($query, $location);

        // Apply product/category filters if specified
        $this->applyProductFilters($query, $context);

        // Order by priority
        $query->byPriority();

        $rates = $query->get()->all();

        // Filter rates by specificity (most specific first)
        $rates = $this->filterBySpecificity($rates, $location);

        // Cache the results
        $this->cachedRates[$cacheKey] = $rates;

        return $rates;
    }

    /**
     * Check if taxes are enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) get_option('enable_taxes', 'shop');
    }

    /**
     * Enable taxes
     */
    public function enable(): void
    {
        save_option('enable_taxes', '1', 'shop');
    }

    /**
     * Disable taxes
     */
    public function disable(): void
    {
        save_option('enable_taxes', '0', 'shop');
    }

    /**
     * Validate location data
     *
     * @param array $location
     * @return array Validated and normalized location
     */
    public function validateLocation(array $location): array
    {
        $validated = [
            'country_code' => null,
            'state_code' => null,
            'city' => null,
            'zip_code' => null,
        ];

        if (!empty($location['country_code'])) {
            $validated['country_code'] = strtoupper(trim($location['country_code']));
        }

        if (!empty($location['state_code'])) {
            $validated['state_code'] = strtoupper(trim($location['state_code']));
        }

        if (!empty($location['city'])) {
            $validated['city'] = trim($location['city']);
        }

        if (!empty($location['zip_code'])) {
            // Normalize ZIP code (remove spaces, dashes for pattern matching)
            $validated['zip_code'] = preg_replace('/[\s\-]/', '', trim($location['zip_code']));
        }

        // Also support alternative field names
        if (empty($validated['country_code']) && !empty($location['country'])) {
            $validated['country_code'] = strtoupper(trim($location['country']));
        }

        if (empty($validated['state_code']) && !empty($location['state'])) {
            $validated['state_code'] = strtoupper(trim($location['state']));
        }

        return $validated;
    }

    /**
     * Get tax summary for display
     *
     * @param float $amount
     * @param array $location
     * @param array $context
     * @return array
     */
    public function getTaxSummary(float $amount, array $location = [], array $context = []): array
    {
        $calculation = $this->calculate($amount, $location, $context);

        return [
            'subtotal' => $amount,
            'tax_amount' => $calculation['amount'],
            'total' => $amount + $calculation['amount'],
            'tax_rates' => array_map(function ($item) {
                return [
                    'name' => $item['name'],
                    'rate' => $item['formatted_rate'] ??
                        ($item['type'] === 'percentage'
                            ? $item['rate'] . '%'
                            : currency_format($item['rate'])),
                    'amount' => $item['amount'],
                ];
            }, $calculation['breakdown']),
        ];
    }

    /**
     * Clear cached rates
     */
    public function clearCache(): void
    {
        $this->cachedRates = null;
    }

    /**
     * Apply location filters to query
     */
    protected function applyLocationFilters($query, array $location): void
    {
        $location = $this->validateLocation($location);

        // Build a query that matches any of the location criteria
        // The more specific the match, the higher priority
        $query->where(function ($q) use ($location) {
            // Country-specific rates
            if ($location['country_code']) {
                $q->orWhere(function ($sub) use ($location) {
                    $sub->where('country_code', $location['country_code']);
                });
            }

            // State-specific rates
            if ($location['state_code']) {
                $q->orWhere(function ($sub) use ($location) {
                    $sub->where('country_code', $location['country_code'] ?? null)
                        ->where('state_code', $location['state_code']);
                });
            }

            // City-specific rates
            if ($location['city']) {
                $q->orWhere(function ($sub) use ($location) {
                    $sub->where('country_code', $location['country_code'] ?? null)
                        ->where('city', $location['city']);
                });
            }

            // ZIP code specific rates
            if ($location['zip_code']) {
                $q->orWhere(function ($sub) use ($location) {
                    $sub->whereRaw('zip_code_pattern IS NOT NULL')
                        ->whereRaw('? LIKE REPLACE(zip_code_pattern, "*", "%")', [$location['zip_code']]);
                });
            }

            // Global/default rates (no location restrictions)
            $q->orWhere(function ($sub) {
                $sub->whereNull('country_code')
                    ->whereNull('state_code')
                    ->whereNull('city')
                    ->whereNull('zip_code_pattern');
            });
        });
    }

    /**
     * Apply product and category filters
     */
    protected function applyProductFilters($query, array $context): void
    {
        // Filter by customer group if specified
        if (!empty($context['customer_group_id'])) {
            $query->where(function ($q) use ($context) {
                $q->whereJsonDoesntContain('applies_to_customer_groups', $context['customer_group_id'])
                    ->orWhereNull('applies_to_customer_groups');
            });
        }

        // Note: Product and category filtering is done post-query
        // because we need to match specific products vs generic rates
    }

    /**
     * Filter rates by specificity - most specific matches win
     */
    protected function filterBySpecificity(array $rates, array $location): array
    {
        $location = $this->validateLocation($location);
        $filtered = [];

        foreach ($rates as $rate) {
            $specificity = $this->calculateSpecificity($rate, $location);

            if ($specificity >= 0) {
                $filtered[] = [
                    'rate' => $rate,
                    'specificity' => $specificity,
                ];
            }
        }

        // Sort by specificity (higher = more specific)
        usort($filtered, function ($a, $b) {
            return $b['specificity'] <=> $a['specificity'];
        });

        // Return just the rates
        return array_map(function ($item) {
            return $item['rate'];
        }, $filtered);
    }

    /**
     * Calculate specificity score for a tax rate
     * Higher score = more specific match
     * -1 = doesn't match
     */
    protected function calculateSpecificity(TaxRate $rate, array $location): int
    {
        $score = 0;

        // Check if rate applies to this location
        if (!$rate->appliesToLocation($location)) {
            return -1;
        }

        // ZIP code pattern is most specific
        if ($rate->zip_code_pattern !== null) {
            $score += 100;
        }

        // City level
        if ($rate->city !== null) {
            $score += 50;
        }

        // State level
        if ($rate->state_code !== null) {
            $score += 30;
        }

        // Country level
        if ($rate->country_code !== null) {
            $score += 20;
        }

        // Priority bonus
        $score += $rate->priority;

        return $score;
    }

    /**
     * Generate cache key for location/context
     */
    protected function getCacheKey(array $location, array $context): string
    {
        $location = $this->validateLocation($location);

        $key = sprintf(
            '%s:%s:%s:%s',
            $location['country_code'] ?? 'all',
            $location['state_code'] ?? 'all',
            $location['city'] ?? 'all',
            $location['zip_code'] ?? 'all'
        );

        // Add context hash
        $contextKey = md5(serialize($context));

        return $key . ':' . $contextKey;
    }
}
