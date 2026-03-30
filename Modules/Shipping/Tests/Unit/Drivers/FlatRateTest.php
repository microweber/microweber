<?php

namespace Modules\Shipping\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Drivers\FlatRate;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class FlatRateTest extends TestCase
{
    #[Test]
    public function it_flatrateinitialization(): void {
        $flatRate = new FlatRate();
        $this->assertEquals('Flat Rate', $flatRate->title());
    }

    #[Test]
    public function it_defaultshippingcost(): void {
        $flatRate = new FlatRate();
        $model = new ShippingProvider();
        $model->settings = [];
        $flatRate->setModel($model);
        $this->assertEquals(0, $flatRate->getShippingCost());
    }

    #[Test]
    public function it_customshippingcost(): void {
        $flatRate = new FlatRate();
        $model = new ShippingProvider();
        $model->settings = ['shipping_cost' => 15];
        $flatRate->setModel($model);
        $this->assertEquals(15, $flatRate->getShippingCost());
    }

    #[Test]
    public function it_settingshandling(): void {
        $flatRate = new FlatRate();
        $model = new ShippingProvider();
        $model->settings = [
            'shipping_cost' => 20,
            'shipping_instructions' => 'Fragile items'
        ];
        $flatRate->setModel($model);
        $this->assertEquals(20, $flatRate->getShippingCost());
    }


}
