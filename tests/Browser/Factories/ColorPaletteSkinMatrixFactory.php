<?php

namespace Tests\Browser\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Support\LandingTestContentPurger;

/**
 * Fixture builder for the Phase-7 cross-skin palette matrix.
 *
 * `ColorPaletteFactory` composes four skins into a single page — it
 * answers "does this palette paint the core touchpoints?". The skin
 * matrix instead produces **one page per skin family + skin number**,
 * so a single Dusk test can iterate every skin the Bootstrap template
 * ships and prove the palette applies there too (not just on the
 * composed smoke page).
 *
 * Target skins (mirrors the Phase-7 plan entry):
 *   - Jumbotron  skin-1, skin-2
 *   - Features   skin-1, skin-2
 *   - Pricing    skin-1, skin-2, skin-3
 *   - Titles     skin-1
 *   - Content    skin-1
 *   - Blog       skin-1
 *   - E-commerce skin-1
 *   - Footers    skin-1
 *
 * Skins whose blade file is missing on disk (today: `jumbotron/skin-2`
 * — listed in the plan but not yet shipped) are filtered out by
 * {@see availableSkins()} at make time, so callers only receive
 * fixtures for render-able skins. When a contributor later adds the
 * missing skin, the factory picks it up automatically without any
 * code change.
 *
 * Same pre-conditions as {@see ColorPaletteFactory}:
 *   1. Admin user `admin@admin.com` / `admin` exists (`is_admin=1`).
 *   2. `options.current_template = Bootstrap`.
 *   3. Every returned page is a `clean.blade.php` layout with exactly
 *      one `<module type="layouts" template="<skin>"/>` tag in its
 *      body — so the palette apply observed at live-edit time lands
 *      on a single, isolated skin.
 *
 * Usage:
 *   $fixtures = ColorPaletteSkinMatrixFactory::makeAll();
 *   foreach ($fixtures as $skin => $fixture) {
 *       $browser->visit('/admin/live-edit?url=' . urlencode($fixture->link));
 *       // apply palette, assert, etc.
 *   }
 *   ColorPaletteSkinMatrixFactory::cleanupAll();
 */
class ColorPaletteSkinMatrixFactory
{
    public const ADMIN_EMAIL = 'admin@admin.com';
    public const ADMIN_USERNAME = 'admin';
    public const ADMIN_PASSWORD = 'admin';
    public const SLUG_PREFIX = 'color-palette-skin-test-';

    /**
     * Skin tags the Phase-7 plan asks to cover, in the order they
     * appear on demo.microweber.org's home-page scroll. Keep this
     * array in sync with the Bootstrap template's
     * `modules/layouts/templates/<family>/skin-*.blade.php` files.
     *
     * Plan B.4 first-bullet contract: this constant is the single
     * hook point for keeping {@see \Tests\Browser\LiveEditColorPaletteSkinMatrixTest}
     * green across new skins. Whenever a new per-skin Dusk test
     * lands under tests/Browser/LiveEdit*Skin*Test.php, the matching
     * `<family>/<skin>` tag MUST be added here so the cross-skin
     * palette matrix exercises the new skin too.
     *
     * @var list<string>
     */
    public const TARGET_SKINS = [
        'jumbotron/skin-1',
        'jumbotron/skin-2',
        'features/skin-1',
        'features/skin-2',
        'pricing/skin-1',
        'pricing/skin-2',
        'pricing/skin-3',
        'titles/skin-1',
        'content/skin-1',
        'blog/skin-1',
        'ecommerce/skin-1',
        'footers/skin-1',
        'text-block/skin-1',
        'menus/skin-1',
    ];

    public int $pageId;
    public string $skin;
    public string $title;
    public string $slug;
    public string $link;
    public int $adminId;

    private function __construct() {}

    /**
     * Build one fixture per available target skin.
     *
     * Returns an associative map keyed by skin tag (e.g.
     * `jumbotron/skin-1`) so callers can look up a specific entry
     * without a linear scan. Missing skins (see {@see availableSkins})
     * are excluded from the result.
     *
     * @return array<string, self>
     */
    public static function makeAll(): array
    {
        $out = [];
        foreach (self::availableSkins() as $skin) {
            $out[$skin] = self::makeForSkin($skin);
        }
        return $out;
    }

    /**
     * Seed one fixture for a single skin tag.
     *
     * Throws if the blade file for the requested skin is missing —
     * callers that want best-effort enumeration should go through
     * {@see makeAll()} / {@see availableSkins()} instead of guessing
     * a tag and catching the exception.
     */
    public static function makeForSkin(string $skin): self
    {
        if (!self::skinBladeExists($skin)) {
            throw new \InvalidArgumentException(
                "ColorPaletteSkinMatrixFactory::makeForSkin: no blade file for skin '{$skin}' "
                . 'under Templates/Bootstrap/resources/views/modules/layouts/templates/. '
                . 'Use availableSkins() to enumerate render-able skins.'
            );
        }

        $instance = new self();
        $instance->skin = $skin;
        $instance->adminId = self::ensureAdminUser();
        self::ensureBootstrapActive();

        $instance->title = 'Skin matrix ' . $skin . ' ' . now()->format('Y-m-d H:i:s');
        $instance->slug = self::SLUG_PREFIX . self::slugSuffix($skin);

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $instance->title,
            'url' => $instance->slug,
            'active_site_template' => 'Bootstrap',
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
            'content' => self::buildContentBodyForSkin($skin),
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                "ColorPaletteSkinMatrixFactory::makeForSkin({$skin}): "
                . 'save_content returned a non-id value: '
                . var_export($id, true)
            );
        }

        $instance->pageId = (int)$id;
        $instance->link = (string)content_link($instance->pageId);

        if ($instance->link === '') {
            throw new \RuntimeException(
                "ColorPaletteSkinMatrixFactory::makeForSkin({$skin}): "
                . "content_link({$instance->pageId}) returned empty"
            );
        }

        return $instance;
    }

    /**
     * Target skins filtered to those that actually have a blade file
     * shipped in the current repo state.
     *
     * @return list<string>
     */
    public static function availableSkins(): array
    {
        $out = [];
        foreach (self::TARGET_SKINS as $skin) {
            if (self::skinBladeExists($skin)) {
                $out[] = $skin;
            }
        }
        return array_values($out);
    }

    /**
     * Target skins enumerated in the Phase-7 plan but NOT yet present
     * on disk. Exposed so tests can emit a visible skip message that
     * points at the missing skin, and so a future contributor who
     * adds the missing skin gets automatic coverage (the pending list
     * empties out).
     *
     * @return list<string>
     */
    public static function pendingSkins(): array
    {
        $out = [];
        foreach (self::TARGET_SKINS as $skin) {
            if (!self::skinBladeExists($skin)) {
                $out[] = $skin;
            }
        }
        return array_values($out);
    }

    /**
     * Build the `<module>` blob for a single skin — one tag only.
     * Mirrors the shape `ColorPaletteFactory::buildContentBody()` uses
     * so the save/render pipeline observes the same canonical form.
     */
    public static function buildContentBodyForSkin(string $skin): string
    {
        return '<module type="layouts" template="' . $skin . '"/>';
    }

    /**
     * True if the skin tag resolves to a blade file shipped by the
     * Bootstrap template today.
     */
    public static function skinBladeExists(string $skin): bool
    {
        $base = base_path(
            'Templates/Bootstrap/resources/views/modules/layouts/templates'
        );
        return is_file($base . '/' . $skin . '.blade.php');
    }

    /**
     * Derive a short, URL-safe slug suffix from a skin tag + current
     * timestamp so every fixture's `url` column is unique even when
     * two calls happen in the same second.
     */
    private static function slugSuffix(string $skin): string
    {
        $flat = str_replace('/', '-', $skin);
        return $flat . '-' . substr(
            md5($skin . microtime(true) . Str::random(6)),
            0,
            8
        );
    }

    /**
     * Guarantee an admin user matching AdminLoginTrait's credentials.
     * Creates the row if missing; repairs is_admin / is_active if stale.
     */
    protected static function ensureAdminUser(): int
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();

        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = self::ADMIN_USERNAME;
            $user->password = Hash::make(self::ADMIN_PASSWORD);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();
            return (int)$user->id;
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

        return (int)$user->id;
    }

    /**
     * Set `options.current_template = 'Bootstrap'` (template group).
     * Idempotent — inserts the row if missing.
     */
    protected static function ensureBootstrapActive(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== 'Bootstrap') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => 'Bootstrap',
                        'updated_at' => now(),
                    ]);
            }
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Bootstrap',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Delete this factory's own page row plus its satellite content
     * rows (content_data, content_fields, revisions, …).
     */
    public function cleanup(): void
    {
        LandingTestContentPurger::purge($this->pageId);
    }

    /**
     * Cascade-delete every leftover `color-palette-skin-test-*` page
     * and its satellite rows. Safe to call from setUp/tearDown hooks.
     *
     * @return int[] ids that were purged
     */
    public static function cleanupAll(): array
    {
        $ids = DB::table('content')
            ->where('url', 'like', self::SLUG_PREFIX . '%')
            ->pluck('id')
            ->map(fn ($v) => (int)$v)
            ->all();

        if ($ids === []) {
            return [];
        }

        LandingTestContentPurger::purge($ids);

        return $ids;
    }
}
