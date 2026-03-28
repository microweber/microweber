<?php

namespace Modules\Currency\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Currency\Models\Currency;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        $codes = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'SEK', 'NOK', 'DKK'];
        $code = $this->faker->unique()->randomElement($codes);

        return [
            'name' => $code . ' Currency',
            'code' => $code,
            'symbol' => match($code) {
                'USD' => '$',
                'EUR' => '€',
                'GBP' => '£',
                default => $code,
            },
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
            'is_default' => false,
            'position' => 'left',
        ];
    }
}
