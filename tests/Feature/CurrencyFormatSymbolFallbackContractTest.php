<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-08-curfmt
 *
 * CurrencyManager::format() must always render a currency symbol.
 *
 * On a fresh install the currencies table is empty, so
 * getCurrentCurrencyCode() returns the 'USD' ultimate fallback but
 * Currency::findByCode('USD') is null. format() used to fall through to a
 * bare number_format() with NO symbol — so every storefront price rendered
 * as a bare number ("19.99") while getSymbol() still returned "$". The two
 * fallbacks were inconsistent. format() now prepends getSymbol()'s "$"
 * default, matching getSymbol(); formatPlain() stays symbol-less by design.
 *
 * Uses a bogus currency code so the no-currency fallback is exercised
 * deterministically regardless of what (if anything) is seeded.
 */
class CurrencyFormatSymbolFallbackContractTest extends TestCase
{
    #[Test]
    public function format_prepends_symbol_when_currency_not_found(): void
    {
        $this->assertSame('$19.99', currency_manager()->format(19.99, 'ZZZ'));
        $this->assertSame('$0.00', currency_manager()->format(0, 'ZZZ'));
    }

    #[Test]
    public function format_plain_stays_symbol_less(): void
    {
        $this->assertSame('19.99', currency_manager()->formatPlain(19.99, 'ZZZ'));
    }

    #[Test]
    public function format_and_get_symbol_fallbacks_are_consistent(): void
    {
        $symbol = currency_manager()->getSymbol('ZZZ');
        $this->assertSame('$', $symbol);
        $this->assertStringStartsWith(
            $symbol,
            currency_manager()->format(5, 'ZZZ'),
            'format() must begin with the same symbol getSymbol() returns.'
        );
    }
}
