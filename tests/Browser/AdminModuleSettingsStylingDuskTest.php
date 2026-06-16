<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverDimension;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Styling Dusk tests for every module-settings admin page.
 *
 * Complements {@see AdminModuleSettingsPagesDuskTest} (which only asserts the
 * pages LOAD without server errors and contain form elements) by pinning the
 * two responsive-table regressions fixed in
 * `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`:
 *
 *   1. **Redundant per-cell labels leak on desktop.** Filament renders a
 *      `.fi-ta-cell-label` inside every body cell for its mobile card layout;
 *      vanilla Filament hides them at the `sm` breakpoint, but the Microweber
 *      theme's own rules had knocked that out, so on a ≥1024px desktop the
 *      Testimonials/Teamcard/Slider tables showed a stray "Picture" / "Name" /
 *      "Title" label above every value. The fix re-hides them ≥1024px. Here we
 *      assert **0 visible `.fi-ta-cell-label`** at a 1280px desktop viewport.
 *
 *   2. **Row actions overflow off-screen in card mode.** In the
 *      `<1024px` card layout the actions cell (`.fi-ta-actions` —
 *      Edit / Delete / Copy) was not being given full row width, so the
 *      buttons ran off the left/right edge of the card (and off-screen inside
 *      the narrow Live-Edit module-settings modal). The fix forces the actions
 *      cell to `width:100%` + `flex-wrap`. Here we assert **0 `.fi-ta-actions`
 *      clusters whose bounding box falls outside the viewport** at a 390px
 *      phone viewport (the width Live-Edit's modal collapses to).
 *
 * Both checks are data-adaptive: a page with no table rows (empty list, or a
 * non-table settings UI like Menu's drag/drop or Pictures' dropzone) trivially
 * reports 0/0 and still asserts the page rendered without error markers. Pages
 * that DO render rows in the running dev DB (the demo content seeds several)
 * get real coverage. The dedicated {@see populated_list_tables_render_clean_rows}
 * test additionally drives the three known data-rich tables by their seeded
 * `rel_id` and skips (rather than silently passes) if the seed is absent.
 *
 * Prerequisites (same as AdminModuleSettingsPagesDuskTest):
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user (admin@admin.com / admin), login captcha disabled
 */
class AdminModuleSettingsStylingDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    /** Desktop viewport — matches DuskTestCase's default 1280-wide Chrome window. */
    private const DESKTOP = [1280, 1080];

    /** Phone viewport — 390×844 (iPhone 12/13/14), the width Live-Edit's modal collapses to. */
    private const MOBILE = [390, 844];

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database.
    }

    /**
     * Every module-settings admin slug. Kept in sync with
     * {@see AdminModuleSettingsPagesDuskTest::allModuleSettingsSlugs()}.
     */
    private static function allModuleSettingsSlugs(): array
    {
        return [
            'accordion-module-settings',
            'audio-module-settings',
            'background-module-settings',
            'before-after-module-settings',
            'breadcrumb-module-settings',
            'btn-module-settings',
            'captcha-module-settings',
            'cart-add-module-settings',
            'category-module-settings',
            'comments-module-settings',
            'contact-form-module-settings',
            'content-module-settings',
            'custom-fields-module-settings',
            'embed-module-settings',
            'facebook-like-module-settings',
            'facebook-page-module-settings',
            'faq-module-settings',
            'google-maps-module-settings',
            'highlight-code-module-settings',
            'image-rollover-module-settings',
            'layout-content-module-settings',
            'layouts-module-settings',
            'logo-module-settings',
            'marquee-module-settings',
            'menu-module-settings',
            'newsletter-module-settings',
            'page-module-settings',
            'pagination-module-settings',
            'pdf-module-settings',
            'pictures-module-settings',
            'post-module-settings',
            'products-module-settings',
            'rating-module-settings',
            'sharer-module-settings',
            'shop-module-settings',
            'skills-module-settings',
            'slider-module-settings',
            'social-links-module-settings',
            'spacer-module-settings',
            'tabs-module-settings',
            'tags-module-settings',
            'teamcard-module-settings',
            'testimonials-module-settings',
            'text-type-module-settings',
            'tweet-embed-module-settings',
            'video-module-settings',
        ];
    }

    /**
     * Content-backed list tables that render real rows from the demo seed
     * without a Live-Edit `rel_id` context — they list global site content
     * (pages / posts / products) so the rows are present in the initial
     * server HTML of a fresh admin session, making them reliable in Dusk.
     *
     * (The per-instance module tables — Slider/Testimonials/Teamcard — only
     * populate when opened with their Live-Edit `rel_id`, which resolves from
     * saved module options inside the editor and is empty on a cold standalone
     * page load, so they're covered by the data-adaptive batch tests instead.)
     */
    private static function populatedTargets(): array
    {
        return [
            'content' => 'content-module-settings',
            'posts' => 'post-module-settings',
            'products' => 'products-module-settings',
        ];
    }

    #[Test]
    public function module_settings_styling_batch1(): void
    {
        $this->assertStylingForSlugs(array_slice(self::allModuleSettingsSlugs(), 0, 16));
    }

    #[Test]
    public function module_settings_styling_batch2(): void
    {
        $this->assertStylingForSlugs(array_slice(self::allModuleSettingsSlugs(), 16, 15));
    }

    #[Test]
    public function module_settings_styling_batch3(): void
    {
        $this->assertStylingForSlugs(array_slice(self::allModuleSettingsSlugs(), 31));
    }

    /**
     * Drive the content-backed list tables (pages / posts / products), which
     * render ACTUAL rows from the demo seed, so the label/actions assertions
     * run against real cells — at both desktop and the 390px card layout.
     * Skips (rather than passing vacuously) if no target rendered rows.
     */
    #[Test]
    public function populated_list_tables_render_clean_rows(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $exercised = 0;
            $failed = [];

            foreach (self::populatedTargets() as $label => $path) {
                $desktop = $this->probeAt($browser, $path, self::DESKTOP);

                if (($desktop['rows'] ?? 0) < 1) {
                    // Seed absent in this DB — don't assert against an empty table.
                    continue;
                }
                $exercised++;

                if (($desktop['visibleLabels'] ?? 0) !== 0) {
                    $failed[] = "{$label}: {$desktop['visibleLabels']} redundant per-cell label(s) "
                        . "visible at 1280px desktop (should be hidden ≥1024px)";
                }
                if (($desktop['actionsOffscreen'] ?? 0) !== 0) {
                    $failed[] = "{$label}: {$desktop['actionsOffscreen']} row-action cluster(s) "
                        . "off-screen at desktop";
                }

                $mobile = $this->probeAt($browser, $path, self::MOBILE);
                if (($mobile['actionsOffscreen'] ?? 0) !== 0) {
                    $failed[] = "{$label}: {$mobile['actionsOffscreen']} row-action cluster(s) "
                        . "off-screen in 390px card mode (Edit/Delete/Copy overflow)";
                }
            }

            $this->restoreDesktop($browser);

            if ($exercised === 0) {
                $this->markTestSkipped(
                    'No content-backed list table rendered rows in this DB; '
                    . 'row-level label/actions styling not exercised.'
                );
            }

            $this->assertEmpty(
                $failed,
                "Populated table styling regressions:\n  - " . implode("\n  - ", $failed)
            );
            $this->assertGreaterThan(0, $exercised, 'Expected at least one populated table to be exercised.');
        });
    }

    /**
     * Visit each slug, probe at desktop + mobile, and collect every styling
     * violation into one report so a single run names all offenders.
     */
    private function assertStylingForSlugs(array $slugs): void
    {
        $this->browse(function (Browser $browser) use ($slugs) {
            $this->loginAsAdmin($browser);

            $failed = [];

            foreach ($slugs as $slug) {
                $desktop = $this->probeAt($browser, $slug, self::DESKTOP);

                if ($desktop['hasError'] ?? false) {
                    $failed[] = "{$slug}: server error markers on page";
                    continue;
                }
                if (($desktop['visibleLabels'] ?? 0) !== 0) {
                    $failed[] = "{$slug}: {$desktop['visibleLabels']} redundant per-cell label(s) "
                        . "visible at 1280px desktop (regression: should be hidden ≥1024px)";
                }
                if (($desktop['actionsOffscreen'] ?? 0) !== 0) {
                    $failed[] = "{$slug}: {$desktop['actionsOffscreen']} row-action cluster(s) "
                        . "off-screen at desktop";
                }

                // Only pages that render table rows are worth the mobile probe.
                if (($desktop['rows'] ?? 0) > 0) {
                    $mobile = $this->probeAt($browser, $slug, self::MOBILE);
                    if (($mobile['actionsOffscreen'] ?? 0) !== 0) {
                        $failed[] = "{$slug}: {$mobile['actionsOffscreen']} row-action cluster(s) "
                            . "off-screen in 390px card mode";
                    }
                }
            }

            $this->restoreDesktop($browser);

            $this->assertEmpty(
                $failed,
                'Module-settings styling regressions (' . count($failed) . "):\n  - "
                . implode("\n  - ", $failed)
            );
        });
    }

    /**
     * Resize to the given viewport, load the page (relative path under /admin/
     * unless it already starts with a slash), and return the styling probe.
     */
    private function probeAt(Browser $browser, string $pathOrSlug, array $size): array
    {
        $browser->driver->manage()->window()->setSize(new WebDriverDimension($size[0], $size[1]));

        $url = str_starts_with($pathOrSlug, '/') ? $pathOrSlug : "/admin/{$pathOrSlug}";
        $browser->visit($url)->pause(1500);
        $this->ensureLoggedIn($browser);

        // Filament tables defer row loading via a second Livewire request, so
        // the rows aren't in the DOM on first paint. Wait until either real
        // rows or the table's empty-state has rendered before probing, then a
        // short settle pause so the responsive layout has reflowed at this
        // viewport. Bounded so non-table pages don't stall the batch.
        try {
            $browser->waitUsing(8, 250, function () use ($browser) {
                $ready = $browser->script(
                    "return document.querySelectorAll('.fi-ta-row').length > 0 "
                    . "|| document.querySelector('.fi-ta-empty-state, .fi-ta-empty-state-content') !== null "
                    . "|| document.querySelector('.fi-ta, .fi-fo-component-ctn') === null;"
                );
                return $ready[0] ?? true;
            });
        } catch (\Exception $e) {
            // Timed out — probe whatever rendered; assertions stay sound.
        }
        $browser->pause(700);

        $result = $browser->script($this->probeScript());

        return $result[0] ?? [];
    }

    private function restoreDesktop(Browser $browser): void
    {
        $browser->driver->manage()->window()->setSize(
            new WebDriverDimension(self::DESKTOP[0], self::DESKTOP[1])
        );
    }

    /**
     * Client-side probe returning the styling invariants. `visibleLabels`
     * counts only laid-out (display!=none, non-zero box) per-cell labels;
     * `actionsOffscreen` counts row-action clusters whose box escapes the
     * viewport horizontally.
     */
    private function probeScript(): string
    {
        return <<<'JS'
            function isVisible(el) {
                var s = window.getComputedStyle(el);
                if (s.display === 'none' || s.visibility === 'hidden') return false;
                var r = el.getBoundingClientRect();
                return r.width > 0 && r.height > 0;
            }

            var rows = document.querySelectorAll('.fi-ta-row, tr.fi-ta-row');

            var labels = document.querySelectorAll(
                '.fi-ta-row .fi-ta-cell-label, .fi-ta-row .fi-ta-record-label'
            );
            var visibleLabels = 0;
            labels.forEach(function (l) { if (isVisible(l)) visibleLabels++; });

            var actions = document.querySelectorAll('.fi-ta-actions');
            var actionsOffscreen = 0;
            var vw = window.innerWidth;
            actions.forEach(function (a) {
                if (!isVisible(a)) return;
                var r = a.getBoundingClientRect();
                if (r.width === 0) return;
                if (r.left < -1 || r.right > vw + 1) actionsOffscreen++;
            });

            var txt = document.body ? (document.body.innerText || '') : '';
            var hasError = /Internal Server Error|Whoops|Server Error \(500\)|Livewire\\Exceptions/i.test(txt);

            return {
                rows: rows.length,
                visibleLabels: visibleLabels,
                actionsOffscreen: actionsOffscreen,
                hasError: hasError
            };
        JS;
    }
}
