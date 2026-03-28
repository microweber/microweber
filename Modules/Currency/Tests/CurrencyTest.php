<?php

namespace Modules\Currency\Tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Currency\Models\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]

    public function it_create_currency(): void {
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
