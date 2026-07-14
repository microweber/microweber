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

    public function test_default_rate_limit_config(): void
    {
        $this->assertEquals(5, config('microweber-fortify.rate_limit.max_attempts'));
        $this->assertEquals(15, config('microweber-fortify.rate_limit.decay_minutes'));
    }

    public function test_default_require_2fa_settings(): void
    {
        $this->assertFalse((bool) config('microweber-fortify.require2fa_all'));
        $this->assertFalse((bool) config('microweber-fortify.require2fa_admin_only'));
    }

    public function test_fortify_limiters_configured(): void
    {
        $this->assertEquals('login', config('fortify.limiters.login'));
        $this->assertEquals('two-factor', config('fortify.limiters.two-factor'));
    }
}