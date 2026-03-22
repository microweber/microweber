<?php

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ExchangeRate Model
 *
 * Manages currency exchange rates between different currencies.
 * Supports automatic rate fetching and manual rate management.
 */
class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchange_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'inverse_rate',
        'source',
        'last_updated',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:8',
        'inverse_rate' => 'decimal:8',
        'last_updated' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active exchange rates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by source currency.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $currency
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCurrency($query, string $currency)
    {
        return $query->where('from_currency', strtoupper($currency));
    }

    /**
     * Scope a query to filter by target currency.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $currency
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToCurrency($query, string $currency)
    {
        return $query->where('to_currency', strtoupper($currency));
    }

    /**
     * Get the source currency relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency', 'code');
    }

    /**
     * Get the target currency relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency', 'code');
    }

    /**
     * Get exchange rate for a currency pair.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return ExchangeRate|null
     */
    public static function getRate(string $fromCurrency, string $toCurrency): ?self
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return null;
        }

        return self::active()
            ->fromCurrency($fromCurrency)
            ->toCurrency($toCurrency)
            ->first();
    }

    /**
     * Update or create an exchange rate.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $rate
     * @param string $source
     * @return self
     */
    public static function updateOrCreateRate(
        string $fromCurrency,
        string $toCurrency,
        float $rate,
        string $source = 'manual'
    ): self {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        $rate = max(0, $rate);
        $inverseRate = $rate > 0 ? 1 / $rate : 0;

        return self::updateOrCreate(
            [
                'from_currency' => $fromCurrency,
                'to_currency' => $toCurrency,
            ],
            [
                'rate' => $rate,
                'inverse_rate' => $inverseRate,
                'source' => $source,
                'last_updated' => now(),
                'is_active' => true,
            ]
        );
    }

    /**
     * Convert an amount from one currency to another using this rate.
     *
     * @param float $amount
     * @return float
     */
    public function convert(float $amount): float
    {
        return $amount * (float) $this->rate;
    }

    /**
     * Convert an amount back to the original currency.
     *
     * @param float $amount
     * @return float
     */
    public function convertBack(float $amount): float
    {
        if ($this->inverse_rate && $this->inverse_rate > 0) {
            return $amount * (float) $this->inverse_rate;
        }

        if ($this->rate > 0) {
            return $amount / (float) $this->rate;
        }

        return $amount;
    }

    /**
     * Check if the rate is stale (older than specified hours).
     *
     * @param int $hours
     * @return bool
     */
    public function isStale(int $hours = 24): bool
    {
        if (!$this->last_updated) {
            return true;
        }

        return $this->last_updated->diffInHours(now()) >= $hours;
    }

    /**
     * Format the rate for display.
     *
     * @param int $decimals
     * @return string
     */
    public function formatRate(int $decimals = 4): string
    {
        return number_format((float) $this->rate, $decimals);
    }

    /**
     * Get all rates for a base currency.
     *
     * @param string $baseCurrency
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRatesForBase(string $baseCurrency)
    {
        return self::active()
            ->fromCurrency($baseCurrency)
            ->with('toCurrency')
            ->get();
    }

    /**
     * Delete stale rates.
     *
     * @param int $days
     * @return int
     */
    public static function deleteStaleRates(int $days = 30): int
    {
        return self::where('last_updated', '<', now()->subDays($days))->delete();
    }
}
