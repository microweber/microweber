<?php

namespace Modules\Shipping\Drivers;

use Filament\Forms;

/**
 * Weight Based Shipping Driver
 *
 * Calculates shipping costs based on cart weight with support for:
 * - Fixed base cost
 * - Cost per weight unit (kg, lb, etc.)
 * - Weight range tiers
 * - Maximum cost cap
 * - Free shipping threshold
 *
 * @package Modules\Shipping\Drivers
 */
class WeightBased extends AbstractShippingMethod
{
    public string $provider = 'weight_based';

    /**
     * Get the driver title
     *
     * @return string
     */
    public function title(): string
    {
        return 'Weight Based Shipping';
    }

    /**
     * Calculate shipping cost based on cart weight
     *
     * @param array $data Optional data for calculation
     * @return float|int
     */
    public function getShippingCost($data = []): float|int
    {
        $model = $this->getModel();
        if (!$model) {
            return 0;
        }

        $settings = $model->settings ?? [];

        // Get cart weight
        $cartWeight = $this->getCartWeight();
        if ($cartWeight <= 0) {
            return 0;
        }

        // Check for free shipping threshold
        $freeShippingThreshold = $settings['free_shipping_threshold'] ?? null;
        if ($freeShippingThreshold && $cartWeight >= floatval($freeShippingThreshold)) {
            session(['shipping_cost' => 0]);
            return 0;
        }

        // Calculate base cost
        $baseCost = isset($settings['base_shipping_cost']) ? floatval($settings['base_shipping_cost']) : 0;

        // Calculate weight-based cost
        $weightCost = 0;

        // Check if using weight tiers
        if (!empty($settings['weight_tiers'])) {
            $weightCost = $this->calculateTieredCost($cartWeight, $settings['weight_tiers']);
        } else {
            // Use per-weight-unit pricing
            $costPerWeightUnit = isset($settings['cost_per_weight_unit']) ? floatval($settings['cost_per_weight_unit']) : 0;
            $weightCost = $costPerWeightUnit * $cartWeight;
        }

        // Calculate total
        $totalCost = $baseCost + $weightCost;

        // Apply maximum cost cap if set
        $maxCost = $settings['max_shipping_cost'] ?? null;
        if ($maxCost && $totalCost > floatval($maxCost)) {
            $totalCost = floatval($maxCost);
        }

        // Round to 2 decimal places
        $totalCost = round($totalCost, 2);

        session(['shipping_cost' => $totalCost]);

        return $totalCost;
    }

    /**
     * Get total weight of items in cart
     *
     * @return float
     */
    protected function getCartWeight(): float
    {
        $totalWeight = 0;
        $cartItemsCount = app()->cart_manager->sum(false);

        if ($cartItemsCount > 0) {
            $cartItems = app()->cart_manager->get_cart();
            if (!empty($cartItems)) {
                foreach ($cartItems as $item) {
                    $contentData = $item['content_data'] ?? [];
                    $qty = intval($item['qty'] ?? 1);

                    // Check if item has free shipping
                    if (isset($contentData['is_free_shipping']) && $contentData['is_free_shipping'] === 'y') {
                        continue;
                    }

                    // Get item weight
                    if (isset($contentData['shipping_weight']) && $contentData['shipping_weight'] !== '') {
                        $itemWeight = floatval($contentData['shipping_weight']) * $qty;
                        $totalWeight += $itemWeight;
                    }
                }
            }
        }

        return $totalWeight;
    }

    /**
     * Calculate cost using weight tiers
     *
     * @param float $weight Total cart weight
     * @param array $tiers Array of weight tier configurations
     * @return float
     */
    protected function calculateTieredCost(float $weight, array $tiers): float
    {
        // Sort tiers by min_weight
        usort($tiers, function ($a, $b) {
            $aMin = floatval($a['min_weight'] ?? 0);
            $bMin = floatval($b['min_weight'] ?? 0);
            return $aMin <=> $bMin;
        });

        // Find applicable tier
        foreach ($tiers as $tier) {
            if (!isset($tier['is_active']) || !$tier['is_active']) {
                continue;
            }

            $minWeight = floatval($tier['min_weight'] ?? 0);
            $maxWeight = isset($tier['max_weight']) ? floatval($tier['max_weight']) : null;

            if ($weight >= $minWeight && ($maxWeight === null || $weight <= $maxWeight)) {
                return floatval($tier['cost'] ?? 0);
            }
        }

        // If no tier matched, use the last active tier's cost
        $lastActiveTier = null;
        foreach (array_reverse($tiers) as $tier) {
            if (isset($tier['is_active']) && $tier['is_active']) {
                $lastActiveTier = $tier;
                break;
            }
        }

        return $lastActiveTier ? floatval($lastActiveTier['cost'] ?? 0) : 0;
    }

    /**
     * Get checkout form for this shipping method
     *
     * @return array
     */
    public function getForm(): array
    {
        $model = $this->getModel();
        $settings = $model ? ($model->settings ?? []) : [];
        $instructions = $settings['shipping_instructions'] ?? 'Shipping cost will be calculated based on the total weight of your order.';

        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, mixed $state = null) use ($instructions) {
                    $cartWeight = $this->getCartWeight();
                    $cost = 0;

                    if ($cartWeight > 0) {
                        $cost = $this->getShippingCost();
                        $instructions = sprintf(
                            'Total cart weight: %.2f kg. Shipping cost: %s',
                            $cartWeight,
                            app()->shop_manager->currency_format($cost)
                        );
                    }

                    return [
                        Forms\Components\Placeholder::make('')
                            ->reactive()
                            ->live()
                            ->content($instructions)
                    ];
                })
        ];
    }

    /**
     * Get settings form for admin configuration
     *
     * @return array
     */
    public function getSettingsForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, mixed $state = null) {
                    $useTiers = $get('settings.use_weight_tiers') ?? false;

                    $schema = [
                        Forms\Components\Toggle::make('use_weight_tiers')
                            ->label('Use Weight Tiers')
                            ->reactive()
                            ->default(false)
                            ->helperText('Enable to set specific costs for weight ranges instead of per-unit pricing'),

                        // Show base cost and per-unit pricing when NOT using tiers
                        Forms\Components\TextInput::make('base_shipping_cost')
                            ->label('Base Shipping Cost')
                            ->numeric()
                            ->default(0)
                            ->helperText('Fixed base cost applied to all orders')
                            ->visible(fn(Forms\Get $get) => !($get('use_weight_tiers')))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('cost_per_weight_unit')
                            ->label('Cost per Weight Unit')
                            ->numeric()
                            ->default(0)
                            ->helperText('Cost per kg/lb added to base cost')
                            ->visible(fn(Forms\Get $get) => !($get('use_weight_tiers')))
                            ->columnSpanFull(),

                        // Show weight tiers when using tiers
                        Forms\Components\Repeater::make('weight_tiers')
                            ->label('Weight Tiers')
                            ->visible(fn(Forms\Get $get) => $get('use_weight_tiers'))
                            ->schema([
                                Forms\Components\TextInput::make('min_weight')
                                    ->label('Min Weight (kg)')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('max_weight')
                                    ->label('Max Weight (kg)')
                                    ->numeric()
                                    ->helperText('Leave empty for no maximum'),

                                Forms\Components\TextInput::make('cost')
                                    ->label('Shipping Cost')
                                    ->numeric()
                                    ->required()
                                    ->default(0),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ])
                            ->defaultItems(3)
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull(),

                        // Common settings
                        Forms\Components\TextInput::make('max_shipping_cost')
                            ->label('Maximum Shipping Cost')
                            ->numeric()
                            ->default(0)
                            ->helperText('Maximum shipping cost regardless of weight (0 = no limit)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('free_shipping_threshold')
                            ->label('Free Shipping Weight Threshold')
                            ->numeric()
                            ->default(0)
                            ->helperText('Orders with weight equal to or above this value get free shipping (0 = disabled)')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('shipping_instructions')
                            ->label('Shipping Instructions')
                            ->columnSpanFull()
                            ->default('Shipping cost is calculated based on the total weight of your order.'),
                    ];

                    return $schema;
                })
                ->visible(fn(Forms\Get $get) => $get('provider') === 'weight_based')
                ->columns(1)
        ];
    }
}
