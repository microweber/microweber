<?php

declare(strict_types=1);

namespace Modules\Shipping\Tools;

use Modules\Billing\Tools\AbstractBillingTool;

use Illuminate\Support\Str;
use Modules\Shipping\Models\ShippingProvider;

abstract class AbstractShippingTool extends AbstractBillingTool
{
    protected string $domain = 'shipping';

    protected array $requiredPermissions = ['view shipping'];

    protected function normalizeProviderCode(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }

    protected function normalizeNullableBoolean(mixed $value): ?bool
    {
        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['1', 'true', 'yes', 'y'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'n'], true)) {
            return false;
        }

        return null;
    }

    protected function shippingProviderTitle(ShippingProvider $provider): string
    {
        $title = (string) ($provider->name ?: ('Shipping provider #' . $provider->id));
        $code = trim((string) $provider->provider);

        if ($code !== '') {
            $title .= '<br><small class="text-muted">' . e($code) . '</small>';
        }

        return $title;
    }

    protected function shippingSettings(ShippingProvider $provider): array
    {
        $settings = $provider->settings;

        if (is_array($settings)) {
            return $settings;
        }

        if (is_string($settings) && $settings !== '') {
            $decoded = json_decode($settings, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function countryZones(ShippingProvider $provider, bool $includeInactive = false): array
    {
        $settings = $this->shippingSettings($provider);
        $countries = $settings['countries'] ?? [];

        if (! is_array($countries)) {
            return [];
        }

        $rows = [];

        foreach ($countries as $zone) {
            if (! is_array($zone)) {
                continue;
            }

            $isActive = (bool) ($zone['is_active'] ?? false);
            if (! $includeInactive && ! $isActive) {
                continue;
            }

            $rows[] = [
                'country' => (string) ($zone['shipping_country'] ?? 'Unspecified'),
                'type' => (string) ($zone['shipping_type'] ?? 'fixed'),
                'shipping_cost' => $zone['shipping_cost'] ?? null,
                'shipping_cost_max' => $zone['shipping_cost_max'] ?? null,
                'shipping_cost_above' => $zone['shipping_cost_above'] ?? null,
                'shipping_price_per_weight' => $zone['shipping_price_per_weight'] ?? null,
                'shipping_price_per_size' => $zone['shipping_price_per_size'] ?? null,
                'shipping_price_per_item' => $zone['shipping_price_per_item'] ?? null,
                'is_active' => $isActive,
            ];
        }

        return $rows;
    }

    protected function shippingMethodSummary(ShippingProvider $provider): string
    {
        $settings = $this->shippingSettings($provider);

        return match ((string) $provider->provider) {
            'flat_rate' => 'Fixed rate ' . $this->formatMoney((float) ($settings['shipping_cost'] ?? 0)),
            'pickup_from_address' => 'Pickup from address / no shipping charge',
            'shipping_to_country' => count($this->countryZones($provider, true)) . ' country zone(s)',
            'weight_based' => $this->weightBasedSummary($settings),
            default => 'Custom driver ' . ((string) $provider->provider !== '' ? (string) $provider->provider : 'unknown'),
        };
    }

    protected function zoneTypeLabel(?string $type): string
    {
        return match ($type) {
            'fixed' => 'Fixed rate',
            'dimensions' => 'Dimensions based',
            'per_item' => 'Per item',
            default => Str::headline((string) $type),
        };
    }

    protected function yesNoLabel(bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    protected function optionalMoney(mixed $value): string
    {
        if ($value === null || $value === '' || ! is_numeric($value)) {
            return 'n/a';
        }

        return $this->formatMoney((float) $value);
    }

    protected function zoneCostSummary(array $zone): string
    {
        $bits = ['Base ' . $this->optionalMoney($zone['shipping_cost'] ?? null)];

        if (is_numeric($zone['shipping_price_per_weight'] ?? null)) {
            $bits[] = 'Per weight ' . $this->formatMoney((float) $zone['shipping_price_per_weight']);
        }

        if (is_numeric($zone['shipping_price_per_size'] ?? null)) {
            $bits[] = 'Per size ' . $this->formatMoney((float) $zone['shipping_price_per_size']);
        }

        if (is_numeric($zone['shipping_price_per_item'] ?? null)) {
            $bits[] = 'Per item ' . $this->formatMoney((float) $zone['shipping_price_per_item']);
        }

        if (is_numeric($zone['shipping_cost_max'] ?? null)) {
            $bits[] = 'Max ' . $this->formatMoney((float) $zone['shipping_cost_max']);
        }

        if (is_numeric($zone['shipping_cost_above'] ?? null)) {
            $bits[] = 'Cap after order total ' . $this->formatMoney((float) $zone['shipping_cost_above']);
        }

        return implode(' | ', $bits);
    }

    protected function weightBasedSummary(array $settings): string
    {
        $useTiers = (bool) ($settings['use_weight_tiers'] ?? false);

        if ($useTiers) {
            $tiers = collect($settings['weight_tiers'] ?? [])
                ->filter(fn ($tier): bool => is_array($tier) && (bool) ($tier['is_active'] ?? false))
                ->count();

            return $tiers . ' active weight tier(s)';
        }

        $baseCost = $this->optionalMoney($settings['base_shipping_cost'] ?? null);
        $perUnit = $this->optionalMoney($settings['cost_per_weight_unit'] ?? null);

        return 'Base ' . $baseCost . ' + ' . $perUnit . ' per weight unit';
    }
}
