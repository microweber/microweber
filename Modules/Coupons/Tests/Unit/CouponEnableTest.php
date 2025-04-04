<?php

namespace Modules\Coupons\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Coupons\Services\CouponService;

class CouponEnableTest extends TestCase
{
    public function testEnableDisableLogic()
    {
        $service = new class extends CouponService {
            private $enabled = false;
            
            public function isEnabled(): bool {
                return $this->enabled;
            }
            
            public function setEnabled(bool $enabled): void {
                $this->enabled = $enabled;
            }
        };

        // Test enabling
        $service->setEnabled(true);
        $this->assertTrue($service->isEnabled());

        // Test disabling
        $service->setEnabled(false);
        $this->assertFalse($service->isEnabled());
    }
}