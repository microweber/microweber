<?php

namespace Modules\Currency\Tests\Filament;

use Modules\Currency\Filament\Admin\Resources\CurrencyResource;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class CurrencyResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_currency_resource_exists(): void
    {
        $this->assertTrue(class_exists(CurrencyResource::class));
    }

    #[Test]
    public function it_exchange_rate_resource_exists(): void
    {
        $this->assertTrue(class_exists(ExchangeRateResource::class));
    }

    #[Test]
    public function it_currency_resource_has_model(): void
    {
        $this->assertNotNull(CurrencyResource::getModel());
    }

    #[Test]
    public function it_exchange_rate_resource_has_model(): void
    {
        $this->assertNotNull(ExchangeRateResource::getModel());
    }

}
