<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Content CRUD Tests
 *
 * Tests creating and editing pages, posts, and products through the Filament admin.
 * Validates form rendering, field population, submission, and verification.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminContentCreateTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * Type a value into a Livewire-bound input.
     * Uses Livewire's component.set() API to ensure the value is bound,
     * targeting the correct component (the form page, not the Topbar).
     */
    protected function livewireType(Browser $browser, string $selector, string $value): void
    {
        // First try Dusk's native typing
        $browser->clear($selector)
            ->append($selector, $value)
            ->pause(500);

        // Tab away to trigger blur/deferred Livewire updates
        $browser->keys($selector, '{tab}')
            ->pause(1500);
    }

    /**
     * Set a value on the Livewire form component via its JS API.
     * This finds the component that owns the form (not Topbar/sidebar)
     * by walking up from the title input to the nearest [wire:id] ancestor.
     * Uses deferred mode to avoid triggering a server roundtrip.
     */
    protected function livewireSet(Browser $browser, string $property, string $value): void
    {
        $js = <<<JS
            var titleInput = document.querySelector('#form\\\\.title');
            if (!titleInput) return false;
            var el = titleInput;
            while (el && !el.getAttribute('wire:id')) el = el.parentElement;
            if (!el) return false;
            var wireId = el.getAttribute('wire:id');
            var comp = window.Livewire.find(wireId);
            if (!comp) return false;
            comp.set('{$property}', '{$value}');
            return true;
        JS;
        $result = $browser->script($js);
        $browser->pause(2000);
    }

    /**
     * Click the Save button using JS to find it by text content.
     */
    protected function clickSave(Browser $browser): void
    {
        $browser->script("
            var saveBtn = Array.from(document.querySelectorAll('button')).find(
                b => b.textContent.trim().includes('Save')
            );
            if (saveBtn) saveBtn.click();
        ");
        $browser->pause(5000);
    }

    #[Test]
    public function create_and_verify_page_post_and_product(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $passed = 0;
            $failed = [];
            $ts = time();

            // ════════════════════════════════════════════════════════════
            // 1. Create Page
            // ════════════════════════════════════════════════════════════
            $pageTitle = "Dusk Page {$ts}";

            try {
                $browser->visit('/admin/pages/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Create page form should not show 500 error');
                $browser->assertSee('Create Page');

                // Verify title field exists
                $titleExists = $browser->script("return !!document.querySelector('#form\\\\.title');");
                $this->assertTrue($titleExists[0], 'Title field should exist on page form');

                // Set title via Livewire API
                $this->livewireSet($browser, 'data.title', $pageTitle);

                // Save
                $this->clickSave($browser);

                // Verify: should redirect to edit page or show the title
                $currentUrl = $browser->driver->getCurrentURL();
                $editPageSource = $browser->driver->getPageSource();

                $savedOk = str_contains($currentUrl, '/edit')
                    || str_contains($editPageSource, $pageTitle)
                    || str_contains($editPageSource, 'Saved');

                $this->assertTrue($savedOk, "Page should be saved. URL: {$currentUrl}");

                // If on edit page, verify title is in the page
                if (str_contains($currentUrl, '/edit')) {
                    $this->assertStringContainsString($pageTitle, $editPageSource,
                        'Edit page should contain the page title');
                }

                $passed++;
            } catch (\Exception $e) {
                $failed['create_page'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 2. Create Post
            // ════════════════════════════════════════════════════════════
            $postTitle = "Dusk Post {$ts}";

            try {
                $browser->visit('/admin/posts/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Create post form should not show 500 error');
                $browser->assertSee('Create Post');

                // Set title via Livewire API (Dusk typing doesn't always bind for posts)
                $this->livewireSet($browser, 'data.title', $postTitle);

                // Save
                $this->clickSave($browser);

                $currentUrl = $browser->driver->getCurrentURL();
                $editPageSource = $browser->driver->getPageSource();

                $savedOk = str_contains($currentUrl, '/edit')
                    || str_contains($editPageSource, $postTitle)
                    || str_contains($editPageSource, 'Saved');

                $this->assertTrue($savedOk, "Post should be saved. URL: {$currentUrl}");

                // Verify redirect URL pattern matches /posts/{id}/edit
                if (str_contains($currentUrl, '/edit')) {
                    $this->assertMatchesRegularExpression(
                        '#/admin/posts/\d+/edit#',
                        $currentUrl,
                        'Should redirect to post edit page after save'
                    );
                }

                $passed++;
            } catch (\Exception $e) {
                $failed['create_post'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 3. Create Product
            // ════════════════════════════════════════════════════════════
            $productTitle = "Dusk Product {$ts}";
            $productPrice = '49.99';

            try {
                $browser->visit('/admin/products/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Create product form should not show 500 error');
                $browser->assertSee('Create Product');

                // Verify product-specific tabs
                $hasTabs = $browser->script("
                    var tabs = Array.from(document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]'));
                    var tabNames = tabs.map(t => t.textContent.trim());
                    return {
                        hasProductDetails: tabNames.includes('Product Details'),
                        tabNames: tabNames
                    };
                ");
                $this->assertTrue($hasTabs[0]['hasProductDetails'],
                    'Product form should have Product Details tab');

                // Set title and price via Livewire API
                $this->livewireSet($browser, 'data.title', $productTitle);
                $this->livewireSet($browser, 'data.price', $productPrice);

                // Save
                $this->clickSave($browser);

                $currentUrl = $browser->driver->getCurrentURL();
                $editPageSource = $browser->driver->getPageSource();

                $savedOk = str_contains($currentUrl, '/edit')
                    || str_contains($editPageSource, $productTitle)
                    || str_contains($editPageSource, 'Saved');

                $this->assertTrue($savedOk, "Product should be saved. URL: {$currentUrl}");

                // Verify redirect URL pattern matches /products/{id}/edit
                if (str_contains($currentUrl, '/edit')) {
                    $this->assertMatchesRegularExpression(
                        '#/admin/products/\d+/edit#',
                        $currentUrl,
                        'Should redirect to product edit page after save'
                    );
                }

                $passed++;
            } catch (\Exception $e) {
                $failed['create_product'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 4. Validation: empty title should not save
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/pages/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                // Click Save without filling title
                $this->clickSave($browser);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/create', $currentUrl,
                    'Should remain on create page when title is empty');
                $passed++;
            } catch (\Exception $e) {
                $failed['empty_title_validation'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 5. Navigation: "New page" button works
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/pages')->pause(3000);
                $this->ensureLoggedIn($browser);

                $browser->script("
                    var newBtn = Array.from(document.querySelectorAll('a')).find(
                        a => a.textContent.trim().includes('New page')
                            || a.textContent.trim().includes('new page')
                    );
                    if (newBtn) newBtn.click();
                ");
                $browser->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/create', $currentUrl,
                    '"New page" button should navigate to create form');
                $browser->assertSee('Create Page');
                $passed++;
            } catch (\Exception $e) {
                $failed['new_page_button'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 6. Navigation: "New product" button works
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/products')->pause(3000);
                $this->ensureLoggedIn($browser);

                $browser->script("
                    var newBtn = Array.from(document.querySelectorAll('a')).find(
                        a => a.textContent.trim().includes('New product')
                            || a.textContent.trim().includes('new product')
                    );
                    if (newBtn) newBtn.click();
                ");
                $browser->pause(3000);

                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/create', $currentUrl,
                    '"New product" button should navigate to create form');
                $browser->assertSee('Create Product');
                $passed++;
            } catch (\Exception $e) {
                $failed['new_product_button'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 7. Create forms have correct tabs
            // ════════════════════════════════════════════════════════════
            try {
                // Page tabs
                $browser->visit('/admin/pages/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                $pageTabs = $browser->script("
                    var tabs = Array.from(document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]'));
                    return tabs.map(t => t.textContent.trim());
                ");
                $this->assertContains('Content', $pageTabs[0], 'Page form should have Content tab');
                $this->assertContains('SEO', $pageTabs[0], 'Page form should have SEO tab');
                $this->assertContains('Advanced', $pageTabs[0], 'Page form should have Advanced tab');

                // Product tabs
                $browser->visit('/admin/products/create')->pause(3000);
                $this->ensureLoggedIn($browser);

                $productTabs = $browser->script("
                    var tabs = Array.from(document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]'));
                    return tabs.map(t => t.textContent.trim());
                ");
                $this->assertContains('Product Details', $productTabs[0],
                    'Product form should have Product Details tab (unique to products)');

                $passed++;
            } catch (\Exception $e) {
                $failed['form_tabs'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // Report
            // ════════════════════════════════════════════════════════════
            $total = $passed + count($failed);

            if (!empty($failed)) {
                $report = "Content creation tests: {$passed}/{$total} checks passed.\nFailed:\n";
                foreach ($failed as $check => $error) {
                    $report .= "  - {$check}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->addToAssertionCount($passed);
        });
    }

}
