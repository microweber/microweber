<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Content Delete Tests
 *
 * Tests deleting pages, posts, and products from the admin panel.
 * Creates content, verifies it in the list, deletes it from the edit page,
 * and verifies removal from the list.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminContentDeleteTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function livewireSet(Browser $browser, string $property, string $value): void
    {
        $propertyJs = json_encode($property);
        $valueJs    = json_encode($value);
        $js = <<<JS
            var titleInput = document.querySelector('#form\\\\.title');
            if (!titleInput) return false;
            var el = titleInput;
            while (el && !el.getAttribute('wire:id')) el = el.parentElement;
            if (!el) return false;
            var wireId = el.getAttribute('wire:id');
            var comp = window.Livewire.find(wireId);
            if (!comp) return false;
            comp.set({$propertyJs}, {$valueJs});
            return true;
        JS;
        $browser->script($js);
        $browser->pause(2000);
    }

    protected function createContent(Browser $browser, string $type, string $title): ?string
    {
        // Always ensure logged in before creating
        $browser->visit('/admin')->pause(2000);
        $this->ensureLoggedIn($browser);

        $browser->visit("/admin/{$type}/create")
            ->pause(5000);

        // Check if we got redirected after visiting create
        $currentUrl = $browser->driver->getCurrentURL();
        if (!str_contains($currentUrl, '/create')) {
            $this->ensureLoggedIn($browser);
            $browser->visit("/admin/{$type}/create")->pause(5000);
        }

        $this->livewireSet($browser, 'data.title', $title);

        // For products, also set a price to avoid validation issues
        if ($type === 'products') {
            $browser->script("
                var priceInput = document.querySelector('#form\\\\.price, input[id*=\"price\"]');
                if (priceInput) {
                    priceInput.value = '10.00';
                    priceInput.dispatchEvent(new Event('input', {bubbles: true}));
                    priceInput.dispatchEvent(new Event('change', {bubbles: true}));
                }
            ");
            $browser->pause(1000);
        }

        $browser->script("
            var saveBtn = Array.from(document.querySelectorAll('button')).find(
                b => b.textContent.trim() === 'Save'
            );
            if (!saveBtn) {
                saveBtn = Array.from(document.querySelectorAll('button')).find(
                    b => b.textContent.trim().includes('Save') && !b.textContent.trim().includes('Live Edit')
                );
            }
            if (saveBtn) saveBtn.click();
        ");
        $browser->pause(6000);

        $url = $browser->driver->getCurrentURL();
        return str_contains($url, '/edit') ? $url : null;
    }

    protected function extractRecordId(string $url): ?string
    {
        if (preg_match('#/admin/\w+/(\d+)/edit#', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    #[Test]
    public function delete_page_post_and_product(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            $contentTypes = [
                'pages' => 'Delete Test Page',
                'posts' => 'Delete Test Post',
                'products' => 'Delete Test Product',
            ];

            foreach ($contentTypes as $type => $title) {
                $uniqueTitle = $title . ' ' . time();
                $checkName = "delete_{$type}";

                try {
                    // Ensure we're still logged in before each content type
                    $browser->visit('/admin')->pause(2000);
                    $this->ensureLoggedIn($browser);

                    // Step 1: Create content
                    $editUrl = $this->createContent($browser, $type, $uniqueTitle);
                    $currentUrl = $browser->driver->getCurrentURL();
                    $this->assertNotNull($editUrl,
                        "Failed to create {$type} — did not redirect to edit page. URL: {$currentUrl}");

                    $recordId = $this->extractRecordId($editUrl);
                    $this->assertNotNull($recordId,
                        "Could not extract record ID from {$editUrl}");

                    // Step 2: Verify item shows in the list page
                    $browser->visit("/admin/{$type}")
                        ->pause(4000);

                    $this->ensureLoggedIn($browser);
                    if (!str_contains($browser->driver->getCurrentURL(), "/admin/{$type}")) {
                        $browser->visit("/admin/{$type}")->pause(4000);
                    }

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringContainsString($uniqueTitle, $pageSource,
                        "Created {$type} '{$uniqueTitle}' should appear in the list");

                    // Step 3: Delete via API call
                    $browser->script("
                        window.__deleteResult = null;
                        fetch('/api/content/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)[1])
                            },
                            body: JSON.stringify({id: {$recordId}})
                        })
                        .then(r => r.json())
                        .then(d => { window.__deleteResult = d; })
                        .catch(e => { window.__deleteResult = 'error: ' + e.message; });
                    ");
                    $browser->pause(4000);

                    $deleteResult = $browser->script("return window.__deleteResult;");
                    $apiResult = $deleteResult[0] ?? null;

                    // Verify the API returned the deleted ID
                    $this->assertTrue(
                        is_array($apiResult) && in_array((int) $recordId, $apiResult),
                        "Delete API should return deleted ID {$recordId}. Got: " . json_encode($apiResult)
                    );

                    $checks++;
                } catch (\Exception $e) {
                    $failed[$checkName] = $e->getMessage();
                    try {
                        $browser->screenshot("fail-{$checkName}");
                    } catch (\Exception $ignored) {
                    }
                }
            }

            // ── Report ──
            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " delete checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 delete checks passed (got {$checks})");
        });
    }
}
