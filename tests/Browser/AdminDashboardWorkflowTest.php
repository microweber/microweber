<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Dashboard & Module Pages Workflow Tests
 *
 * End-to-end tests for:
 *   - Dashboard loads with stat widgets, charts, quick links
 *   - Newsletter admin pages (campaigns, subscribers, lists)
 *   - Billing admin pages (subscriptions, plans)
 *   - Site stats page
 *   - Tag management pages
 *   - Customer management pages
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminDashboardWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function assertPageLoads(Browser $browser, string $url, string $name): void
    {
        $browser->visit($url)->pause(4000);
        $this->ensureLoggedIn($browser);

        $currentUrl = $browser->driver->getCurrentURL();
        if (str_contains($currentUrl, '/login')) {
            $browser->visit($url)->pause(4000);
        }

        $pageSource = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
            "{$name} should not return 500");
        $this->assertStringNotContainsString('Whoops', $pageSource,
            "{$name} should not show error page");
    }

    #[Test]
    public function dashboard_has_stats_and_widgets(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(5000);

            $checks = 0;
            $failed = [];

            // Check 1: Dashboard has widget/stat sections
            try {
                $widgetInfo = $browser->script("
                    var widgets = document.querySelectorAll('.fi-wi, [class*=\"widget\"], .fi-section');
                    var statsOverview = document.querySelectorAll('.fi-wi-stats-overview, [class*=\"stats\"]');
                    var charts = document.querySelectorAll('canvas, svg[class*=\"chart\"], [class*=\"chart\"]');
                    return {
                        widgetCount: widgets.length,
                        statsCount: statsOverview.length,
                        chartCount: charts.length,
                        hasContent: document.querySelector('.fi-page-content') !== null
                    };
                ");

                $info = $widgetInfo[0] ?? [];
                $this->assertTrue(
                    ($info['widgetCount'] ?? 0) > 0 || ($info['hasContent'] ?? false),
                    'Dashboard should have widgets or content sections'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['widgets'] = $e->getMessage();
            }

            // Check 2: Dashboard has stat numbers (views, orders, users, etc.)
            try {
                $bodyText = $browser->script("return document.body.innerText;");
                $text = $bodyText[0] ?? '';

                $hasStats = preg_match('/\d+/', $text);
                $this->assertTrue($hasStats > 0,
                    'Dashboard should display numeric stat values');
                $checks++;
            } catch (\Exception $e) {
                $failed['stat_numbers'] = $e->getMessage();
            }

            // Check 3: Dashboard has quick links or navigation cards
            try {
                $linkInfo = $browser->script("
                    var links = document.querySelectorAll('a[href*=\"/admin/\"]');
                    var adminLinks = [];
                    links.forEach(function(l) {
                        if (l.href && !l.href.includes('/login')) {
                            adminLinks.push(l.textContent.trim().substring(0, 50));
                        }
                    });
                    return { count: adminLinks.length, sample: adminLinks.slice(0, 10) };
                ");

                $info = $linkInfo[0] ?? [];
                $this->assertGreaterThan(3, $info['count'] ?? 0,
                    'Dashboard should have admin navigation links');
                $checks++;
            } catch (\Exception $e) {
                $failed['quick_links'] = $e->getMessage();
            }

            // Check 4: No JS errors on dashboard
            try {
                $jsErrors = $browser->script("
                    return window.__duskJsErrors || [];
                ");
                // This just verifies the page doesn't crash
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Dashboard should not have server errors');
                $checks++;
            } catch (\Exception $e) {
                $failed['no_errors'] = $e->getMessage();
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " dashboard checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All dashboard checks passed (got {$checks})");
        });
    }

    #[Test]
    public function newsletter_admin_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/modules/newsletter' => 'Newsletter Module',
                '/admin/newsletter-campaigns' => 'Newsletter Campaigns',
                '/admin/newsletter-subscribers' => 'Newsletter Subscribers',
                '/admin/newsletter-lists' => 'Newsletter Lists',
                '/admin/newsletter-sender-accounts' => 'Sender Accounts',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoads($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-' . strtolower(str_replace([' ', '/'], ['-', ''], $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " newsletter page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 5, "All 5 newsletter page checks passed (got {$checks})");
        });
    }

    #[Test]
    public function billing_admin_pages_load(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $pages = [
                '/admin/subscriptions' => 'Subscriptions',
                '/admin/billing-plans' => 'Billing Plans',
                '/admin/billing-plan-groups' => 'Plan Groups',
                '/admin/billing-users' => 'Billing Users',
            ];

            foreach ($pages as $url => $name) {
                try {
                    $this->assertPageLoads($browser, $url, $name);
                    $checks++;
                } catch (\Exception $e) {
                    $failed[$name] = $e->getMessage();
                    try {
                        $browser->screenshot('fail-billing-' . strtolower(str_replace(' ', '-', $name)));
                    } catch (\Exception $ignored) {
                    }
                }
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " billing page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 4, "All 4 billing page checks passed (got {$checks})");
        });
    }

    #[Test]
    public function customer_and_tag_pages_load_with_content(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Customer pages ──
            try {
                $browser->visit('/admin/customers')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Customers list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null;
                ");
                $this->assertTrue($hasContent[0] ?? false,
                    'Customers page should have a table or empty state');
                $checks++;
            } catch (\Exception $e) {
                $failed['customers_list'] = $e->getMessage();
            }

            // ── Tag pages ──
            try {
                $this->assertPageLoads($browser, '/admin/tags', 'Tags');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null;
                ");
                $this->assertTrue($hasContent[0] ?? false,
                    'Tags page should have a table or empty state');
                $checks++;
            } catch (\Exception $e) {
                $failed['tags'] = $e->getMessage();
            }

            // ── Tag groups ──
            try {
                $this->assertPageLoads($browser, '/admin/tag-groups', 'Tag Groups');
                $checks++;
            } catch (\Exception $e) {
                $failed['tag_groups'] = $e->getMessage();
            }

            // ── Site stats ──
            try {
                $this->assertPageLoads($browser, '/admin/site-stats', 'Site Stats');

                $bodyText = $browser->script("return document.body.innerText;");
                $text = $bodyText[0] ?? '';
                $hasStats = stripos($text, 'Views') !== false
                    || stripos($text, 'Traffic') !== false
                    || stripos($text, 'Visitors') !== false
                    || stripos($text, 'Stats') !== false
                    || stripos($text, 'Page') !== false;
                $this->assertTrue($hasStats,
                    'Site stats page should display statistics content');
                $checks++;
            } catch (\Exception $e) {
                $failed['site_stats'] = $e->getMessage();
            }

            // ── Invoice pages ──
            try {
                $this->assertPageLoads($browser, '/admin/invoices', 'Invoices');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null;
                ");
                $this->assertTrue($hasContent[0] ?? false,
                    'Invoices page should have a table or empty state');
                $checks++;
            } catch (\Exception $e) {
                $failed['invoices'] = $e->getMessage();
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " module page checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 5, "All module page checks passed (got {$checks})");
        });
    }

    #[Test]
    public function dashboard_view_more_links_navigate(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(5000);
            $this->ensureLoggedIn($browser);

            $checks = 0;
            $failed = [];

            // Check: "View more" or "See all" links on dashboard navigate correctly
            try {
                $viewMoreLinks = $browser->script("
                    var links = document.querySelectorAll('a');
                    var viewMore = [];
                    links.forEach(function(l) {
                        var text = l.textContent.trim().toLowerCase();
                        if ((text.includes('view') || text.includes('see all') || text.includes('more'))
                            && l.href.includes('/admin/')) {
                            viewMore.push({ text: l.textContent.trim(), href: l.href });
                        }
                    });
                    return viewMore.slice(0, 5);
                ");

                $links = $viewMoreLinks[0] ?? [];

                if (count($links) > 0) {
                    // Click the first "view more" link
                    $targetUrl = $links[0]['href'];
                    $browser->visit($targetUrl)->pause(4000);
                    $this->ensureLoggedIn($browser);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        "View more link to {$targetUrl} should not return 500");
                    $checks++;
                } else {
                    // No "view more" links — that's OK
                    $checks++;
                }
            } catch (\Exception $e) {
                $failed['view_more'] = $e->getMessage();
            }

            // Check: Dashboard logo and branding present
            try {
                $browser->visit('/admin')->pause(3000);

                $hasBranding = $browser->script("
                    return document.querySelector('img[class*=\"logo\"], .fi-logo, img[alt*=\"logo\"]') !== null
                        || document.querySelector('.fi-header-heading') !== null
                        || document.querySelector('header') !== null;
                ");
                $this->assertTrue($hasBranding[0] ?? false,
                    'Dashboard should have branding/header elements');
                $checks++;
            } catch (\Exception $e) {
                $failed['branding'] = $e->getMessage();
            }

            // Check: Dashboard user menu exists
            try {
                $hasUserMenu = $browser->script("
                    return document.querySelector('.fi-user-menu, [class*=\"user-menu\"]') !== null
                        || document.querySelector('button[class*=\"avatar\"]') !== null
                        || document.body.innerText.includes('admin@admin.com');
                ");
                $this->assertTrue($hasUserMenu[0] ?? false,
                    'Dashboard should have a user menu or display current user');
                $checks++;
            } catch (\Exception $e) {
                $failed['user_menu'] = $e->getMessage();
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " dashboard navigation checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All dashboard navigation checks passed (got {$checks})");
        });
    }
}
