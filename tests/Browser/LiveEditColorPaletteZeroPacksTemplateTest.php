<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-8 empty-template JS-safety guard: when
 * `options.current_template` points at a theme with zero color packs
 * (today: `Big2` — advertised by the setup wizard but not shipped as
 * a folder under `Templates/`), opening the live-edit sidebar must
 * not throw a JS error, and the style-pack picker simply must not
 * render — no swatch iframes, no `.style-pack-item` nodes, no
 * unhandled promise rejections.
 *
 * Why this matters:
 *   The picker's Vue component ({@see FieldStylePack.vue}) iterates
 *   `setting.fieldSettings.styleProperties.forEach(...)` to render
 *   swatches. If the backend returns a partial/missing
 *   `fieldSettings`, an over-eager `setting.previewElementsStyleProperties[0].properties`
 *   access would throw at `updateIframeContent()`-time. The stylePack
 *   opener computed `isStylePackOpenerMode` already guards on
 *   `Array.isArray(...styleProperties) && length > 0` — this test
 *   locks that guard in.
 *
 *   Separately, the API endpoint `/api/template/template-style-settings`
 *   is served by
 *   {@see \MicroweberPackages\Template\Http\Controllers\Api\TemplateStyleEditorSettingsController::templateStyleSettings}
 *   which reads from `template_dir()` (resolved via
 *   `options.current_template`). When the template folder doesn't
 *   exist, {@see \MicroweberPackages\Template\Adapters\TemplateStylesSettingsReader}
 *   silently returns an empty settings tree — the Vue caller normalizes
 *   this into an empty `styleSettingVars`. A regression that changed
 *   that contract (e.g. throwing or returning `null`) would surface
 *   here as a 500 / a JS crash rather than a silent no-op.
 *
 * Flow:
 *   1. {@see ColorPaletteFactory::make()} seeds a Bootstrap-pinned page
 *      so the factory's own sanity invariants still hold.
 *   2. Flip `options.current_template` to `Big2` (cache-aware, matches
 *      Phase-6 harness pattern).
 *   3. Arm a JS error trap on window.onerror + unhandledrejection
 *      before navigating — errors thrown during sidebar init are the
 *      primary failure mode we're guarding against.
 *   4. Visit `/admin/live-edit?url=...`, wait for the cssEditor to
 *      boot (same readiness gate as every other palette test).
 *   5. Hit `/api/template/template-style-settings` from the top
 *      window and assert: HTTP 200, `styleSettingsVars` is present
 *      and is an array, AND the response contains NO stylePack
 *      setting with populated `fieldSettings.styleProperties`.
 *   6. Assert no `.style-pack-item` swatch elements exist anywhere
 *      in the top document or in its frames.
 *   7. Assert the JS error trap caught zero errors and zero rejections.
 *   8. `finally`: restore `options.current_template = Bootstrap`
 *      (cache-aware) so subsequent tests see a clean baseline.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteZeroPacksTemplateTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const EMPTY_TEMPLATE = 'Big2';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function sidebar_renders_cleanly_when_active_template_has_no_color_packs(): void
    {
        // Confirm the assumption that underpins the test: Big2 truly
        // ships zero color packs. If someone later commits a Big2
        // template that has `design-styles/style-packs/colors/`, this
        // test has to change to use a different empty template — the
        // assertion being "no packs" only makes sense if we proved it
        // before we start.
        $big2Colors = base_path(
            'Templates/' . self::EMPTY_TEMPLATE
            . '/resources/assets/design-styles/style-packs/colors'
        );
        $big2Packs = is_dir($big2Colors) ? (glob($big2Colors . '/*.json') ?: []) : [];
        $this->assertSame(
            [],
            $big2Packs,
            self::EMPTY_TEMPLATE . ' must ship zero color packs for this '
            . 'test to be meaningful; found ' . count($big2Packs) . '. '
            . 'Pick a different never-shipped template name or add a '
            . 'cleanup step that moves the existing packs aside.'
        );

        $fixture = ColorPaletteFactory::make(
            'Palette zero-packs JS-safety'
        );

        $this->setCurrentTemplate(self::EMPTY_TEMPLATE);

        try {
            $this->browse(function (Browser $browser) use ($fixture) {
                $this->loginAsAdmin($browser);

                // Install the error trap BEFORE navigating. The admin
                // live-edit page is the one we need to observe, so we
                // drop the listener on the *top* window now — after the
                // login visit we're on an admin origin which shares the
                // top context with live-edit.
                $this->installErrorTrap($browser);

                $link = content_link($fixture->pageId);
                $this->assertNotEmpty(
                    $link,
                    'content_link for the fixture page must resolve; '
                    . 'otherwise the live-edit URL below is malformed '
                    . 'and a navigation 404 would mask the real bug.'
                );

                $browser->visit('/admin/live-edit?url=' . urlencode((string)$link))
                    ->pause(5000);
                $browser->waitFor('iframe', 20)->pause(3000);

                // Wait for the cssEditor bootstrap same as every
                // palette test, so a slow-Chrome race doesn't read DOM
                // before FieldStylePack had a chance to init.
                $this->waitForCssEditor($browser);

                // Re-arm: after the admin page navigation the window
                // was replaced. Install again on the new top window.
                $this->installErrorTrap($browser);

                $browser->pause(2500);

                // --- API contract check ----------------------------
                $api = $this->fetchStyleSettings($browser);
                $this->assertIsArray(
                    $api,
                    '/api/template/template-style-settings did not return '
                    . 'a JSON object for template=' . self::EMPTY_TEMPLATE
                    . '. A 5xx/empty-body here means the server blew up '
                    . 'on the missing template folder instead of falling '
                    . 'back silently — the picker would show nothing, '
                    . "but the *request* would be red. Got: "
                    . var_export($api, true)
                );

                $this->assertArrayHasKey(
                    'styleSettingsVars',
                    $api,
                    'API payload must carry `styleSettingsVars` even '
                    . 'when empty — an absent key would change the '
                    . 'Vue contract and force a client-side optional '
                    . 'chain everywhere. Got keys: '
                    . json_encode(array_keys($api))
                );

                $vars = $api['styleSettingsVars'];
                $this->assertIsArray(
                    $vars,
                    'styleSettingsVars must always be an array shape, '
                    . 'never null/object, for Big2 — got '
                    . gettype($vars) . ': ' . json_encode($vars)
                );

                // Prove there is no populated stylePack in the tree.
                $populatedPacks = $this->findPopulatedStylePacks($vars);
                $this->assertSame(
                    [],
                    $populatedPacks,
                    'A template with no color packs must not surface '
                    . 'any `fieldType=stylePack` setting with a '
                    . 'non-empty `styleProperties` list — the Vue '
                    . 'picker would render the iframe + swatches and '
                    . "confuse users. Found: "
                    . json_encode($populatedPacks)
                );

                // --- DOM check -------------------------------------
                // The picker's swatches live inside preview-iframes
                // ({@see FieldStylePack.vue::initIframeWrapper}).
                // Count `.style-pack-item` across the top doc + every
                // same-origin iframe — if Big2 accidentally produced
                // swatches, at least one would show up.
                $count = $this->countStylePackItemsEverywhere($browser);
                $this->assertSame(
                    0,
                    $count,
                    'Expected zero .style-pack-item nodes while '
                    . 'current_template=' . self::EMPTY_TEMPLATE
                    . ', but found ' . $count . '. A non-zero count '
                    . 'means the picker rendered itself even though '
                    . 'the active template has no color packs.'
                );

                // --- Error trap drain ------------------------------
                $errors = $this->drainErrorTrap($browser);
                $this->assertSame(
                    [],
                    $errors,
                    'No JS errors should fire during live-edit init '
                    . 'for current_template=' . self::EMPTY_TEMPLATE
                    . '. A populated list indicates the picker '
                    . '(or an adjacent sidebar panel that depends on '
                    . 'the same settings endpoint) threw when it '
                    . 'found an empty tree. Captured: '
                    . json_encode($errors, JSON_UNESCAPED_SLASHES)
                );
            });
        } finally {
            // Unconditional restore — mid-test failure otherwise leaves
            // the dev server pointing at Big2 and every subsequent
            // Dusk run starts broken. Mirrors the `finally` pattern
            // used by LiveEditColorPaletteTemplateSwitchRoundTripTest.
            $this->setCurrentTemplate('Bootstrap');
        }
    }

    /**
     * Wait for the top-frame cssEditor to be ready. Same readiness
     * gate used by {@see LiveEditColorPaletteTrait::openColorPaletteSidebar}
     * — inlined here because that helper also navigates, and we're
     * already on the right page.
     */
    private function waitForCssEditor(Browser $browser): void
    {
        for ($i = 0; $i < 30; $i++) {
            $ready = $browser->script(
                "return (typeof window.mw !== 'undefined'
                    && window.mw.top
                    && window.mw.top().app
                    && window.mw.top().app.cssEditor
                    && typeof window.mw.top().app.cssEditor.setPropertyForSelectorBulk === 'function'
                ) ? 1 : 0;"
            );
            if (($ready[0] ?? 0) === 1) {
                return;
            }
            $browser->pause(500);
        }
        // Not a hard fail — the test still proves value if cssEditor
        // timing out is caused by the same regression this test guards
        // against (a template with no packs shouldn't break cssEditor,
        // but if it does, the error trap + DOM check will still flag).
    }

    /**
     * Hook window.onerror + unhandledrejection so we can catch sync
     * and async failures raised during sidebar init. The trap pushes
     * to `window.__paletteZeroPacksErrors` which we drain at the end.
     */
    private function installErrorTrap(Browser $browser): void
    {
        $browser->script("
            if (!window.__paletteZeroPacksErrors) {
                window.__paletteZeroPacksErrors = [];
            }
            if (!window.__paletteZeroPacksErrorTrapInstalled) {
                window.__paletteZeroPacksErrorTrapInstalled = true;
                window.addEventListener('error', function (e) {
                    try {
                        window.__paletteZeroPacksErrors.push({
                            type: 'error',
                            message: (e && e.message) ? String(e.message) : 'unknown',
                            filename: (e && e.filename) ? String(e.filename) : '',
                            lineno: (e && typeof e.lineno !== 'undefined') ? e.lineno : -1
                        });
                    } catch (_) {}
                }, true);
                window.addEventListener('unhandledrejection', function (e) {
                    try {
                        var msg = '';
                        if (e && e.reason) {
                            msg = (e.reason.message) ? String(e.reason.message) : String(e.reason);
                        }
                        window.__paletteZeroPacksErrors.push({
                            type: 'rejection',
                            message: msg
                        });
                    } catch (_) {}
                }, true);
            }
        ");
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function drainErrorTrap(Browser $browser): array
    {
        $result = $browser->script(
            "var out = window.__paletteZeroPacksErrors || [];
            window.__paletteZeroPacksErrors = [];
            return out;"
        );
        $value = $result[0] ?? [];
        return is_array($value) ? array_values($value) : [];
    }

    /**
     * Fetch the template style-settings API using the admin's live
     * session cookies via fetch(). Returns the decoded JSON or an
     * empty array on network/parse failure (which the caller asserts
     * against).
     *
     * @return array<string, mixed>
     */
    private function fetchStyleSettings(Browser $browser): array
    {
        // Kick off fetch. Keep each script payload small so
        // WebDriver's JSON wire encoding can't trip on a deep object.
        $browser->script("
            window.__paletteZeroPacksApiJson = '';
            window.__paletteZeroPacksApiDone = false;
            window.__paletteZeroPacksApiError = '';
            try {
                fetch('/api/template/template-style-settings', {
                    credentials: 'include',
                    headers: { 'Accept': 'application/json' }
                }).then(function (r) {
                    if (!r.ok) {
                        window.__paletteZeroPacksApiError = 'HTTP ' + r.status;
                        window.__paletteZeroPacksApiDone = true;
                        return '';
                    }
                    return r.text();
                }).then(function (txt) {
                    window.__paletteZeroPacksApiJson = txt || '';
                    window.__paletteZeroPacksApiDone = true;
                }).catch(function (e) {
                    window.__paletteZeroPacksApiError = String(e && e.message || e);
                    window.__paletteZeroPacksApiDone = true;
                });
            } catch (e) {
                window.__paletteZeroPacksApiError = 'sync: ' + String(e && e.message || e);
                window.__paletteZeroPacksApiDone = true;
            }
        ");

        for ($i = 0; $i < 40; $i++) {
            $done = $browser->script(
                'return window.__paletteZeroPacksApiDone ? 1 : 0;'
            );
            if (($done[0] ?? 0) === 1) {
                $errorRes = $browser->script(
                    "return window.__paletteZeroPacksApiError || '';"
                );
                $error = (string)($errorRes[0] ?? '');
                if ($error !== '') {
                    $this->fail(
                        'template-style-settings API request failed: '
                        . $error
                    );
                }
                $jsonRes = $browser->script(
                    "return window.__paletteZeroPacksApiJson || '';"
                );
                $json = (string)($jsonRes[0] ?? '');
                if ($json === '') {
                    return [];
                }
                $decoded = json_decode($json, true);
                return is_array($decoded) ? $decoded : [];
            }
            $browser->pause(250);
        }

        $this->fail('template-style-settings API request never completed within 10s');
    }

    /**
     * Walk the recursive `styleSettingsVars` shape and return every
     * node that is a `stylePack` with a populated `styleProperties`
     * list. Empty list for a zero-packs template is the pass case.
     *
     * @param array<int|string, mixed> $tree
     * @return list<array<string, mixed>>
     */
    private function findPopulatedStylePacks(array $tree): array
    {
        $hits = [];
        $this->walkStylePacks($tree, $hits);
        return $hits;
    }

    /**
     * @param array<int|string, mixed> $tree
     * @param list<array<string, mixed>> $hits
     */
    private function walkStylePacks(array $tree, array &$hits): void
    {
        foreach ($tree as $node) {
            if (!is_array($node)) {
                continue;
            }
            $type = $node['fieldType'] ?? null;
            if ($type === 'stylePack') {
                $props = $node['fieldSettings']['styleProperties'] ?? null;
                if (is_array($props) && $props !== []) {
                    // Only flag when the nested properties object is
                    // itself non-empty — a style pack that's declared
                    // but has zero keys is still a "no packs" shape.
                    foreach ($props as $styleProp) {
                        $innerProps = $styleProp['properties'] ?? null;
                        if (is_array($innerProps) && $innerProps !== []) {
                            $hits[] = [
                                'title' => $node['title'] ?? '',
                                'label' => $styleProp['label'] ?? '',
                                'propertyCount' => count($innerProps),
                            ];
                            break;
                        }
                    }
                }
            }
            if (isset($node['settings']) && is_array($node['settings'])) {
                $this->walkStylePacks($node['settings'], $hits);
            }
        }
    }

    /**
     * Count `.style-pack-item` elements across the top document and
     * every same-origin iframe in it. The picker mounts swatches
     * inside a preview-iframe, so a top-doc-only querySelector would
     * miss them.
     */
    private function countStylePackItemsEverywhere(Browser $browser): int
    {
        $result = $browser->script("
            try {
                var total = document.querySelectorAll('.style-pack-item').length;
                var frames = document.querySelectorAll('iframe');
                for (var i = 0; i < frames.length; i++) {
                    try {
                        var fdoc = frames[i].contentDocument;
                        if (fdoc) {
                            total += fdoc.querySelectorAll('.style-pack-item').length;
                        }
                    } catch (_) {
                        // cross-origin iframe — skip
                    }
                }
                return total;
            } catch (e) {
                return -1;
            }
        ");
        $value = $result[0] ?? 0;
        return is_int($value) ? $value : (int)$value;
    }

    /**
     * Cache-aware option write. Identical shape to the helper in
     * LiveEditColorPaletteTemplateSwitchRoundTripTest — option changes
     * invisible to the dev-server artisan-serve worker without a
     * cache kill, so both the repo's in-memory cache and the file
     * cache-manager 'options' bucket are invalidated.
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
            // best-effort
        }

        // Also do a raw DB write as a defensive backstop in case
        // save_option() silently declined (permissions / order).
        DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->update([
                'option_value' => $name,
                'updated_at' => now(),
            ]);
    }
}
