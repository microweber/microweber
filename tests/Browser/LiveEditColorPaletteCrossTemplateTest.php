<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-8 cross-template palette portability guard.
 *
 * The Bootstrap template ships 17 color packs under
 * `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/`,
 * but the live-edit picker reads packs from **the active template's**
 * folder — not a hardcoded Bootstrap path. When a future release adds
 * Big / Big2 / Dream packs alongside Bootstrap's, the same save→public-
 * render pipeline must keep working for pages whose
 * `content.active_site_template` pins that sibling template.
 *
 * This test enumerates every template directory under `Templates/`
 * that has a non-empty `resources/assets/design-styles/style-packs/
 * colors/` folder with at least one valid pack JSON, and for each one
 * asserts the **first** pack (alphabetical by slug) applies and
 * survives a guest public-render.
 *
 * Today that's exactly one row (Bootstrap + `apple-shine`). The
 * data-provider glob scaffolds Big/Big2/Dream so any future sibling
 * template that drops a `colors/*.json` file auto-joins the matrix
 * without a code change here.
 *
 * Why "first pack only" (not every pack per template):
 *   {@see LiveEditColorPalettePublicRenderTest} already iterates every
 *   Bootstrap pack for the same save→public flow — so running all 17
 *   here again would be pure duplication. This test's job is
 *   *template portability*: proving the pipeline works when the active
 *   template changes. One representative pack per template is
 *   sufficient for that assertion; the full per-pack matrix lives in
 *   the Phase-3 test.
 *
 * Flow per template:
 *   1. Create a fresh `color-palette-cross-template-test-*` page whose
 *      `active_site_template` pins the template under test AND set
 *      `options.current_template` to the same value (the custom-CSS
 *      save pipeline keys off the *global* current template, so the
 *      page-level pin alone is not enough when reloading public).
 *   2. Invalidate both the file-backed options cache and the repo's
 *      in-memory cache — the artisan-serve worker caches option reads.
 *   3. Open live-edit, apply the first pack via
 *      `cssEditor.setPropertyForSelectorBulk(':root', ..., true, true)`
 *      — the same API the Vue picker drives.
 *   4. `saveLiveEdit()` → persist as template custom CSS.
 *   5. Drop admin cookies, visit the public URL as a guest, snapshot
 *      `document.documentElement`'s computed `--mw-*` vars and assert
 *      every property in the pack is present and matches (hex↔rgb
 *      normalized).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteCrossTemplateTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const SLUG_PREFIX = 'color-palette-cross-template-test-';

    /**
     * Yield one row per template that ships at least one valid color
     * pack JSON. Data providers run before the Laravel app boots, so
     * we resolve paths relative to this file rather than via
     * `base_path()`.
     *
     * @return iterable<string, array{0:string, 1:string}>
     */
    public static function templateProvider(): iterable
    {
        $root = dirname(__DIR__, 2);
        $templatesDir = $root . '/Templates';

        if (!is_dir($templatesDir)) {
            return;
        }

        $templateDirs = glob($templatesDir . '/*', GLOB_ONLYDIR) ?: [];
        sort($templateDirs);

        foreach ($templateDirs as $templatePath) {
            $templateName = basename($templatePath);
            $colorsDir = $templatePath
                . '/resources/assets/design-styles/style-packs/colors';

            if (!is_dir($colorsDir)) {
                continue;
            }

            $packs = [];
            foreach (glob($colorsDir . '/*.json') ?: [] as $file) {
                $slug = pathinfo($file, PATHINFO_FILENAME);
                if (self::packFileIsValid($file)) {
                    $packs[] = $slug;
                }
            }

            if ($packs === []) {
                continue;
            }

            sort($packs);
            yield $templateName => [$templateName, $packs[0]];
        }
    }

    /**
     * Cheap shape check used by the data provider: a pack JSON must
     * decode to an object with `settings[0].fieldSettings.styleProperties[0].properties`
     * that is a non-empty map. Mirrors the guard in
     * {@see LiveEditColorPaletteTrait::listColorPalettes()} so templates
     * that ship a malformed JSON aren't silently yielded as "has packs".
     */
    private static function packFileIsValid(string $file): bool
    {
        $raw = @file_get_contents($file);
        if ($raw === false || $raw === '') {
            return false;
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return false;
        }
        $props = $data['settings'][0]['fieldSettings']['styleProperties'][0]['properties']
            ?? null;
        return is_array($props) && $props !== [];
    }

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    #[DataProvider('templateProvider')]
    public function first_pack_survives_save_to_public_render(
        string $template,
        string $slug
    ): void {
        $pack = $this->readPackFromTemplate($template, $slug);
        $this->assertNotEmpty(
            $pack,
            "Template '{$template}' pack '{$slug}' must declare at least "
            . "one CSS variable in settings[0].fieldSettings.styleProperties[0].properties"
        );

        $fixture = $this->makeFixtureForTemplate($template, $slug);

        $this->browse(function (Browser $browser) use (
            $fixture,
            $pack,
            $slug,
            $template
        ) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture['pageId']);

            $propsJson = json_encode(
                $pack,
                JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            );
            $apply = $browser->script(
                "try {
                    var cssEditor = window.mw && mw.top && mw.top().app && mw.top().app.cssEditor;
                    if (!cssEditor || typeof cssEditor.setPropertyForSelectorBulk !== 'function') {
                        return 'NO_EDITOR';
                    }
                    cssEditor.setPropertyForSelectorBulk(':root', {$propsJson}, true, true);
                    return 'OK';
                } catch (e) {
                    return 'ERR:' + (e && e.message ? e.message : e);
                }"
            );
            $this->assertSame(
                'OK',
                $apply[0] ?? 'UNKNOWN',
                "Cross-template apply failed for '{$template}/{$slug}': "
                . var_export($apply[0] ?? null, true)
            );
            $browser->pause(800);

            $this->assertPaletteApplied($browser, $pack);

            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // Public render as guest — custom CSS must be baked in.
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/' . ltrim($fixture['slug'], '/'))->pause(2000);

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
                "Public render of '{$fixture['slug']}' must expose --mw-* "
                . "custom properties on :root "
                . "(template: {$template}, pack: {$slug})"
            );

            foreach ($pack as $prop => $expectedValue) {
                $this->assertArrayHasKey(
                    $prop,
                    $actual,
                    "Public :root is missing '{$prop}' for template "
                    . "'{$template}' pack '{$slug}' — template custom CSS "
                    . 'save did not persist across templates'
                );

                $exp = $this->normalizeCssColor((string)$expectedValue);
                $got = $this->normalizeCssColor((string)$actual[$prop]);

                $this->assertSame(
                    $exp,
                    $got,
                    "Public :root '{$prop}' mismatch for template "
                    . "'{$template}' pack '{$slug}': expected "
                    . "'{$expectedValue}' got '{$actual[$prop]}'"
                );
            }
        });
    }

    /**
     * Decode one pack JSON from a sibling template's folder and return
     * its CSS custom-property map. Inlined here (instead of extending
     * the shared trait) because the trait hardcodes Bootstrap by design
     * — this test is specifically proving the pipeline works when the
     * active template is something else.
     *
     * @return array<string, string>
     */
    private function readPackFromTemplate(string $template, string $slug): array
    {
        $file = base_path(
            "Templates/{$template}/resources/assets/design-styles/style-packs/colors/{$slug}.json"
        );
        if (!is_file($file)) {
            throw new \RuntimeException(
                "readPackFromTemplate: {$file} not found"
            );
        }
        $raw = @file_get_contents($file);
        if ($raw === false || $raw === '') {
            throw new \RuntimeException(
                "readPackFromTemplate: {$file} is empty or unreadable"
            );
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new \RuntimeException(
                "readPackFromTemplate: {$file} is not valid JSON"
            );
        }
        $props = $data['settings'][0]['fieldSettings']['styleProperties'][0]['properties']
            ?? null;
        if (!is_array($props)) {
            return [];
        }
        return array_map(static fn ($v) => (string)$v, $props);
    }

    /**
     * Seed a `color-palette-cross-template-test-*` Page whose
     * `active_site_template` column pins `$template` and flip the
     * global `options.current_template` to the same value.
     *
     * Returns a plain array (not an object) so the test method can
     * destructure without pulling in a separate fixture class — this
     * file is the only caller.
     *
     * @return array{pageId:int, slug:string, link:string, title:string}
     */
    private function makeFixtureForTemplate(
        string $template,
        string $slug
    ): array {
        $this->ensureAdminUser();
        $this->ensureCurrentTemplate($template);

        $title = "Cross-tpl {$template} {$slug} " . now()->format('Y-m-d H:i:s');
        $pageSlug = self::SLUG_PREFIX
            . strtolower(str_replace([' ', '/'], '-', $template)) . '-'
            . substr(md5($title . microtime(true) . Str::random(6)), 0, 10);

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $pageSlug,
            'active_site_template' => $template,
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
            'content' => '<module type="layouts" template="content/skin-1"/>',
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                "makeFixtureForTemplate({$template}): save_content returned "
                . 'a non-id value: ' . var_export($id, true)
            );
        }

        $link = (string)content_link((int)$id);
        if ($link === '') {
            throw new \RuntimeException(
                "makeFixtureForTemplate({$template}): content_link({$id}) "
                . 'returned empty'
            );
        }

        return [
            'pageId' => (int)$id,
            'slug' => $pageSlug,
            'link' => $link,
            'title' => $title,
        ];
    }

    /**
     * Admin bootstrap parallel to the factories. Kept here instead of
     * reused from ColorPaletteFactory because that factory hardcodes
     * `active_site_template = Bootstrap` in `save_content` — we can't
     * flip that at call time, so we duplicate the small admin-seed
     * helper rather than complicate the factory's signature.
     */
    private function ensureAdminUser(): void
    {
        $user = User::where('email', 'admin@admin.com')->first();
        if (!$user) {
            $user = new User();
            $user->email = 'admin@admin.com';
            $user->username = 'admin';
            $user->password = Hash::make('admin');
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();
            return;
        }
        $dirty = false;
        if ((int)$user->is_admin !== 1) {
            $user->is_admin = 1;
            $dirty = true;
        }
        if ((int)$user->is_active !== 1) {
            $user->is_active = 1;
            $dirty = true;
        }
        if ($dirty) {
            $user->save();
        }
    }

    /**
     * Flip `options.current_template` to `$template` (template group).
     * The file-backed options cache + the repository's in-memory cache
     * are invalidated so the next artisan-serve request reads the
     * freshly written row — without this, the public render would
     * still resolve to the previous template's custom CSS.
     */
    private function ensureCurrentTemplate(string $template): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== $template) {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => $template,
                        'updated_at' => now(),
                    ]);
            }
        } else {
            DB::table('options')->insert([
                'option_key' => 'current_template',
                'option_value' => $template,
                'option_group' => 'template',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
            // Cache kill is best-effort; the teardown trait re-runs it.
        }
    }

    protected function tearDown(): void
    {
        try {
            $ids = DB::table('content')
                ->where('url', 'like', self::SLUG_PREFIX . '%')
                ->pluck('id')
                ->map(fn ($v) => (int)$v)
                ->all();
            if ($ids !== []) {
                LandingTestContentPurger::purge($ids);
            }
        } catch (\Throwable) {
            // teardown must never mask a test failure
        }

        parent::tearDown();
    }
}
