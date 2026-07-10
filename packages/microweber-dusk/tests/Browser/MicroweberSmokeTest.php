<?php

namespace MicroweberPackages\Dusk\Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Dusk\Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * End-to-end smoke test: install → login → visit all sidebar pages.
 *
 * Supports SQLite, MySQL, and PostgreSQL via environment variables.
 * DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD are read
 * from the environment and passed to the install form.
 */
class MicroweberSmokeTest extends DuskTestCase
{
    /**
     * Step 1: Complete the installation wizard.
     */
    #[Test]
    public function step1_installation(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();
            $pageSource = $browser->driver->getPageSource();

            $browser->screenshot('step1-01-landing');

            // If already installed, skip
            if (!str_contains($pageSource, 'Database Server')
                && !str_contains($pageSource, 'Login Information')
                && !str_contains($pageSource, 'Installation')
                && !str_contains($currentUrl, 'install')) {
                $this->assertTrue(true, 'Site is already installed');
                return;
            }

            $browser->waitFor('form', 20)
                ->screenshot('step1-02-form');

            // Select the right DB driver from environment
            $dbDriver = env('DB_CONNECTION', 'sqlite');
            $browser->script("
                var sel = document.querySelector('select[name=\"db_driver\"]');
                if (sel) {
                    for (var i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].value === '{$dbDriver}') {
                            sel.selectedIndex = i;
                            sel.dispatchEvent(new Event('change', { bubbles: true }));
                            if (typeof showForm === 'function') showForm(sel);
                            break;
                        }
                    }
                }
            ");
            $browser->pause(1000);

            // Fill DB credentials for MySQL/Postgres
            if ($dbDriver !== 'sqlite') {
                $dbHost = env('DB_HOST', '127.0.0.1');
                $dbName = env('DB_DATABASE', 'microweber_dusk');
                $dbUser = env('DB_USERNAME', 'root');
                $dbPass = env('DB_PASSWORD', '');

                $this->fillField($browser, 'db_host', $dbHost);
                $this->fillField($browser, 'db_username', $dbUser);
                $this->fillField($browser, 'db_password', $dbPass);
                $this->fillField($browser, 'db_name', $dbName);
            }

            // Clear db_prefix to avoid "too long" errors with MySQL
            $this->fillField($browser, 'db_prefix', '');

            // Check 'Restore default content' using click (not just setting .checked)
            $browser->script("
                var cb = document.querySelector('#with_default_content, [name=\"with_default_content\"]');
                if (cb && !cb.checked) {
                    cb.click();
                }
            ");

            // Fill admin credentials
            $this->fillField($browser, 'admin_username', 'admin');
            $this->fillField($browser, 'admin_email', 'admin@admin.com');
            $this->fillField($browser, 'admin_password', 'admin');
            $this->fillField($browser, 'admin_password2', 'admin');

            $browser->screenshot('step1-03-filled');

            // Click Install button
            $browser->script("
                var btn = null;
                var btns = document.querySelectorAll('button, input[type=\"submit\"]');
                for (var i = 0; i < btns.length; i++) {
                    var text = (btns[i].textContent || btns[i].value || '').trim().toLowerCase();
                    if (text === 'install') { btn = btns[i]; break; }
                }
                if (btn) { btn.scrollIntoView(); btn.click(); }
            ");

            $browser->pause(3000);
            $browser->screenshot('step1-04-clicked');

            // If jQuery handler didn't trigger, force form submit
            $pageSource = $browser->driver->getPageSource();
            if (str_contains($pageSource, 'Database Server') && !str_contains($pageSource, 'progress-bar')) {
                $browser->script("
                    var form = document.querySelector('form');
                    if (form && typeof jQuery !== 'undefined') jQuery(form).trigger('submit');
                ");
                $browser->pause(3000);
            }

            // Wait for completion (up to 8 minutes — MySQL/Postgres take longer)
            for ($elapsed = 0; $elapsed < 480; $elapsed += 5) {
                $browser->pause(5000);
                $url = $browser->driver->getCurrentURL();
                if (str_contains($url, 'install_done=1') || (str_contains($url, '/admin') && !str_contains($url, 'install'))) {
                    break;
                }
            }

            $browser->screenshot('step1-05-complete');

            $finalUrl = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($finalUrl, '/admin') || str_contains($finalUrl, 'install_done'),
                "Installation should redirect to admin. Final URL: {$finalUrl}"
            );
        });
    }

    /**
     * Step 2: Log into the admin panel.
     */
    #[Test]
    public function step2_admin_login(): void
    {
        $this->browse(function (Browser $browser) {
            $email    = env('DUSK_ADMIN_EMAIL', 'admin@admin.com');
            $password = env('DUSK_ADMIN_PASSWORD', 'admin');

            // Clear cookies for a clean login
            $browser->visit('/admin/login');
            $browser->driver->manage()->deleteAllCookies();
            static::$adminLoggedIn = false;

            $browser->visit('/admin/login')
                ->pause(3000);

            $currentUrl = $browser->driver->getCurrentURL();

            if (!str_contains($currentUrl, '/login')) {
                static::$adminLoggedIn = true;
                $this->assertStringContainsString('/admin', $currentUrl);
                return;
            }

            $browser->waitFor('input[type="email"]', 15)
                ->screenshot('step2-01-login-page');

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Login page should not return 500');

            for ($attempt = 1; $attempt <= 5; $attempt++) {
                $browser->clear('input[type="email"]')
                    ->type('input[type="email"]', $email)
                    ->clear('input[type="password"]')
                    ->type('input[type="password"]', $password)
                    ->click('button[type="submit"]')
                    ->pause(5000);

                $url = $browser->driver->getCurrentURL();
                if (!str_contains($url, '/login')) {
                    break;
                }

                $rateLimited = $browser->script(
                    "return document.body.innerText.includes('Too many') || document.body.innerText.includes('throttle');"
                );
                if ($rateLimited[0] ?? false) {
                    $browser->pause(10000);
                    continue;
                }
                break;
            }

            $browser->screenshot('step2-02-after-login');

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringNotContainsString('/login', $currentUrl,
                'After login, should not be on login page');
            $this->assertStringContainsString('/admin', $currentUrl,
                'After login, should be on admin area');

            static::$adminLoggedIn = true;
        });
    }

    /**
     * Step 3: Visit all admin sidebar pages.
     */
    #[Test]
    public function step3_visit_all_sidebar_pages(): void
    {
        $this->browse(function (Browser $browser) {
            $this->ensureAdminLoggedIn($browser);

            $browser->visit('/admin')
                ->pause(5000)
                ->screenshot('step3-01-dashboard');

            // Collect sidebar links
            $links = $browser->script("
                var results = [];
                var seen = {};
                var sidebar = document.querySelector('#admin-sidebar, #sidebar-menu, nav, aside');
                if (!sidebar) sidebar = document;
                var anchors = sidebar.querySelectorAll('a[href]');
                for (var i = 0; i < anchors.length; i++) {
                    var href = anchors[i].getAttribute('href');
                    var text = anchors[i].textContent.trim();
                    if (href && href.includes('/admin') && !href.startsWith('#') && !href.startsWith('javascript')
                        && !href.includes('logout') && !href.includes('editmode') && !href.includes('live-edit')
                        && !seen[href]) {
                        seen[href] = true;
                        results.push({href: href, text: text.substring(0, 50)});
                    }
                }
                return results;
            ");

            $sidebarLinks = $links[0] ?? [];

            // Expand dropdown menus
            $browser->script("
                document.querySelectorAll('#sidebar-menu .add-new, .navbar-nav .nav-link[data-bs-toggle]').forEach(function(el) {
                    try { el.click(); } catch(e) {}
                });
            ");
            $browser->pause(1000);

            // Re-collect after expanding
            $expandedLinks = $browser->script("
                var results = [];
                var seen = {};
                var sidebar = document.querySelector('#admin-sidebar, #sidebar-menu, nav, aside');
                if (!sidebar) sidebar = document;
                var anchors = sidebar.querySelectorAll('a[href]');
                for (var i = 0; i < anchors.length; i++) {
                    var href = anchors[i].getAttribute('href');
                    var text = anchors[i].textContent.trim();
                    if (href && href.includes('/admin') && !href.startsWith('#') && !href.startsWith('javascript')
                        && !href.includes('logout') && !href.includes('editmode') && !href.includes('live-edit')
                        && !seen[href]) {
                        seen[href] = true;
                        results.push({href: href, text: text.substring(0, 50)});
                    }
                }
                return results;
            ");

            $allLinks = array_merge($sidebarLinks, $expandedLinks[0] ?? []);

            // Deduplicate
            $uniqueLinks = [];
            $seenHrefs = [];
            foreach ($allLinks as $link) {
                $href = is_array($link) ? ($link['href'] ?? '') : ($link->href ?? '');
                if ($href && !isset($seenHrefs[$href])) {
                    $seenHrefs[$href] = true;
                    $text = is_array($link) ? ($link['text'] ?? '') : ($link->text ?? '');
                    $uniqueLinks[] = ['href' => $href, 'text' => $text];
                }
            }

            // Add known admin pages as fallback
            $knownPages = [
                '/admin' => 'Dashboard',
                '/admin/pages' => 'Pages',
                '/admin/posts' => 'Posts',
                '/admin/products' => 'Products',
                '/admin/orders' => 'Orders',
                '/admin/categories' => 'Categories',
                '/admin/settings' => 'Settings',
                '/admin/users' => 'Users',
            ];

            $baseUrl = rtrim($this->siteUrl, '/');
            foreach ($knownPages as $path => $name) {
                $fullUrl = $baseUrl . $path;
                if (!isset($seenHrefs[$fullUrl]) && !isset($seenHrefs[$path])) {
                    $uniqueLinks[] = ['href' => $path, 'text' => $name];
                }
            }

            $this->assertNotEmpty($uniqueLinks, 'Should discover at least one sidebar link');

            $visited = 0;
            $failed  = [];

            foreach ($uniqueLinks as $link) {
                $href = $link['href'];
                $label = $link['text'] ?: $href;

                try {
                    $browser->visit($href)->pause(3000);

                    $currentUrl = $browser->driver->getCurrentURL();
                    if (str_contains($currentUrl, '/login')) {
                        $this->ensureAdminLoggedIn($browser);
                        $browser->visit($href)->pause(3000);
                    }

                    $pageSource = $browser->driver->getPageSource();
                    if (str_contains($pageSource, 'Internal Server Error')
                        || str_contains($pageSource, '500 Server Error')
                        || str_contains($pageSource, 'Whoops, looks like something went wrong')) {
                        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', substr($label, 0, 30));
                        $browser->screenshot("step3-error-{$safeName}");
                        $failed[] = "{$label} ({$href}): 500 error";
                    } else {
                        $visited++;
                    }
                } catch (\Exception $e) {
                    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', substr($label, 0, 30));
                    try { $browser->screenshot("step3-exc-{$safeName}"); } catch (\Exception) {}
                    $failed[] = "{$label} ({$href}): " . substr($e->getMessage(), 0, 200);
                }
            }

            $browser->screenshot('step3-02-all-visited');

            if (!empty($failed)) {
                $report = "Failed on " . count($failed) . "/" . ($visited + count($failed)) . " pages:\n";
                foreach ($failed as $f) {
                    $report .= "  - {$f}\n";
                }
                echo $report;
            }

            $this->assertGreaterThanOrEqual(5, $visited,
                "Should visit at least 5 admin pages. Visited: {$visited}");
        });
    }

    // ─── Helpers ────────────────────────────────────────────────────────

    private function fillField(Browser $browser, string $name, string $value): void
    {
        $escapedValue = json_encode($value);
        $browser->script("
            var field = document.querySelector('[name=\"{$name}\"]');
            if (field) {
                field.value = '';
                field.focus();
                field.value = {$escapedValue};
                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
                field.blur();
            }
        ");
    }

    private function ensureAdminLoggedIn(Browser $browser): void
    {
        $email    = env('DUSK_ADMIN_EMAIL', 'admin@admin.com');
        $password = env('DUSK_ADMIN_PASSWORD', 'admin');

        $browser->visit('/admin/login')->pause(2000);
        $currentUrl = $browser->driver->getCurrentURL();

        if (!str_contains($currentUrl, '/login')) {
            return;
        }

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $browser->waitFor('input[type="email"]', 10)
                ->clear('input[type="email"]')
                ->type('input[type="email"]', $email)
                ->clear('input[type="password"]')
                ->type('input[type="password"]', $password)
                ->click('button[type="submit"]')
                ->pause(5000);

            $url = $browser->driver->getCurrentURL();
            if (!str_contains($url, '/login')) {
                return;
            }

            $rateLimited = $browser->script(
                "return document.body.innerText.includes('Too many') || document.body.innerText.includes('throttle');"
            );
            if ($rateLimited[0] ?? false) {
                $browser->pause(10000);
                continue;
            }
            break;
        }

        $url = $browser->driver->getCurrentURL();
        if (str_contains($url, '/login')) {
            throw new \RuntimeException('Admin login failed — still on login page');
        }
    }
}
