<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Carbon;
use Modules\Tax\Models\TaxRate;

abstract class AbstractTaxTool extends AbstractBillingTool
{
    protected string $domain = 'tax';

    protected array $requiredPermissions = ['view taxes'];

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

    protected function normalizeTaxType(mixed $value): string
    {
        $type = strtolower(trim((string) $value));

        return match ($type) {
            'percent' => 'percentage',
            'percentage', 'fixed' => $type,
            default => '',
        };
    }

    protected function formatTaxRateValue(?string $type, mixed $rate): string
    {
        $normalizedType = $this->normalizeTaxType($type);
        $numericRate = (float) $rate;

        if ($normalizedType === 'fixed') {
            return $this->formatMoney($numericRate);
        }

        $formatted = rtrim(rtrim(number_format($numericRate, 4, '.', ''), '0'), '.');

        return ($formatted === '' ? '0' : $formatted) . '%';
    }

    protected function taxRuleStatus(TaxRate $rate): string
    {
        if (! $rate->is_active) {
            return 'Inactive';
        }

        $now = now();

        if ($rate->valid_from instanceof Carbon && $now->lt($rate->valid_from)) {
            return 'Scheduled';
        }

        if ($rate->valid_until instanceof Carbon && $now->gt($rate->valid_until)) {
            return 'Expired';
        }

        return 'Current';
    }

    protected function validityWindow(?Carbon $from, ?Carbon $until): string
    {
        $fromText = $from?->format('Y-m-d') ?: 'Immediate';
        $untilText = $until?->format('Y-m-d') ?: 'Open-ended';

        return $fromText . ' -> ' . $untilText;
    }

    protected function describeLocation(array $location): string
    {
        $parts = [];

        foreach (['country_code', 'state_code', 'city', 'zip_code'] as $key) {
            $value = trim((string) ($location[$key] ?? ''));
            if ($value !== '') {
                $parts[] = strtoupper($key) === 'ZIP_CODE' ? ('ZIP ' . $value) : $value;
            }
        }

        return $parts === [] ? 'All locations' : implode(', ', $parts);
    }
}
