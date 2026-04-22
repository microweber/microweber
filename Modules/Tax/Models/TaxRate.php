<?php

namespace Modules\Tax\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Country\Models\Country;
use Modules\Tax\Database\Factories\TaxRateFactory;

/**
 * Tax Rate Model
 *
 * Represents a location-based tax rate that can be applied to orders.
 * Supports country, state, ZIP code, and city-level tax rules.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $country_code ISO 2-letter country code
 * @property string|null $state_code State/province code
 * @property string|null $zip_code_pattern ZIP code pattern (supports wildcards)
 * @property string|null $city City name
 * @property string $type 'percentage' or 'fixed'
 * @property float $rate Tax rate value
 * @property bool $compound_tax Whether tax compounds with other taxes
 * @property int $priority Priority for sorting (higher = first)
 * @property bool $is_default Whether this is the default tax rate
 * @property bool $is_active Whether this tax rate is active
 * @property Carbon|null $valid_from Start date of validity
 * @property Carbon|null $valid_until End date of validity
 * @property array|null $applies_to_products Product IDs this applies to
 * @property array|null $applies_to_categories Category IDs this applies to
 * @property array|null $applies_to_customer_groups Customer group IDs
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TaxRate extends Model
{
    use CacheableQueryBuilderTrait;
    use HasFactory;

    protected $table = 'tax_rates';

    protected static function newFactory(): TaxRateFactory
    {
        return TaxRateFactory::new();
    }

    protected $fillable = [
        'name',
        'description',
        'country_code',
        'state_code',
        'zip_code_pattern',
        'city',
        'type',
        'rate',
        'compound_tax',
        'priority',
        'is_default',
        'is_active',
        'valid_from',
        'valid_until',
        'applies_to_products',
        'applies_to_categories',
        'applies_to_customer_groups',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'compound_tax' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applies_to_products' => 'array',
        'applies_to_categories' => 'array',
        'applies_to_customer_groups' => 'array',
    ];

    /**
     * Scope: Active tax rates
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Currently valid tax rates
     */
    public function scopeCurrentlyValid(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where(function (Builder $q) use ($now) {
            $q->whereNull('valid_from')
                ->orWhere('valid_from', '<=', $now);
        })->where(function (Builder $q) use ($now) {
            $q->whereNull('valid_until')
                ->orWhere('valid_until', '>=', $now);
        });
    }

    /**
     * Scope: For a specific country
     */
    public function scopeForCountry(Builder $query, ?string $countryCode): Builder
    {
        if (empty($countryCode)) {
            return $query->whereNull('country_code');
        }

        return $query->where(function (Builder $q) use ($countryCode) {
            $q->where('country_code', $countryCode)
                ->orWhereNull('country_code');
        });
    }

    /**
     * Scope: For a specific state
     */
    public function scopeForState(Builder $query, ?string $stateCode): Builder
    {
        if (empty($stateCode)) {
            return $query->whereNull('state_code');
        }

        return $query->where(function (Builder $q) use ($stateCode) {
            $q->where('state_code', $stateCode)
                ->orWhereNull('state_code');
        });
    }

    /**
     * Scope: For a specific city
     */
    public function scopeForCity(Builder $query, ?string $city): Builder
    {
        if (empty($city)) {
            return $query->whereNull('city');
        }

        return $query->where(function (Builder $q) use ($city) {
            $q->whereRaw('LOWER(city) = ?', [strtolower($city)])
                ->orWhereNull('city');
        });
    }

    /**
     * Scope: For a specific ZIP code
     */
    public function scopeForZipCode(Builder $query, ?string $zipCode): Builder
    {
        if (empty($zipCode)) {
            return $query->whereNull('zip_code_pattern');
        }

        // Match exact ZIP or wildcard patterns
        return $query->where(function (Builder $q) use ($zipCode) {
            $q->where(function (Builder $sub) use ($zipCode) {
                // Match exact ZIP code
                $sub->whereRaw('zip_code_pattern = ? COLLATE utf8mb4_bin', [$zipCode]);
            })->orWhere(function (Builder $sub) use ($zipCode) {
                // Match wildcard patterns (e.g., "123*" matches "12345")
                $sub->whereNotNull('zip_code_pattern')
                    ->whereRaw('? LIKE REPLACE(zip_code_pattern, "*", "%") COLLATE utf8mb4_bin', [$zipCode]);
            })->orWhereNull('zip_code_pattern');
        });
    }

    /**
     * Scope: Order by priority (highest first)
     */
    public function scopeByPriority(Builder $query): Builder
    {
        return $query->orderBy('priority', 'desc')
            ->orderBy('is_default', 'desc')
            ->orderBy('id', 'asc');
    }

    /**
     * Relationship: Country
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    /**
     * Check if this tax rate is currently valid
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return $this->is_active;
    }

    /**
     * Check if this tax rate applies to a specific location
     */
    public function appliesToLocation(array $location): bool
    {
        // Normalize location data
        $location = array_map(function($val) {
            return $val !== null ? strtoupper(trim($val)) : null;
        }, $location);

        // Check country
        if ($this->country_code !== null) {
            if (empty($location['country_code']) ||
                $location['country_code'] !== strtoupper($this->country_code)) {
                return false;
            }
        }

        // Check state
        if ($this->state_code !== null) {
            if (empty($location['state_code']) ||
                $location['state_code'] !== strtoupper($this->state_code)) {
                return false;
            }
        }

        // Check city
        if ($this->city !== null) {
            if (empty($location['city']) ||
                strtoupper($location['city']) !== strtoupper($this->city)) {
                return false;
            }
        }

        // Check ZIP code pattern
        if ($this->zip_code_pattern !== null) {
            if (empty($location['zip_code'])) {
                return false;
            }

            // First replace wildcards, then quote the pattern
            $patternWithWildcards = str_replace('*', '___WILDCARD___', $this->zip_code_pattern);
            $quotedPattern = preg_quote($patternWithWildcards, '/');
            $zipPattern = str_replace('___WILDCARD___', '.*', $quotedPattern);
            if (!preg_match('/^' . $zipPattern . '$/i', $location['zip_code'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate tax amount
     */
    public function calculate(float $amount): float
    {
        if ($this->type === 'fixed') {
            return $this->rate;
        }

        return $amount * ($this->rate / 100);
    }

    /**
     * Get formatted rate display
     */
    public function getFormattedRateAttribute(): string
    {
        if ($this->type === 'fixed') {
            return currency_format($this->rate);
        }

        // Format percentage without extra decimals
        $rate = floatval($this->rate);
        if ($rate == intval($rate)) {
            return intval($rate) . '%';
        }
        return rtrim(rtrim(number_format($rate, 4, '.', ''), '0'), '.') . '%';
    }

    /**
     * Get location description
     */
    public function getLocationDescriptionAttribute(): string
    {
        $parts = [];

        if ($this->country_code) {
            $parts[] = $this->country?->name ?? $this->country_code;
            // Also include the code in parentheses for clarity
            if ($this->country && $this->country->name !== $this->country_code) {
                $parts[0] .= ' (' . $this->country_code . ')';
            } else {
                $parts[0] = $this->country_code;
            }
        }

        if ($this->state_code) {
            $parts[] = $this->state_code;
        }

        if ($this->city) {
            $parts[] = $this->city;
        }

        if ($this->zip_code_pattern) {
            $parts[] = 'ZIP: ' . $this->zip_code_pattern;
        }

        return empty($parts) ? 'All locations' : implode(', ', $parts);
    }
}
