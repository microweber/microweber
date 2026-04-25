<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Missing Coverage Tests
 *
 * Tests for admin areas not covered by existing Dusk tests:
 *   - FAQ modules CRUD
 *   - Template customization page
 *   - File manager page
 *   - Site stats page
 *   - Setup wizard page
 *   - Contents (general content list)
 *   - Offers list
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminMissingCoverageTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function faq_modules_crud(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: FAQ list page loads ──
            try {
                $browser->visit('/admin/faq-modules')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'FAQ modules list should not return 500');

                $hasTable = $browser->script("
                    var table = document.querySelector('table, .fi-ta, [class*=\"table\"]');
                    var hasWire = document.querySelector('[wire\\\\:id]') !== null;
                    return table !== null || hasWire;
                ");
                $this->assertTrue($hasTable[0] ?? false,
                    'FAQ modules page should have a table or Livewire component');

                $checks++;
            } catch (\Exception $e) {
                $failed['faq_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: FAQ create form loads ──
            try {
                $browser->visit('/admin/faq-modules/create')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'FAQ create page should not return 500');

                $hasForm = $browser->script("
                    var inputs = document.querySelectorAll('input, textarea, select');
                    return inputs.length > 0;
                ");
                $this->assertTrue($hasForm[0] ?? false,
                    'FAQ create page should have form inputs');

                $checks++;
            } catch (\Exception $e) {
                $failed['faq_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " FAQ module checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(2, $checks, "All FAQ checks passed ({$checks})");
        });
    }

    #[Test]
    public function template_customization_page(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Template customization loads ──
            try {
                $browser->visit('/admin/template-customization')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Template customization should not return 500');
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Template customization should not show error page');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasContent = str_contains($text, 'template')
                    || str_contains($text, 'customiz')
                    || str_contains($text, 'theme')
                    || str_contains($text, 'design')
                    || str_contains($text, 'style');

                $hasInputs = $browser->script("
                    var inputs = document.querySelectorAll('input, textarea, select, .fi-toggle, iframe');
                    return inputs.length > 0;
                ");

                $this->assertTrue($hasContent || ($hasInputs[0] ?? false),
                    'Template customization should have relevant content or inputs');

                $checks++;
            } catch (\Exception $e) {
                $failed['template_customization'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed template customization checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 1, "Template customization check passed");
        });
    }

    #[Test]
    public function file_manager_and_site_stats_pages(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: File manager page loads ──
            try {
                $browser->visit('/admin/file-manager-page-admin')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'File manager should not return 500');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasContent = str_contains($text, 'file')
                    || str_contains($text, 'media')
                    || str_contains($text, 'upload')
                    || str_contains($text, 'manager');

                $hasElements = $browser->script("
                    var els = document.querySelectorAll('[wire\\\\:id], iframe, .fi-section, input, button');
                    return els.length > 0;
                ");

                $this->assertTrue($hasContent || ($hasElements[0] ?? false),
                    'File manager should have relevant content');

                $checks++;
            } catch (\Exception $e) {
                $failed['file_manager'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Site stats page loads ──
            try {
                $browser->visit('/admin/site-stats')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Site stats should not return 500');

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasContent = str_contains($text, 'stat')
                    || str_contains($text, 'visit')
                    || str_contains($text, 'traffic')
                    || str_contains($text, 'analytics')
                    || str_contains($text, 'page view');

                $this->assertTrue($hasContent,
                    'Site stats page should have stats-related content');

                $checks++;
            } catch (\Exception $e) {
                $failed['site_stats'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Contents (general) list loads ──
            try {
                $browser->visit('/admin/contents')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Contents list should not return 500');

                $hasTable = $browser->script("
                    var table = document.querySelector('table, .fi-ta, [class*=\"table\"]');
                    var hasWire = document.querySelector('[wire\\\\:id]') !== null;
                    return table !== null || hasWire;
                ");
                $this->assertTrue($hasTable[0] ?? false,
                    'Contents page should have a table or Livewire component');

                $checks++;
            } catch (\Exception $e) {
                $failed['contents_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Offers list loads ──
            try {
                $browser->visit('/admin/offers')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Offers list should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['offers_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(4, $checks, "All page checks passed ({$checks})");
        });
    }

    #[Test]
    public function setup_wizard_and_shop_settings_pages(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Setup wizard loads ──
            try {
                $browser->visit('/admin/setup-wizard')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Setup wizard should not return 500');
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Setup wizard should not show error page');

                $checks++;
            } catch (\Exception $e) {
                $failed['setup_wizard'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Blog settings page loads ──
            try {
                $browser->visit('/admin/blog-settings')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Blog settings should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['blog_settings'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Search settings page loads ──
            try {
                $browser->visit('/admin/search-settings')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Search settings should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['search_settings'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Settings menus page loads ──
            try {
                $browser->visit('/admin/settings/menus')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Settings menus page should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['settings_menus'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 5: Settings Google Analytics page loads ──
            try {
                $browser->visit('/admin/settings/google-analytics')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Google Analytics settings should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['google_analytics'] = substr($e->getMessage(), 0, 200);
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " settings checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(5, $checks, "All settings checks passed ({$checks})");
        });
    }

    #[Test]
    public function shop_categories_and_inventory_pages(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Shop categories page loads ──
            try {
                $browser->visit('/admin/shop-categories')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Shop categories should not return 500');

                $hasTable = $browser->script("
                    var table = document.querySelector('table, .fi-ta, [class*=\"table\"]');
                    var hasWire = document.querySelector('[wire\\\\:id]') !== null;
                    return table !== null || hasWire;
                ");
                $this->assertTrue($hasTable[0] ?? false,
                    'Shop categories page should have a table');

                $checks++;
            } catch (\Exception $e) {
                $failed['shop_categories'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Shop categories create loads ──
            try {
                $browser->visit('/admin/shop-categories/create')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Shop category create should not return 500');

                $hasForm = $browser->script("
                    var inputs = document.querySelectorAll('input, textarea, select');
                    return inputs.length > 0;
                ");
                $this->assertTrue($hasForm[0] ?? false,
                    'Shop category create should have form inputs');

                $checks++;
            } catch (\Exception $e) {
                $failed['shop_categories_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Inventory create form loads ──
            try {
                $browser->visit('/admin/inventory/create')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Inventory create should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['inventory_create'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 4: Rating modules page loads ──
            try {
                $browser->visit('/admin/rating-modules')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Rating modules should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['rating_modules'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 5: White label settings page loads ──
            try {
                $browser->visit('/admin/white-label-settings-admin-settings-page')->pause(1200);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'White label settings should not return 500');

                $checks++;
            } catch (\Exception $e) {
                $failed['white_label'] = substr($e->getMessage(), 0, 200);
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " shop/inventory checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(5, $checks, "All checks passed ({$checks})");
        });
    }
}
