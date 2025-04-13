<?php

namespace Modules\Shipping\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Drivers\FlatRate;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;

class FlatRateTest extends TestCase
{
    #[Test]

    public function testFlatRateInitialization()
    {
        $flatRate = new FlatRate();
        $this->assertEquals('Flat Rate', $flatRate->title());
    }

    #[Test]
    public function testDefaultShippingCost()
    {
        $flatRate = new FlatRate();
        $model = new ShippingProvider();
        $model->settings = [];
        $flatRate->setModel($model);
        $this->assertEquals(0, $flatRate->getShippingCost());
    }

    #[Test]
    public function testCustomShippingCost()
    {
        $flatRate = new FlatRate();
        $model = new ShippingProvider();
        $model->settings = ['shipping_cost' => 15];
        $flatRate->setModel($model);
        $this->assertEquals(15, $flatRate->getShippingCost());
    }

    #[Test]
    public function testSettingsHandling()
    {
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
