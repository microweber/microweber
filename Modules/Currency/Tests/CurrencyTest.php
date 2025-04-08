<?php

namespace Modules\Currency\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Currency\Models\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCurrency()
    {
        $currency = Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        $this->assertDatabaseHas('currencies', [
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
        ]);
    }
}
