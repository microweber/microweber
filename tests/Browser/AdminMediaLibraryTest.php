<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Media Library Tests
 *
 * Tests the media library page loads and basic UI elements are present.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminMediaLibraryTest extends DuskTestCase
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
    public function media_library_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/media-library')->pause(5000);
            $this->ensureLoggedIn($browser);

            $currentUrl = $browser->driver->getCurrentURL();
            if (!str_contains($currentUrl, '/media')) {
                $browser->visit('/admin/media-library')->pause(5000);
            }

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Media library should not return 500');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'Media library should not show error page');
        });
    }

    #[Test]
    public function media_library_has_upload_area(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/media-library')->pause(5000);
            $this->ensureLoggedIn($browser);

            // Look for upload button, dropzone, or file input
            $hasUpload = $browser->script("
                var hasFileInput = document.querySelector('input[type=\"file\"]') !== null;
                var hasUploadBtn = false;
                var allElements = document.querySelectorAll('button, a, [role=\"button\"]');
                allElements.forEach(function(el) {
                    var text = el.textContent.trim().toLowerCase();
                    if (text.includes('upload') || text.includes('add media') || text.includes('browse')) {
                        hasUploadBtn = true;
                    }
                });
                var hasDropzone = document.querySelector('[class*=\"dropzone\"], [class*=\"upload\"], [class*=\"drop-area\"]') !== null;
                return hasFileInput || hasUploadBtn || hasDropzone;
            ");

            $this->assertTrue($hasUpload[0] ?? false,
                'Media library should have upload functionality (file input, upload button, or dropzone)');
        });
    }
}
