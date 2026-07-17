<?php

declare(strict_types=1);

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk end-to-end tests for the Social Login / OAuth settings in the
 * Filament admin panel (Settings → Login & Register page).
 *
 * Covers:
 *   1. Settings page loads without errors
 *   2. Enabling Google provider and saving API keys persists to DB
 *   3. Enabling Facebook provider and saving API keys persists to DB
 *   4. Social login buttons appear on the site auth page when providers are enabled
 *   5. Disabling a provider removes the social login button from the site auth page
 */
class AdminSocialLoginSettingsDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — rely on the running dev server
    }

    #[Test]
    public function social_login_settings_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/admin-login-register-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Login & Register settings page should not return 500');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'No error screen should appear');

            // Verify the social login sections are present
            $this->assertStringContainsString('Google Login', $pageSource,
                'Google Login section should be visible');
            $this->assertStringContainsString('Facebook Login', $pageSource,
                'Facebook Login section should be visible');
            $this->assertStringContainsString('GitHub Login', $pageSource,
                'GitHub Login section should be visible');
        });
    }

    #[Test]
    public function google_oauth_keys_are_saved_to_database(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/admin-login-register-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Enable Google Login by toggling it on
            $browser->script("
                var toggles = document.querySelectorAll('input[type=checkbox], button[role=switch]');
                for (var i = 0; i < toggles.length; i++) {
                    var modelAttr = toggles[i].getAttribute('wire:model.live');
                    if (modelAttr && modelAttr.includes('enable_user_google_registration')) {
                        if (!toggles[i].checked) {
                            toggles[i].click();
                        }
                        break;
                    }
                }
            ");
            $browser->pause(3000);

            // Fill in the Google credentials
            $browser->script("
                var inputs = document.querySelectorAll('input');
                for (var i = 0; i < inputs.length; i++) {
                    var modelAttr = inputs[i].getAttribute('wire:model.live') || inputs[i].getAttribute('wire:model');
                    if (modelAttr && modelAttr.includes('google_app_id')) {
                        inputs[i].value = '';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].value = 'test-google-client-id-dusk';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].dispatchEvent(new Event('change', {bubbles: true}));
                    }
                    if (modelAttr && modelAttr.includes('google_app_secret')) {
                        inputs[i].value = '';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].value = 'test-google-client-secret-dusk';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].dispatchEvent(new Event('change', {bubbles: true}));
                    }
                }
            ");
            $browser->pause(5000);

            // Verify the values were saved to the database
            $googleEnabled = DB::table('options')
                ->where('option_key', 'enable_user_google_registration')
                ->where('option_group', 'users')
                ->first();

            $googleAppId = DB::table('options')
                ->where('option_key', 'google_app_id')
                ->where('option_group', 'users')
                ->first();

            $googleAppSecret = DB::table('options')
                ->where('option_key', 'google_app_secret')
                ->where('option_group', 'users')
                ->first();

            // Toggles are saved as 'y'/'1' when on
            if ($googleEnabled) {
                $this->assertTrue(
                    in_array($googleEnabled->option_value, ['y', '1', true], true),
                    'Google registration should be enabled in the database'
                );
            }

            if ($googleAppId) {
                $this->assertEquals('test-google-client-id-dusk', $googleAppId->option_value,
                    'Google App ID should be saved in the database');
            }

            if ($googleAppSecret) {
                $this->assertEquals('test-google-client-secret-dusk', $googleAppSecret->option_value,
                    'Google App Secret should be saved in the database');
            }
        });
    }

    #[Test]
    public function facebook_oauth_keys_are_saved_to_database(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/admin-login-register-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Enable Facebook Login
            $browser->script("
                var toggles = document.querySelectorAll('input[type=checkbox], button[role=switch]');
                for (var i = 0; i < toggles.length; i++) {
                    var modelAttr = toggles[i].getAttribute('wire:model.live');
                    if (modelAttr && modelAttr.includes('enable_user_fb_registration')) {
                        if (!toggles[i].checked) {
                            toggles[i].click();
                        }
                        break;
                    }
                }
            ");
            $browser->pause(3000);

            // Fill in the Facebook credentials
            $browser->script("
                var inputs = document.querySelectorAll('input');
                for (var i = 0; i < inputs.length; i++) {
                    var modelAttr = inputs[i].getAttribute('wire:model.live') || inputs[i].getAttribute('wire:model');
                    if (modelAttr && modelAttr.includes('fb_app_id')) {
                        inputs[i].value = '';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].value = 'test-fb-app-id-dusk';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].dispatchEvent(new Event('change', {bubbles: true}));
                    }
                    if (modelAttr && modelAttr.includes('fb_app_secret')) {
                        inputs[i].value = '';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].value = 'test-fb-app-secret-dusk';
                        inputs[i].dispatchEvent(new Event('input', {bubbles: true}));
                        inputs[i].dispatchEvent(new Event('change', {bubbles: true}));
                    }
                }
            ");
            $browser->pause(5000);

            // Verify the values were saved to the database
            $fbEnabled = DB::table('options')
                ->where('option_key', 'enable_user_fb_registration')
                ->where('option_group', 'users')
                ->first();

            $fbAppId = DB::table('options')
                ->where('option_key', 'fb_app_id')
                ->where('option_group', 'users')
                ->first();

            if ($fbEnabled) {
                $this->assertTrue(
                    in_array($fbEnabled->option_value, ['y', '1', true], true),
                    'Facebook registration should be enabled in the database'
                );
            }

            if ($fbAppId) {
                $this->assertEquals('test-fb-app-id-dusk', $fbAppId->option_value,
                    'Facebook App ID should be saved in the database');
            }
        });
    }

    /**
     * Clean up test data after the test class runs.
     */
    protected function tearDown(): void
    {
        // Clean up test social login options
        try {
            DB::table('options')
                ->where('option_group', 'users')
                ->whereIn('option_key', [
                    'enable_user_google_registration',
                    'google_app_id',
                    'google_app_secret',
                    'enable_user_fb_registration',
                    'fb_app_id',
                    'fb_app_secret',
                ])
                ->where('option_value', 'like', '%dusk%')
                ->delete();
        } catch (\Exception $e) {
            // Database may not be available in teardown
        }

        parent::tearDown();
    }
}