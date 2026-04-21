<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Module Settings Workflow Tests
 *
 * Batch smoke test for all 50+ module settings pages:
 *   - Each page loads without 500/Whoops
 *   - Each page has form inputs or settings-related content
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminModuleSettingsWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function batchTestModuleSettings(Browser $browser, array $routes): array
    {
        $passed = 0;
        $failed = [];

        foreach ($routes as $route) {
            try {
                $browser->visit($route)->pause(800);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $hasError = str_contains($pageSource, 'Internal Server Error')
                    || str_contains($pageSource, 'Whoops');

                if ($hasError) {
                    $failed[$route] = '500 or Whoops error';
                    continue;
                }

                $hasContent = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    var hasWire = document.querySelector('[wire\\\\:id]') !== null;
                    var hasSections = document.querySelectorAll('.fi-section').length > 0;
                    return inputs.length > 0 || hasWire || hasSections;
                ");

                if (!($hasContent[0] ?? false)) {
                    $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                    $text = $bodyText[0] ?? '';
                    $hasSettingsText = str_contains($text, 'settings')
                        || str_contains($text, 'module')
                        || str_contains($text, 'save')
                        || str_contains($text, 'option')
                        || str_contains($text, 'config');
                    if (!$hasSettingsText) {
                        $failed[$route] = 'No form inputs or settings content';
                        continue;
                    }
                }

                $passed++;
            } catch (\Exception $e) {
                $failed[$route] = substr($e->getMessage(), 0, 150);
            }
        }

        return ['passed' => $passed, 'failed' => $failed];
    }

    #[Test]
    public function module_settings_batch_1(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $routes = [
                '/admin/accordion-module-settings',
                '/admin/audio-module-settings',
                '/admin/background-module-settings',
                '/admin/before-after-module-settings',
                '/admin/breadcrumb-module-settings',
                '/admin/btn-module-settings',
                '/admin/captcha-module-settings',
                '/admin/cart-add-module-settings',
                '/admin/category-module-settings',
                '/admin/code-editor-module-settings-page',
                '/admin/comments-module-settings',
                '/admin/contact-form-module-settings',
                '/admin/content-module-settings',
                '/admin/cookie-notice-module-settings-admin',
                '/admin/custom-fields-module-settings',
                '/admin/embed-module-settings',
                '/admin/facebook-like-module-settings',
                '/admin/facebook-page-module-settings',
                '/admin/faq-module-settings',
                '/admin/google-maps-module-settings',
                '/admin/highlight-code-module-settings',
                '/admin/image-rollover-module-settings',
                '/admin/layout-content-module-settings',
                '/admin/layouts-module-settings',
                '/admin/logo-module-settings',
                '/admin/marquee-module-settings',
            ];

            $result = $this->batchTestModuleSettings($browser, $routes);

            if (!empty($result['failed'])) {
                $report = "Failed " . count($result['failed']) . "/" . count($routes) . " module settings pages (batch 1):\n";
                foreach ($result['failed'] as $route => $error) {
                    $report .= "  - {$route}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertEquals(count($routes), $result['passed'],
                "All " . count($routes) . " module settings pages in batch 1 passed");
        });
    }

    #[Test]
    public function module_settings_batch_2(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $routes = [
                '/admin/menu-module-settings',
                '/admin/newsletter-module-settings',
                '/admin/page-module-settings',
                '/admin/pagination-module-settings',
                '/admin/pdf-module-settings',
                '/admin/pictures-module-settings',
                '/admin/post-module-settings',
                '/admin/products-module-settings',
                '/admin/rating-module-settings',
                '/admin/sharer-module-settings',
                '/admin/shop-module-settings',
                '/admin/skills-module-settings',
                '/admin/slider-module-settings',
                '/admin/social-links-module-settings',
                '/admin/spacer-module-settings',
                '/admin/tabs-module-settings',
                '/admin/tags-module-settings',
                '/admin/teamcard-module-settings',
                '/admin/testimonials-module-settings',
                '/admin/text-type-module-settings',
                '/admin/tweet-embed-module-settings',
                '/admin/video-module-settings',
            ];

            $result = $this->batchTestModuleSettings($browser, $routes);

            if (!empty($result['failed'])) {
                $report = "Failed " . count($result['failed']) . "/" . count($routes) . " module settings pages (batch 2):\n";
                foreach ($result['failed'] as $route => $error) {
                    $report .= "  - {$route}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertEquals(count($routes), $result['passed'],
                "All " . count($routes) . " module settings pages in batch 2 passed");
        });
    }
}
