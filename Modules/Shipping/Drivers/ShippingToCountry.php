<?php

namespace Modules\Shipping\Drivers;

use Filament\Forms;

class ShippingToCountry extends AbstractShippingMethod
{
    public string $provider = 'shipping_to_country';

    public function title(): string
    {
        return 'Shipping to Country';
    }

    public function getShippingCost($data = []): float|int
    {
        $model = $this->getModel();
        if (!$model) {
            return 0;
        }
        $shipping_country = $data['shipping_country'] ?? null;
        //   $shipping_country = session('shipping_country');
        if (!$shipping_country && auth()->check()) {
            $shipping_address = app()->user_manager->get_shipping_address();
            if ($shipping_address && isset($shipping_address['country'])) {
                $shipping_country = $shipping_address['country'];
            }
        }
        if (!$shipping_country) {
            $shipping_country = checkout_get_user_info('country');
        }


        $defined_cost = 0;
        $is_worldwide = false;

        $countries = $this->getModel()->settings['countries'] ?? [];
        $shipping_country_record = null;

        // Get shipping country record
        if (!$shipping_country) {
            $shipping_country_record = collect($countries)->where('is_active', true)
                ->where('shipping_country', 'Worldwide')
                ->first();
            if ($shipping_country_record) {
                $is_worldwide = true;
            }
        } else {
            $shipping_country_record = collect($countries)->where('is_active', true)
                ->where('shipping_country', $shipping_country)
                ->first();
            if (!$shipping_country_record) {
                $shipping_country_record = collect($countries)->where('is_active', true)
                    ->where('shipping_country', 'Worldwide')
                    ->first();
                if ($shipping_country_record) {
                    $is_worldwide = true;
                }
            }
        }

        if (!$shipping_country_record) {
            $shipping_country_record = collect($countries)->where('is_active', true)
                ->first();
        }

        if (!$shipping_country_record) {
            session(['shipping_country' => 'none']);
            session(['shipping_cost' => 0]);
            return 0;
        }

        // Calculate shipping cost based on type
        if ($shipping_country_record['shipping_type'] == 'fixed') {
            $defined_cost = floatval($shipping_country_record['shipping_cost']);
        } elseif ($shipping_country_record['shipping_type'] == 'dimensions') {
            $total_shipping_weight = 0;
            $total_shipping_volume = 0;

            $items_cart_count = app()->shop_manager->cart_sum(false);
            if ($items_cart_count > 0) {
                $cart_items = app()->shop_manager->get_cart();
                if (!empty($cart_items)) {
                    foreach ($cart_items as $item) {
                        $content_data = $item['content_data'] ?? [];

                        if (!isset($content_data['is_free_shipping']) || $content_data['is_free_shipping'] != 'y') {
                            if (isset($content_data['additional_shipping_cost']) && intval($content_data['additional_shipping_cost']) > 0) {
                                $volume = floatval($content_data['additional_shipping_cost']) * intval($item['qty']);
                                $defined_cost += $volume;
                            } else {
                                if (isset($content_data['shipping_weight']) && $content_data['shipping_weight'] != '') {
                                    $weight = floatval($content_data['shipping_weight']) * intval($item['qty']);
                                    $total_shipping_weight += $weight;
                                }

                                if (isset($content_data['shipping_width']) && isset($content_data['shipping_height']) && isset($content_data['shipping_depth'])) {
                                    $volume = floatval($content_data['shipping_width']) *
                                        floatval($content_data['shipping_height']) *
                                        floatval($content_data['shipping_depth']) *
                                        intval($item['qty']);
                                    $total_shipping_volume += $volume;
                                }
                            }
                        }
                    }
                }
            }

            // Add weight based cost
            if (isset($shipping_country_record['shipping_price_per_weight']) && $total_shipping_weight > 1) {
                $weight_cost = floatval($shipping_country_record['shipping_price_per_weight']);
                $defined_cost += $weight_cost * ceil($total_shipping_weight - 1);
            }

            // Add size based cost
            if (isset($shipping_country_record['shipping_price_per_size'])) {
                $size_cost = floatval($shipping_country_record['shipping_price_per_size']);
                $defined_cost += $size_cost * $total_shipping_volume;
            }
        } elseif ($shipping_country_record['shipping_type'] == 'per_item') {
            if (isset($shipping_country_record['shipping_price_per_item'])) {
                $items_cart_count = app()->shop_manager->cart_sum(false);
                if ($items_cart_count > 0) {
                    $cart_items = app()->shop_manager->get_cart();
                    if (!empty($cart_items)) {
                        foreach ($cart_items as $item) {
                            $content_data = $item['content_data'] ?? [];
                            if (!isset($content_data['is_free_shipping']) || $content_data['is_free_shipping'] != 'y') {
                                if (isset($content_data['additional_shipping_cost']) && intval($content_data['additional_shipping_cost']) > 0) {
                                    $volume = floatval($content_data['additional_shipping_cost']) * intval($item['qty']);
                                    $defined_cost += $volume;
                                } else {
                                    $defined_cost += floatval($shipping_country_record['shipping_price_per_item']) * intval($item['qty']);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Check for cost threshold
        $items_cart_amount = app()->shop_manager->cart_sum();
        if (isset($shipping_country_record['shipping_cost_above']) && isset($shipping_country_record['shipping_cost_max'])) {
            if ($items_cart_amount >= floatval($shipping_country_record['shipping_cost_above'])) {
                $defined_cost = floatval($shipping_country_record['shipping_cost_max']);
            }
        }

        // Add base shipping cost for non-fixed types
        if ($shipping_country_record['shipping_type'] != 'fixed' && isset($shipping_country_record['shipping_cost'])) {
            $defined_cost += floatval($shipping_country_record['shipping_cost']);
        }

        session(['shipping_cost' => $defined_cost]);

        return $defined_cost;
    }

    public function getForm(): array
    {


        return [
            Forms\Components\Section::make()
                ->live()
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {

                    $instructions = $this->getModel()->settings['shipping_instructions'] ?? 'Please select your shipping country to calculate shipping costs.';

                    $shipping_country = checkout_get_user_info('country');

                    $shipping_country = $get('country') ?? $shipping_country;

                    $cost = 0;
                    $selected_country = null;
                    if ($shipping_country) {
                        $cost = $this->getShippingCost(['shipping_country' => $shipping_country]);

                        $instructions = 'Shipping to ' . $shipping_country . ' costs ' . mw()->shop_manager->currency_format($cost);

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

    public function getSettingsForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->schema([
                    Forms\Components\Repeater::make('countries')
                        ->label('Shipping Countries')
                        ->schema([
                            Forms\Components\Select::make('shipping_country')
                                ->label('Country')
                                ->options(function () {
                                    $countries = ['Worldwide' => 'Worldwide'];
                                    $all_countries = mw()->forms_manager->countries_list();
                                    foreach ($all_countries as $country) {
                                        $countries[$country] = $country;
                                    }
                                    return $countries;
                                })
                                ->required(),

                            Forms\Components\Select::make('shipping_type')
                                ->label('Shipping Type')
                                ->options([
                                    'fixed' => 'Fixed Rate',
                                    'dimensions' => 'Based on Dimensions',
                                    'per_item' => 'Per Item'
                                ])
                                ->required()
                                ->reactive(),

                            Forms\Components\TextInput::make('shipping_cost')
                                ->label('Base Shipping Cost')
                                ->numeric()
                                ->default(0),

                            Forms\Components\TextInput::make('shipping_cost_max')
                                ->label('Maximum Shipping Cost')
                                ->numeric()
                                ->visible(fn(Forms\Get $get) => $get('shipping_type') != 'fixed'),

                            Forms\Components\TextInput::make('shipping_cost_above')
                                ->label('Apply Max Cost Above Order Total')
                                ->numeric()
                                ->visible(fn(Forms\Get $get) => $get('shipping_type') != 'fixed'),

                            Forms\Components\TextInput::make('shipping_price_per_weight')
                                ->label('Cost Per Weight Unit')
                                ->numeric()
                                ->visible(fn(Forms\Get $get) => $get('shipping_type') == 'dimensions'),

                            Forms\Components\TextInput::make('shipping_price_per_size')
                                ->label('Cost Per Size Unit')
                                ->numeric()
                                ->visible(fn(Forms\Get $get) => $get('shipping_type') == 'dimensions'),

                            Forms\Components\TextInput::make('shipping_price_per_item')
                                ->label('Cost Per Item')
                                ->numeric()
                                ->visible(fn(Forms\Get $get) => $get('shipping_type') == 'per_item'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ])
                        ->defaultItems(1)
                        ->reorderable()
                        ->collapsible()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('shipping_instructions')
                        ->label('Shipping Instructions')
                        ->columnSpanFull()
                ])
                ->columns(2)
        ];
    }
}
