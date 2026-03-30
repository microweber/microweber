<?php

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\Database\Factories\CurrencyFactory;

/**
 * Currency Model
 *
 * Represents a currency with its formatting properties.
 * Includes relationships for exchange rates.
 */
class Currency extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return CurrencyFactory::new();
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'precision',
        'thousand_separator',
        'decimal_separator',
        'swap_currency_symbol',
        'is_active',
        'is_default',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precision' => 'integer',
        'swap_currency_symbol' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Scope a query to only include active currencies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to get the default currency.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get exchange rates from this currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangeRatesFrom()
    {
        return $this->hasMany(ExchangeRate::class, 'from_currency', 'code');
    }

    /**
     * Get exchange rates to this currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangeRatesTo()
    {
        return $this->hasMany(ExchangeRate::class, 'to_currency', 'code');
    }

    /**
     * Get active exchange rates from this currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeExchangeRatesFrom()
    {
        return $this->exchangeRatesFrom()->active();
    }

    /**
     * Get active exchange rates to this currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeExchangeRatesTo()
    {
        return $this->exchangeRatesTo()->active();
    }

    /**
     * Find currency by code.
     *
     * @param string $code
     * @return Currency|null
     */
    public static function findByCode(string $code): ?self
    {
        return self::where('code', strtoupper($code))->first();
    }

    /**
     * Get the default currency.
     *
     * @return Currency|null
     */
    public static function getDefault(): ?self
    {
        return self::default()->first();
    }

    /**
     * Format an amount with this currency.
     *
     * @param float $amount
     * @return string
     */
    public function formatAmount(float $amount): string
    {
        $decimalSep = ($this->decimal_separator !== null && $this->decimal_separator !== '') ? $this->decimal_separator : '.';
        $formatted = number_format(
            $amount,
            $this->precision,
            $decimalSep,
            $this->thousand_separator
        );

        if ($this->swap_currency_symbol) {
            return $formatted . ' ' . $this->symbol;
        }

        return $this->symbol . ' ' . $formatted;
    }

    /**
     * Get amount without symbol.
     *
     * @param float $amount
     * @return string
     */
    public function formatAmountPlain(float $amount): string
    {
        $decimalSep = ($this->decimal_separator !== null && $this->decimal_separator !== '') ? $this->decimal_separator : '.';
        return number_format(
            $amount,
            $this->precision,
            $decimalSep,
            $this->thousand_separator
        );
    }

    /**
     * Set this currency as default.
     *
     * @return void
     */
    public function setAsDefault(): void
    {
        // Remove default from all other currencies
        self::where('is_default', true)->update(['is_default' => false]);

        // Set this as default
        $this->is_default = true;
        $this->save();
    }

    /**
     * Check if this currency has exchange rates defined.
     *
     * @return bool
     */
    public function hasExchangeRates(): bool
    {
        return $this->activeExchangeRatesFrom()->exists();
    }

    /**
     * Get all currencies that can be converted to/from this currency.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConvertibleCurrencies()
    {
        $rateCodes = $this->activeExchangeRatesFrom()
            ->pluck('to_currency')
            ->toArray();

        return self::whereIn('code', $rateCodes)->active()->get();
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure code is always uppercase
        static::saving(function ($currency) {
            if ($currency->code) {
                $currency->code = strtoupper($currency->code);
            }
        });
    }
}
