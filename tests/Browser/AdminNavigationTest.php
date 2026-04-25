<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Navigation Tests
 *
 * Tests sidebar navigation, breadcrumbs, and navigation between sections.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminNavigationTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function sidebar_has_navigation_groups(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(5000);

            // Sidebar should exist with navigation items
            $sidebarInfo = $browser->script("
                var sidebar = document.querySelector('.fi-sidebar');
                if (!sidebar) return { exists: false };

                var items = sidebar.querySelectorAll('.fi-sidebar-item');
                var groups = sidebar.querySelectorAll('.fi-sidebar-group');
                var labels = [];
                items.forEach(function(item) {
                    var label = item.querySelector('.fi-sidebar-item-label');
                    if (label) labels.push(label.textContent.trim());
                });

                return {
                    exists: true,
                    itemCount: items.length,
                    groupCount: groups.length,
                    labels: labels.slice(0, 20)
                };
            ");

            $info = $sidebarInfo[0] ?? [];
            $this->assertTrue($info['exists'] ?? false, 'Sidebar should exist');
            $this->assertGreaterThan(3, $info['itemCount'] ?? 0,
                'Sidebar should have more than 3 navigation items');
        });
    }

    #[Test]
    public function sidebar_navigation_works(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // Test clicking on key sidebar items and verifying navigation
            $targets = [
                'Pages' => '/admin/pages',
                'Orders' => '/admin/orders',
                'Users' => '/admin/users',
            ];

            foreach ($targets as $label => $expectedPath) {
                try {
                    $browser->visit('/admin')->pause(3000);
                    $this->ensureLoggedIn($browser);

                    // Click the sidebar item by label text
                    $clicked = $browser->script("
                        var items = document.querySelectorAll('.fi-sidebar-item-label');
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].textContent.trim() === '{$label}') {
                                var link = items[i].closest('a');
                                if (link) { link.click(); return true; }
                            }
                        }
                        return false;
                    ");

                    $browser->pause(4000);

                    $currentUrl = $browser->driver->getCurrentURL();
                    $this->assertStringContainsString($expectedPath, $currentUrl,
                        "Clicking '{$label}' sidebar item should navigate to {$expectedPath}");

                    $checks++;
                } catch (\Exception $e) {
                    $failed[$label] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-nav-' . strtolower($label));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " navigation checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All navigation checks passed (got {$checks})");
        });
    }

    #[Test]
    public function breadcrumbs_show_on_subpages(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/pages')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Check for breadcrumb component
            $hasBreadcrumbs = $browser->script("
                var bc = document.querySelector('.fi-breadcrumbs, nav[aria-label=\"Breadcrumb\"], ol[class*=\"breadcrumb\"]');
                if (!bc) return { exists: false };
                var items = bc.querySelectorAll('li, a, span');
                var texts = [];
                items.forEach(function(el) {
                    var t = el.textContent.trim();
                    if (t && t !== '/' && t !== '>') texts.push(t);
                });
                return { exists: true, items: texts };
            ");

            $info = $hasBreadcrumbs[0] ?? [];
            $this->assertTrue($info['exists'] ?? false,
                'Breadcrumbs should be present on subpages');
        });
    }

    #[Test]
    public function admin_dashboard_has_widgets(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(5000);

            // Dashboard should have widget containers
            $widgetInfo = $browser->script("
                var widgets = document.querySelectorAll('.fi-wi, [class*=\"widget\"], .fi-section');
                var statsWidgets = document.querySelectorAll('.fi-wi-stats-overview, [class*=\"stats\"]');
                return {
                    widgetCount: widgets.length,
                    hasStats: statsWidgets.length > 0,
                    hasContent: document.querySelector('.fi-page-content') !== null
                };
            ");

            $info = $widgetInfo[0] ?? [];
            $this->assertTrue(($info['widgetCount'] ?? 0) > 0 || ($info['hasContent'] ?? false),
                'Dashboard should have widgets or content sections');
        });
    }

    #[Test]
    public function guest_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            // Visit admin without being logged in — use a fresh session
            $browser->visit('/admin/login')->pause(2000);

            // Logout if logged in
            $browser->script("
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': decodeURIComponent((document.cookie.match(/XSRF-TOKEN=([^;]+)/) || ['',''])[1])
                    }
                });
            ");
            $browser->pause(2000);

            // Clear cookies to simulate guest
            $browser->driver->manage()->deleteAllCookies();
            $browser->pause(1000);

            // Try to access admin pages
            $browser->visit('/admin/pages')->pause(5000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($currentUrl, '/login') || str_contains($currentUrl, '/admin/login'),
                'Guest should be redirected to login page. Got: ' . $currentUrl
            );
        });
    }
}
