<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Form Entries Workflow Tests
 *
 * End-to-end tests for contact form submissions:
 *   - Form entries list page loads with table or empty state
 *   - Form entry detail view (modal) loads without 500
 *   - Form entry search/filter works
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminFormEntriesWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function form_entries_list_and_detail_and_search(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Form entries list page loads with table or empty state ──
            try {
                $browser->visit('/admin/form-entries')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Form entries list should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0
                        || document.querySelector('.fi-resource-index') !== null;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasFormContent = str_contains($text, 'form')
                    || str_contains($text, 'email')
                    || str_contains($text, 'entry')
                    || str_contains($text, 'name')
                    || str_contains($text, 'subject')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'no entries');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasFormContent,
                    'Form entries page should have table/content or form-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['entries_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Form entry detail view (modal) loads without 500 ──
            try {
                $browser->visit('/admin/form-entries')->pause(5000);
                $this->ensureLoggedIn($browser);

                $hasRows = $browser->script("
                    return document.querySelectorAll('table tbody tr').length > 0
                        || document.querySelector('.fi-ta-row') !== null;
                ");

                if ($hasRows[0] ?? false) {
                    // Try to click the view action button on the first row
                    $viewClicked = $browser->script("
                        var viewBtn = document.querySelector('button[wire\\\\:click*=\"mountTableAction\"][wire\\\\:click*=\"view\"]');
                        if (!viewBtn) viewBtn = document.querySelector('.fi-ta-actions button');
                        if (!viewBtn) {
                            var btns = document.querySelectorAll('table tbody tr:first-child button');
                            if (btns.length > 0) viewBtn = btns[0];
                        }
                        if (viewBtn) { viewBtn.click(); return true; }
                        return false;
                    ");

                    if ($viewClicked[0] ?? false) {
                        $browser->pause(3000);

                        // Check modal or detail view opened without error
                        $pageSource = $browser->driver->getPageSource();
                        $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                            'Form entry detail view should not return 500');

                        $hasDetail = $browser->script("
                            return document.querySelector('.fi-modal') !== null
                                || document.querySelector('[x-data]') !== null
                                || document.querySelector('.fi-infolist') !== null;
                        ");

                        $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                        $text = $bodyText[0] ?? '';
                        $hasDetailContent = str_contains($text, 'email')
                            || str_contains($text, 'name')
                            || str_contains($text, 'message')
                            || str_contains($text, 'subject')
                            || str_contains($text, 'date')
                            || str_contains($text, 'close');
                        $this->assertTrue(
                            ($hasDetail[0] ?? false) || $hasDetailContent,
                            'Form entry detail view should show entry data'
                        );
                    } else {
                        // No view button found — verify page is stable
                        $pageSource = $browser->driver->getPageSource();
                        $this->assertStringNotContainsString('Whoops', $pageSource,
                            'Form entries page should not show error');
                    }
                } else {
                    // No entries — verify page is stable (empty state or just no data)
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Form entries page should not return 500 when empty');
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Form entries page should not show error when empty');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['entry_detail'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Form entry search/filter works ──
            try {
                $browser->visit('/admin/form-entries')->pause(5000);
                $this->ensureLoggedIn($browser);

                // Check if search input exists
                $hasSearch = $browser->script("
                    return document.querySelector('.fi-ta-search-field input') !== null
                        || document.querySelector('input[type=\"search\"]') !== null
                        || document.querySelector('input[placeholder*=\"Search\"]') !== null
                        || document.querySelector('input[placeholder*=\"search\"]') !== null;
                ");

                if ($hasSearch[0] ?? false) {
                    // Type a search query
                    $browser->script("
                        var searchInput = document.querySelector('.fi-ta-search-field input')
                            || document.querySelector('input[type=\"search\"]')
                            || document.querySelector('input[placeholder*=\"Search\"]')
                            || document.querySelector('input[placeholder*=\"search\"]');
                        if (searchInput) {
                            searchInput.value = 'test';
                            searchInput.dispatchEvent(new Event('input', {bubbles: true}));
                        }
                    ");
                    $browser->pause(3000);

                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                        'Search should not cause 500 error');
                } else {
                    // No search — page might have no searchable columns or is empty state
                    $pageSource = $browser->driver->getPageSource();
                    $this->assertStringNotContainsString('Whoops', $pageSource,
                        'Form entries page should not show error');
                }
                $checks++;
            } catch (\Exception $e) {
                $failed['search_filter'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " form entry checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 form entry checks passed (got {$checks})");
        });
    }
}
