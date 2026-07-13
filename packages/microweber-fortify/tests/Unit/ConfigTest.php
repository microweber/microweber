<?php

namespace MicroweberPackages\Fortify\Tests\Unit;

use MicroweberPackages\Fortify\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_fortify_config_is_loaded(): void
    {
        $this->assertNotNull(config('fortify.guard'));
        $this->assertEquals('web', config('fortify.guard'));
    }

    public function test_microweber_fortify_config_is_loaded(): void
    {
        $this->assertNotNull(config('microweber-fortify'));
        $this->assertEquals(8, config('microweber-fortify.recovery_codes_count'));
        $this->assertEquals(200, config('microweber-fortify.qr_code_size'));
    }

    public function test_two_factor_feature_is_enabled(): void
    {
        $features = config('fortify.features', []);
        $this->assertNotEmpty($features);

        $hasTwoFactor = false;
        foreach ($features as $feature) {
            if (str_contains($feature, 'two-factor')) {
                $hasTwoFactor = true;
                break;
            }
        }
        $this->assertTrue($hasTwoFactor, '2FA feature should be enabled in fortify config');
    }
}