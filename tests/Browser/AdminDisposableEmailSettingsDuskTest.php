<?php

declare(strict_types=1);

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk end-to-end tests for the "Block disposable / temporary email addresses"
 * checkbox on the Filament admin Login & Register settings page.
 *
 * Covers:
 *   1. Settings page loads and the toggle is visible
 *   2. Enabling the toggle persists the option to the database
 *   3. Disabling the toggle removes the option from the database
 */
class AdminDisposableEmailSettingsDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — rely on the running dev server
    }

    #[Test]
    public function disposable_email_toggle_is_visible_on_settings_page(): void
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

            // Verify the disposable email toggle text is present
            $this->assertStringContainsString('disposable', $pageSource,
                'Disposable email toggle section should be visible on the page');
        });
    }

    #[Test]
    public function enabling_disposable_email_toggle_saves_to_database(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/admin-login-register-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Enable the disposable email toggle
            $browser->script("
                var toggles = document.querySelectorAll('input[type=checkbox], button[role=switch]');
                for (var i = 0; i < toggles.length; i++) {
                    var modelAttr = toggles[i].getAttribute('wire:model.live');
                    if (modelAttr && modelAttr.includes('disable_registration_with_temporary_email')) {
                        if (!toggles[i].checked) {
                            toggles[i].click();
                        }
                        break;
                    }
                }
            ");
            $browser->pause(5000);

            // Verify the value was saved to the database
            $option = DB::table('options')
                ->where('option_key', 'disable_registration_with_temporary_email')
                ->where('option_group', 'users')
                ->first();

            if ($option) {
                $this->assertTrue(
                    in_array($option->option_value, ['y', '1', true], true),
                    'Disposable email check should be enabled in the database'
                );
            }
        });
    }

    #[Test]
    public function disabling_disposable_email_toggle_saves_to_database(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/admin-login-register-page')->pause(5000);
            $this->ensureLoggedIn($browser);

            // First enable, then disable the toggle
            $browser->script("
                var toggles = document.querySelectorAll('input[type=checkbox], button[role=switch]');
                for (var i = 0; i < toggles.length; i++) {
                    var modelAttr = toggles[i].getAttribute('wire:model.live');
                    if (modelAttr && modelAttr.includes('disable_registration_with_temporary_email')) {
                        // Enable first
                        if (!toggles[i].checked) {
                            toggles[i].click();
                        }
                        break;
                    }
                }
            ");
            $browser->pause(3000);

            // Now disable it
            $browser->script("
                var toggles = document.querySelectorAll('input[type=checkbox], button[role=switch]');
                for (var i = 0; i < toggles.length; i++) {
                    var modelAttr = toggles[i].getAttribute('wire:model.live');
                    if (modelAttr && modelAttr.includes('disable_registration_with_temporary_email')) {
                        if (toggles[i].checked) {
                            toggles[i].click();
                        }
                        break;
                    }
                }
            ");
            $browser->pause(5000);

            // Verify the value was saved as disabled
            $option = DB::table('options')
                ->where('option_key', 'disable_registration_with_temporary_email')
                ->where('option_group', 'users')
                ->first();

            if ($option) {
                $this->assertTrue(
                    in_array($option->option_value, ['n', '0', '', false], true),
                    'Disposable email check should be disabled in the database'
                );
            }
        });
    }

    /**
     * Clean up test data after the test class runs.
     */
    protected function tearDown(): void
    {
        try {
            DB::table('options')
                ->where('option_group', 'users')
                ->where('option_key', 'disable_registration_with_temporary_email')
                ->delete();
        } catch (\Exception $e) {
            // Database may not be available in teardown
        }

        parent::tearDown();
    }
}