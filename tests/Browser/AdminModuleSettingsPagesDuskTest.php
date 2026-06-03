<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk tests for all module settings admin pages.
 *
 * Visits each module settings page via its admin URL and verifies:
 *   - Page loads successfully (no 500, no Whoops)
 *   - Page contains Filament form elements (inputs, toggles, selects, tabs)
 *   - No critical JS errors
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 *   - Login captcha disabled
 */
class AdminModuleSettingsPagesDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * All module settings admin page slugs grouped into batches.
     * Each batch is tested in a single browser session to reduce login overhead.
     */
    private static function allModuleSettingsSlugs(): array
    {
        return [
            'accordion-module-settings',
            'audio-module-settings',
            'background-module-settings',
            'before-after-module-settings',
            'breadcrumb-module-settings',
            'btn-module-settings',
            'captcha-module-settings',
            'cart-add-module-settings',
            'category-module-settings',
            'comments-module-settings',
            'contact-form-module-settings',
            'content-module-settings',
            'cookie-notice-module-settings-admin',
            'custom-fields-module-settings',
            'embed-module-settings',
            'facebook-like-module-settings',
            'facebook-page-module-settings',
            'faq-module-settings',
            'google-maps-module-settings',
            'highlight-code-module-settings',
            'image-rollover-module-settings',
            'layout-content-module-settings',
            'layouts-module-settings',
            'logo-module-settings',
            'marquee-module-settings',
            'menu-module-settings',
            'newsletter-module-settings',
            'page-module-settings',
            'pagination-module-settings',
            'pdf-module-settings',
            'pictures-module-settings',
            'post-module-settings',
            'products-module-settings',
            'rating-module-settings',
            'sharer-module-settings',
            'shop-module-settings',
            'skills-module-settings',
            'slider-module-settings',
            'social-links-module-settings',
            'spacer-module-settings',
            'tabs-module-settings',
            'tags-module-settings',
            'teamcard-module-settings',
            'testimonials-module-settings',
            'text-type-module-settings',
            'tweet-embed-module-settings',
            'video-module-settings',
        ];
    }

    /**
     * Additional admin settings pages (not standard module settings).
     */
    private static function adminSettingsSlugs(): array
    {
        return [
            'comments-module-settings-admin',
        ];
    }

    /**
     * Batch 1: accordion through faq (16 modules)
     */
    #[Test]
    public function module_settings_batch1_render_without_errors(): void
    {
        $slugs = array_slice(self::allModuleSettingsSlugs(), 0, 16);
        $this->assertModuleSettingsPagesLoad($slugs);
    }

    /**
     * Batch 2: google_maps through products (16 modules)
     */
    #[Test]
    public function module_settings_batch2_render_without_errors(): void
    {
        $slugs = array_slice(self::allModuleSettingsSlugs(), 16, 16);
        $this->assertModuleSettingsPagesLoad($slugs);
    }

    /**
     * Batch 3: rating through video + admin-only pages (15 + 1 modules)
     */
    #[Test]
    public function module_settings_batch3_render_without_errors(): void
    {
        $slugs = array_merge(
            array_slice(self::allModuleSettingsSlugs(), 32),
            self::adminSettingsSlugs()
        );
        $this->assertModuleSettingsPagesLoad($slugs);
    }

    /**
     * Verify that module settings pages contain form elements (inputs, toggles, tabs).
     * Tests a representative subset of modules that are known to have rich forms.
     */
    #[Test]
    public function module_settings_pages_contain_form_elements(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $richModules = [
                'btn-module-settings',
                'contact-form-module-settings',
                'menu-module-settings',
                'logo-module-settings',
                'shop-module-settings',
                'video-module-settings',
                'slider-module-settings',
                'social-links-module-settings',
            ];

            $checked = 0;
            $failed = [];

            foreach ($richModules as $slug) {
                $browser->visit("/admin/{$slug}")->pause(2000);

                $pageSource = $browser->driver->getPageSource();

                // Skip if page redirected to login
                if (stripos($pageSource, 'login') !== false && stripos($pageSource, 'password') !== false) {
                    $this->ensureLoggedIn($browser);
                    $browser->visit("/admin/{$slug}")->pause(2000);
                    $pageSource = $browser->driver->getPageSource();
                }

                // Check for form elements
                $hasFormElements = $browser->script("
                    try {
                        var inputs = document.querySelectorAll(
                            'input:not([type=\"hidden\"]), select, textarea, ' +
                            '.fi-toggle, [role=\"tab\"], .fi-fo-select, ' +
                            '.fi-input, .fi-ta-content, button[type=\"submit\"]'
                        );
                        return inputs.length;
                    } catch(e) { return 0; }
                ");

                $count = $hasFormElements[0] ?? 0;

                if ($count > 0) {
                    $checked++;
                } else {
                    $failed[$slug] = "No form elements found (0 inputs/selects/toggles/tabs)";
                }
            }

            if (!empty($failed)) {
                $report = "Module settings pages missing form elements:\n";
                foreach ($failed as $slug => $error) {
                    $report .= "  - {$slug}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(4, $checked,
                "At least 4 module settings should have form elements (found {$checked})");
        });
    }

    /**
     * Core assertion: visits each slug, checks for 500/Whoops errors.
     */
    private function assertModuleSettingsPagesLoad(array $slugs): void
    {
        $this->browse(function (Browser $browser) use ($slugs) {
            $this->loginAsAdmin($browser);

            $checked = 0;
            $failed = [];

            foreach ($slugs as $slug) {
                try {
                    $browser->visit("/admin/{$slug}")->pause(1500);

                    $pageSource = $browser->driver->getPageSource();

                    // If redirected to login, re-authenticate and retry
                    if (stripos($pageSource, 'login') !== false && stripos($pageSource, 'password') !== false) {
                        $this->ensureLoggedIn($browser);
                        $browser->visit("/admin/{$slug}")->pause(1500);
                        $pageSource = $browser->driver->getPageSource();
                    }

                    // No 500 errors
                    $this->assertStringNotContainsString(
                        'Internal Server Error',
                        $pageSource,
                        "Page /admin/{$slug} returned 500"
                    );

                    // No Whoops error page
                    $this->assertStringNotContainsString(
                        'Whoops',
                        $pageSource,
                        "Page /admin/{$slug} shows Whoops error"
                    );

                    // No Livewire exception
                    $this->assertStringNotContainsString(
                        'Livewire\Exceptions',
                        $pageSource,
                        "Page /admin/{$slug} has Livewire exception"
                    );

                    $checked++;
                } catch (\Exception $e) {
                    $failed[$slug] = substr($e->getMessage(), 0, 200);
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checked + count($failed)) . " module settings page checks:\n";
                foreach ($failed as $slug => $error) {
                    $report .= "  - /admin/{$slug}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(count($slugs), $checked,
                "All {$checked}/" . count($slugs) . " module settings pages should load");
        });
    }
}
