<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Content Edit Tests
 *
 * Tests editing pages, posts, and products through the Filament admin.
 * Creates content first, then verifies edit page loads, title persists,
 * title can be updated, and form tabs work correctly.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminContentEditTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    /**
     * Set a value on the Livewire form component via its JS API.
     * Uses deferred mode to avoid triggering a server roundtrip
     * (which can cause validation on unrelated Select fields).
     */
    protected function livewireSet(Browser $browser, string $property, string $value): void
    {
        $escapedValue = addslashes($value);
        $js = <<<JS
            var titleInput = document.querySelector('#form\\\\.title');
            if (!titleInput) return false;
            var el = titleInput;
            while (el && !el.getAttribute('wire:id')) el = el.parentElement;
            if (!el) return false;
            var wireId = el.getAttribute('wire:id');
            var comp = window.Livewire.find(wireId);
            if (!comp) return false;
            comp.set('{$property}', '{$escapedValue}');
            return true;
        JS;
        $browser->script($js);
        $browser->pause(2000);
    }

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

    protected function extractRecordId(string $url): ?string
    {
        if (preg_match('#/admin/\w+/(\d+)/edit#', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    #[Test]
    public function edit_page_post_and_product(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $passed = 0;
            $failed = [];
            $ts = time();

            // ════════════════════════════════════════════════════════════
            // 1. Create and then Edit a Page
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/pages/create')->pause(1500);
                $this->ensureLoggedIn($browser);

                // Verify form loaded properly
                $formReady = $browser->script("return !!document.querySelector('#form\\\\.title');");
                $this->assertTrue($formReady[0], 'Title field should exist on create page form');

                $this->livewireSet($browser, 'data.title', "Edit Test Page {$ts}");

                // Wait for Livewire to process the set
                $browser->pause(2000);

                $this->clickSave($browser);

                // Extra wait for redirect
                $browser->pause(1500);

                $createUrl = $browser->driver->getCurrentURL();

                // If still on create, check for validation errors
                if (str_contains($createUrl, '/create')) {
                    $errors = $browser->script("
                        return Array.from(document.querySelectorAll('.fi-fo-field-wrp-error-message'))
                            .map(e => e.textContent.trim());
                    ");
                    $errorMsg = !empty($errors[0]) ? implode(', ', $errors[0]) : 'no visible errors';
                    $this->assertStringContainsString('/edit', $createUrl,
                        "Page should save and redirect to edit. Validation errors: {$errorMsg}");
                }

                $pageId = $this->extractRecordId($createUrl);
                $this->assertNotNull($pageId, 'Should extract page ID from URL');

                // Visit the edit page fresh
                $browser->visit("/admin/pages/{$pageId}/edit")->pause(1500);
                $this->ensureLoggedIn($browser);

                // Verify edit page loads without errors
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Edit page should not show 500 error');

                // Verify the page title is displayed in browser tab
                $this->assertStringContainsString("Edit Test Page {$ts}", $pageSource,
                    'Edit page should show the page title');

                // Verify the title input has the correct value
                $titleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals("Edit Test Page {$ts}", $titleValue[0],
                    'Title input should contain the page title');

                // Update the title
                $updatedPageTitle = "Updated Page {$ts}";
                $this->livewireSet($browser, 'data.title', $updatedPageTitle);
                $this->clickSave($browser);

                // Verify still on edit page
                $afterSaveUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString("/pages/{$pageId}/edit", $afterSaveUrl,
                    'Should remain on edit page after saving');

                // Verify updated title persisted
                $browser->pause(2000);
                $updatedTitleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals($updatedPageTitle, $updatedTitleValue[0],
                    'Title should be updated after save');

                $passed++;
            } catch (\Exception $e) {
                $failed['edit_page'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 2. Create and then Edit a Post
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/posts/create')->pause(1500);
                $this->ensureLoggedIn($browser);
                $this->livewireSet($browser, 'data.title', "Edit Test Post {$ts}");
                $this->clickSave($browser);

                $createUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/edit', $createUrl, 'Post should save and redirect to edit');

                $postId = $this->extractRecordId($createUrl);
                $this->assertNotNull($postId, 'Should extract post ID from URL');

                // Visit the edit page fresh
                $browser->visit("/admin/posts/{$postId}/edit")->pause(1500);
                $this->ensureLoggedIn($browser);

                // Verify edit page loads without errors
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Edit post page should not show 500 error');

                // Verify the title input has the correct value
                $titleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals("Edit Test Post {$ts}", $titleValue[0],
                    'Title input should contain the post title');

                // Update the title
                $updatedPostTitle = "Updated Post {$ts}";
                $this->livewireSet($browser, 'data.title', $updatedPostTitle);
                $this->clickSave($browser);

                // Verify still on edit page
                $afterSaveUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString("/posts/{$postId}/edit", $afterSaveUrl,
                    'Should remain on edit page after saving');

                // Verify updated title persisted
                $browser->pause(2000);
                $updatedTitleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals($updatedPostTitle, $updatedTitleValue[0],
                    'Post title should be updated after save');

                $passed++;
            } catch (\Exception $e) {
                $failed['edit_post'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 3. Create and then Edit a Product
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/products/create')->pause(1500);
                $this->ensureLoggedIn($browser);
                $this->livewireSet($browser, 'data.title', "Edit Test Product {$ts}");
                $this->livewireSet($browser, 'data.price', '99.99');
                $this->clickSave($browser);

                $createUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString('/edit', $createUrl, 'Product should save and redirect to edit');

                $productId = $this->extractRecordId($createUrl);
                $this->assertNotNull($productId, 'Should extract product ID from URL');

                // Visit the edit page fresh
                $browser->visit("/admin/products/{$productId}/edit")->pause(1500);
                $this->ensureLoggedIn($browser);

                // Verify edit page loads without errors
                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Edit product page should not show 500 error');

                // Verify the title input has the correct value
                $titleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals("Edit Test Product {$ts}", $titleValue[0],
                    'Title input should contain the product title');

                // Verify product tabs exist on edit page
                $hasTabs = $browser->script("
                    var tabs = Array.from(document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]'));
                    var tabNames = tabs.map(t => t.textContent.trim());
                    return tabNames.includes('Product Details');
                ");
                $this->assertTrue($hasTabs[0], 'Edit product should have Product Details tab');

                // Update the title
                $updatedProductTitle = "Updated Product {$ts}";
                $this->livewireSet($browser, 'data.title', $updatedProductTitle);
                $this->clickSave($browser);

                // Verify still on edit page
                $afterSaveUrl = $browser->driver->getCurrentURL();
                $this->assertStringContainsString("/products/{$productId}/edit", $afterSaveUrl,
                    'Should remain on edit page after saving');

                // Verify updated title persisted
                $browser->pause(2000);
                $updatedTitleValue = $browser->script("return document.querySelector('#form\\\\.title')?.value;");
                $this->assertEquals($updatedProductTitle, $updatedTitleValue[0],
                    'Product title should be updated after save');

                $passed++;
            } catch (\Exception $e) {
                $failed['edit_product'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 4. Edit page form tabs are correct
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/pages')->pause(1500);
                $this->ensureLoggedIn($browser);

                // Click the first row in the table
                $browser->script("
                    var row = document.querySelector('table tbody tr');
                    if (row) row.click();
                ");
                $browser->pause(1500);

                $currentUrl = $browser->driver->getCurrentURL();
                if (str_contains($currentUrl, '/edit')) {
                    $pageTabs = $browser->script("
                        var tabs = Array.from(document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]'));
                        return tabs.map(t => t.textContent.trim());
                    ");
                    $this->assertContains('Content', $pageTabs[0], 'Edit page should have Content tab');
                    $this->assertContains('SEO', $pageTabs[0], 'Edit page should have SEO tab');
                    $this->assertContains('Advanced', $pageTabs[0], 'Edit page should have Advanced tab');
                }

                $passed++;
            } catch (\Exception $e) {
                $failed['edit_page_tabs'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 5. Edit page SEO tab loads fields
            // ════════════════════════════════════════════════════════════
            try {
                $browser->visit('/admin/pages')->pause(1500);
                $this->ensureLoggedIn($browser);

                $browser->script("
                    var rows = document.querySelectorAll('table tbody tr');
                    if (rows.length > 0) rows[0].click();
                ");
                $browser->pause(1500);

                $currentUrl = $browser->driver->getCurrentURL();
                if (str_contains($currentUrl, '/edit')) {
                    // Click SEO tab
                    $browser->script("
                        var tabs = document.querySelectorAll('.fi-tabs-item-button, [role=\"tab\"]');
                        tabs.forEach(function(tab) {
                            if (tab.textContent.trim() === 'SEO') tab.click();
                        });
                    ");
                    $browser->pause(2000);

                    // Verify SEO fields are visible
                    $seoFields = $browser->script("
                        return {
                            hasMetaTitle: !!document.querySelector('[id*=\"meta_title\"], [wire\\\\:model*=\"meta_title\"]'),
                            hasMetaDescription: !!document.querySelector('[id*=\"meta_description\"], textarea[id*=\"description\"]'),
                            hasRobots: !!document.querySelector('[id*=\"robots\"]')
                        };
                    ");
                    $hasSeoField = ($seoFields[0]['hasMetaTitle'] ?? false)
                        || ($seoFields[0]['hasMetaDescription'] ?? false)
                        || ($seoFields[0]['hasRobots'] ?? false);
                    $this->assertTrue($hasSeoField, 'SEO tab should show SEO-related fields');
                }

                $passed++;
            } catch (\Exception $e) {
                $failed['edit_seo_tab'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // Report
            // ════════════════════════════════════════════════════════════
            $total = $passed + count($failed);

            if (!empty($failed)) {
                $report = "Content edit tests: {$passed}/{$total} checks passed.\nFailed:\n";
                foreach ($failed as $check => $error) {
                    $report .= "  - {$check}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->addToAssertionCount($passed);
        });
    }
}
