<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-8 palette-state round-trip guard: cycling
 * `options.current_template` Bootstrap → Big2 → Bootstrap must NOT
 * clobber the color-palette custom CSS for pages whose
 * `content.active_site_template` pins Bootstrap.
 *
 * This piggybacks on the Phase-6 template-switch regression harness
 * ({@see LiveEditTemplateSwitchBackToBootstrapNoStateLeakTest}) — it
 * already proves body classes + skin markers survive the option
 * churn. The new assertion here is narrower: **the palette vars on
 * `:root` keep resolving to the pack's declared values through the
 * entire bounce**.
 *
 * Why this matters:
 *   Palette customizations are persisted as the active template's
 *   custom CSS (see {@see \MicroweberPackages\Template\Adapters\TemplateCustomCss}).
 *   When a page pins `active_site_template=Bootstrap`, a guest visit
 *   must load Bootstrap's custom CSS regardless of what the site-
 *   wide `options.current_template` happens to be at that moment. A
 *   regression where:
 *     - the public controller reads the site-wide option instead of
 *       the page-level override when picking a custom CSS file, OR
 *     - the option-cache invalidation flushes the wrong bucket,
 *       causing the custom CSS URL to resolve to Big2's (empty)
 *       namespace,
 *   would surface here as missing/blanked `--mw-*` vars on `:root`.
 *
 * Flow:
 *   1. {@see ColorPaletteFactory::make()} seeds a Bootstrap-pinned
 *      page. The factory also asserts `options.current_template=
 *      Bootstrap` via `ensureBootstrapActive()`.
 *   2. Apply `neon-night` in live-edit (high-contrast pack — distinct
 *      enough from Bootstrap's default `#f4a261`/`#212529` tokens that
 *      a fallback re-paint would be instantly visible), then
 *      `saveLiveEdit()` to persist as Bootstrap's custom CSS.
 *   3. Logout to guest, drop cookies.
 *   4. Phase A (baseline, current_template=Bootstrap): visit the page.
 *      Assert every pack property is present on `:root` — proves the
 *      custom CSS save completed correctly.
 *   5. Phase B (current_template=Big2, page still pinned Bootstrap):
 *      flip only the option via {@see save_option()} (which also
 *      nukes the repository + cache-manager caches), re-visit, and
 *      assert the same palette vars still resolve on `:root`. Body
 *      class is also checked to contain "Bootstrap" — if it flipped to
 *      "Big2", the page-level override is broken and the palette
 *      assertion would be meaningless.
 *   6. Phase C (round-trip back to Bootstrap): flip the option back,
 *      re-visit, assert palette vars persist AND body class is
 *      Bootstrap (no residual "Big2" class).
 *   7. `finally`: unconditionally restore
 *      `options.current_template = Bootstrap` so a mid-test failure
 *      can't leave the dev server half-switched.
 *
 * Why we save via the cache-aware path:
 *   {@see \MicroweberPackages\Option\OptionManager::save} (reached
 *   through `save_option()`) invalidates both the repository's in-
 *   memory cache and the file-backed `cache_manager` 'options' group.
 *   A raw `DB::update(...)` would be invisible to the artisan-serve
 *   worker on the next request because
 *   {@see \MicroweberPackages\Option\Repositories\OptionRepository::getOptionsByGroup}
 *   wraps reads in `cacheCallback()`. The helper {@see setCurrentTemplate}
 *   here mirrors the pattern used by the Phase-6 regression harness
 *   so both tests exercise the same option-write code path.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditColorPaletteTemplateSwitchRoundTripTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_SLUG = 'neon-night';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function palette_survives_current_template_round_trip_when_page_pins_bootstrap(): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_SLUG,
            $packs,
            "Representative palette '" . self::PALETTE_SLUG
            . "' must be discoverable on disk"
        );
        $packProperties = $packs[self::PALETTE_SLUG]['properties'];
        $this->assertNotEmpty(
            $packProperties,
            "Pack '" . self::PALETTE_SLUG . "' must declare at least one "
            . 'CSS variable'
        );

        $fixture = ColorPaletteFactory::make(
            'Palette template-switch round-trip'
        );

        $this->browse(function (Browser $browser) use ($fixture, $packProperties) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            // Apply the pack and save so the template-custom-CSS disk
            // write lands before we start bouncing the option.
            $this->clickPalette($browser, self::PALETTE_SLUG);
            $this->assertPaletteApplied($browser, $packProperties);

            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            $this->logoutToGuest($browser);

            try {
                // Phase A — baseline (option=Bootstrap, page=Bootstrap).
                $this->assertSame(
                    'Bootstrap',
                    $this->readCurrentTemplateOption(),
                    'Baseline precondition: options.current_template must '
                    . 'be "Bootstrap" before the round-trip. Factory '
                    . 'ensureBootstrapActive() is supposed to guarantee this.'
                );
                $this->visitPublic($browser, $fixture->slug);

                $bodyBaseline = $this->readBodyClasses($browser);
                $this->assertStringContainsString(
                    'Bootstrap',
                    $bodyBaseline,
                    'Baseline public render must tag <body> with '
                    . '"Bootstrap" — if this fails, the fixture is broken '
                    . 'and the later palette assertions would be testing '
                    . "a page that isn't pinned where we think it is. "
                    . 'Got body classes: ' . json_encode($bodyBaseline)
                );
                $this->assertPublicRootHasPaletteVars(
                    $browser,
                    $packProperties,
                    'baseline (Bootstrap)',
                    $fixture->slug
                );

                // Phase B — flip only the global option to Big2.
                $this->setCurrentTemplate('Big2');
                $this->assertSame(
                    'Big2',
                    $this->readCurrentTemplateOption(),
                    'Mid-flight precondition: options.current_template '
                    . 'must actually be "Big2" after the save — otherwise '
                    . 'the bounce is a no-op and the preservation check '
                    . 'would be meaningless.'
                );
                $this->visitPublic($browser, $fixture->slug);

                $bodyMid = $this->readBodyClasses($browser);
                $this->assertStringContainsString(
                    'Bootstrap',
                    $bodyMid,
                    'Mid-flight (options.current_template=Big2, '
                    . 'page.active_site_template=Bootstrap): the public '
                    . 'render must still carry "Bootstrap" as a body '
                    . 'class because the page-level override wins over '
                    . 'the option. A "Big2"-only body class here would '
                    . 'indicate the resolver ignored the page pin. Got: '
                    . json_encode($bodyMid)
                );
                $this->assertPublicRootHasPaletteVars(
                    $browser,
                    $packProperties,
                    'mid-flight (option=Big2, page pinned Bootstrap)',
                    $fixture->slug
                );

                // Phase C — round-trip back to Bootstrap.
                $this->setCurrentTemplate('Bootstrap');
                $this->assertSame(
                    'Bootstrap',
                    $this->readCurrentTemplateOption(),
                    'Round-trip precondition: options.current_template '
                    . 'must be back to "Bootstrap" — a regression in the '
                    . 'setter would otherwise make the final preservation '
                    . 'assertion testing the wrong option value.'
                );
                $this->visitPublic($browser, $fixture->slug);

                $bodyPost = $this->readBodyClasses($browser);
                $this->assertStringContainsString(
                    'Bootstrap',
                    $bodyPost,
                    'Post round-trip: body classes must contain '
                    . '"Bootstrap" again. Got: ' . json_encode($bodyPost)
                );
                $this->assertStringNotContainsString(
                    'Big2',
                    $bodyPost,
                    'Post round-trip: body classes must NOT still carry '
                    . '"Big2" — a leak here means the template-adapter '
                    . 'cached the mid-flight value across the restore '
                    . 'write. Got: ' . json_encode($bodyPost)
                );
                $this->assertPublicRootHasPaletteVars(
                    $browser,
                    $packProperties,
                    'post round-trip (Bootstrap restored)',
                    $fixture->slug
                );
            } finally {
                // Restore the global option unconditionally so a
                // mid-test failure can't leave subsequent tests
                // (palette, skin-matrix, etc.) observing a dev server
                // pointed at the non-existent "Big2" template.
                $this->setCurrentTemplate('Bootstrap');
            }
        });
    }

    /**
     * Snapshot `document.documentElement`'s computed `--mw-*` custom
     * properties on the current public page and assert every property
     * the pack declared is present and matches (hex↔rgb normalized).
     *
     * The `$phase` label is interpolated into every failure message so
     * a regression at the baseline vs. mid-flight vs. post-round-trip
     * phase is immediately attributable without reading the stack.
     *
     * @param array<string, string> $packProperties
     */
    private function assertPublicRootHasPaletteVars(
        Browser $browser,
        array $packProperties,
        string $phase,
        string $slug
    ): void {
        $result = $browser->script(
            "try {
                var styles = getComputedStyle(document.documentElement);
                var out = {};
                for (var i = 0; i < styles.length; i++) {
                    var prop = styles[i];
                    if (prop && prop.indexOf('--mw-') === 0) {
                        out[prop] = (styles.getPropertyValue(prop) || '').trim();
                    }
                }
                return out;
            } catch (e) {
                return {};
            }"
        );

        $actual = is_array($result[0] ?? null) ? $result[0] : [];
        $this->assertNotEmpty(
            $actual,
            "[{$phase}] /{$slug} must expose --mw-* custom properties "
            . 'on :root. An empty snapshot means either the template '
            . 'custom CSS never loaded or the template changed to one '
            . 'that declares no palette vars.'
        );

        foreach ($packProperties as $prop => $expectedValue) {
            $this->assertArrayHasKey(
                $prop,
                $actual,
                "[{$phase}] Public :root on /{$slug} is missing '{$prop}'. "
                . 'Template custom CSS did not survive the phase — either '
                . 'the save was reverted or the template-switch stripped '
                . 'the custom CSS association for this page.'
            );

            $exp = $this->normalizeCssColor((string)$expectedValue);
            $got = $this->normalizeCssColor((string)$actual[$prop]);

            $this->assertSame(
                $exp,
                $got,
                "[{$phase}] Public :root '{$prop}' on /{$slug} expected "
                . "'{$expectedValue}' got '{$actual[$prop]}'. A default-"
                . 'template value here means the custom CSS file '
                . 'resolved under the wrong template namespace after '
                . 'the option flip.'
            );
        }
    }

    private function visitPublic(Browser $browser, string $slug): void
    {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2000);
    }

    /**
     * Cache-aware option write. `save_option()` delegates to
     * {@see \MicroweberPackages\Option\OptionManager::save}, which
     * invalidates both the repository cache and the cache-manager
     * 'options' group. Belt-and-suspenders extra deletes below guard
     * against a partial-invalidation regression — the Phase-6 harness
     * uses the same pattern.
     */
    private function setCurrentTemplate(string $name): void
    {
        save_option('current_template', $name, 'template');

        try {
            $app = app();
            if (isset($app->cache_manager) && method_exists($app->cache_manager, 'delete')) {
                $app->cache_manager->delete('options');
                $app->cache_manager->delete('options/template');
            }
            if (isset($app->option_repository) && method_exists($app->option_repository, 'clearCache')) {
                $app->option_repository->clearCache();
            }
        } catch (\Throwable) {
            // Best-effort — save_option() already nuked the relevant
            // buckets; the teardown trait re-runs it anyway.
        }
    }

    private function readCurrentTemplateOption(): string
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();
        return $row ? (string)$row->option_value : '';
    }

    private function readBodyClasses(Browser $browser): string
    {
        $res = $browser->script("
            var body = document.body;
            return body ? (body.getAttribute('class') || '') : '__NO_BODY__';
        ");
        $value = $res[0] ?? '';
        return is_string($value) ? $value : '';
    }

    /**
     * Drop the admin session before the public visits. Matches the
     * logout flow used by the Phase-6 harness so the two tests share
     * the same cache-bleed rationale.
     */
    private function logoutToGuest(Browser $browser): void
    {
        $browser->visit('/');
        $browser->pause(500);
        $browser->script("
            try {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': decodeURIComponent(
                            (document.cookie.match(/XSRF-TOKEN=([^;]+)/) || ['',''])[1]
                        )
                    },
                    credentials: 'include'
                });
            } catch (e) {}
        ");
        $browser->pause(1500);
        $browser->driver->manage()->deleteAllCookies();
        $browser->pause(300);

        DuskTestCase::$adminLoggedIn = false;
    }
}
