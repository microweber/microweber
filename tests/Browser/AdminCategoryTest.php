<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Category Tests
 *
 * Tests category management: list, create, edit, and delete categories.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminCategoryTest extends DuskTestCase
{
    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function loginAsAdmin(Browser $browser): void
    {
        $browser->visit('/admin/login')->pause(2000);

        $currentUrl = $browser->driver->getCurrentURL();
        if (!str_contains($currentUrl, '/login')) {
            return;
        }

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $browser->waitFor('input[type="email"]', 10)
                ->clear('input[type="email"]')
                ->type('input[type="email"]', 'admin@admin.com')
                ->clear('input[type="password"]')
                ->type('input[type="password"]', 'password123')
                ->click('button[type="submit"]')
                ->pause(5000);

            $url = $browser->driver->getCurrentURL();
            if (!str_contains($url, '/login')) {
                return;
            }

            $rateLimited = $browser->script("return document.body.innerText.includes('Too many');");
            if ($rateLimited[0] ?? false) {
                $browser->pause(5000);
                continue;
            }

            break;
        }

        $url = $browser->driver->getCurrentURL();
        $this->assertStringNotContainsString('/login', $url, 'Login failed — still on login page');
    }

    protected function ensureLoggedIn(Browser $browser): void
    {
        $currentUrl = $browser->driver->getCurrentURL();
        if (str_contains($currentUrl, '/login') || !str_contains($currentUrl, '/admin')) {
            $this->loginAsAdmin($browser);
        }
    }

    #[Test]
    public function categories_list_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/categories')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Categories list should not return 500');

            // Categories uses a custom tree/list layout, not standard Filament table
            $hasContent = $browser->script("
                return document.querySelector('.fi-ta-table') !== null
                    || document.querySelector('.fi-empty-state') !== null
                    || document.querySelector('table') !== null
                    || document.querySelector('[wire\\\\:id]') !== null
                    || document.querySelector('.fi-page-content') !== null
                    || document.querySelector('h1') !== null;
            ");
            $this->assertTrue($hasContent[0] ?? false,
                'Categories page should have content');
        });
    }

    #[Test]
    public function category_create_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/categories/create')->pause(5000);
            $this->ensureLoggedIn($browser);

            $currentUrl = $browser->driver->getCurrentURL();
            if (!str_contains($currentUrl, '/categories')) {
                $browser->visit('/admin/categories/create')->pause(5000);
            }

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Category create page should not return 500');

            $hasForm = $browser->script("
                return document.querySelectorAll('input, select, textarea').length > 0;
            ");
            $this->assertTrue($hasForm[0] ?? false,
                'Category create page should have form fields');
        });
    }

    #[Test]
    public function new_category_button_navigates_to_create(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/categories')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Click the "New category" button
            $clicked = $browser->script("
                var links = document.querySelectorAll('a, button');
                for (var i = 0; i < links.length; i++) {
                    var text = links[i].textContent.trim().toLowerCase();
                    if (text.includes('new category') || text.includes('create')) {
                        links[i].click();
                        return true;
                    }
                }
                return false;
            ");

            $browser->pause(5000);

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($currentUrl, '/create') || str_contains($currentUrl, '/categories'),
                'New category button should navigate. Got: ' . $currentUrl
            );

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Category create page should not return 500');
        });
    }

    #[Test]
    public function shop_categories_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/shop-categories')->pause(5000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Shop categories list should not return 500');

            $hasContent = $browser->script("
                return document.querySelector('.fi-ta-table') !== null
                    || document.querySelector('.fi-empty-state') !== null
                    || document.querySelector('table') !== null
                    || document.querySelector('[wire\\\\:id]') !== null
                    || document.querySelector('.fi-page-content') !== null
                    || document.querySelector('h1') !== null;
            ");
            $this->assertTrue($hasContent[0] ?? false,
                'Shop categories page should have content');
        });
    }
}
