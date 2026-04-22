<?php

namespace Tests\Browser\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Support\LandingTestContentPurger;

/**
 * Fixture builder for color-palette Dusk tests.
 *
 * Seeds the pre-conditions every palette test assumes:
 *   1. An admin user `admin@admin.com` / `admin` exists with
 *      `is_admin=1` (mirrors AdminLoginTrait credentials).
 *   2. The globally-active site template is "Bootstrap" — the only
 *      template that currently ships the 17 style-pack color JSONs.
 *   3. A fresh `color-palette-test-<ts>-<rand>` Page row exists with
 *      the clean layout and its `content` body pre-populated with the
 *      four layout modules that together exercise the palette's
 *      visible touchpoints:
 *        - `jumbotron/skin-1` — header/top-header variables and hero
 *        - `content/skin-1`  — body, heading, paragraph, link colors
 *        - `pricing/skin-1`  — primary-button variables
 *        - `footers/skin-1`  — section-background + link-on-section
 *      This picks up every {@see LiveEditColorPaletteTrait}'s core
 *      variable set without needing live-edit Insert Layout clicks
 *      during test setup.
 *
 * Usage:
 *   $fixture = ColorPaletteFactory::make('Palette smoke');
 *   $browser->visit('/admin/live-edit?url=' . urlencode($fixture->link));
 *   ...
 *   $fixture->cleanup();
 */
class ColorPaletteFactory
{
    public const ADMIN_EMAIL = 'admin@admin.com';
    public const ADMIN_USERNAME = 'admin';
    public const ADMIN_PASSWORD = 'admin';
    public const SLUG_PREFIX = 'color-palette-test-';

    /**
     * Module layout tags (category/skin) composed into the page body.
     * Each entry is rendered by Microweber's `<module type="layouts" .../>`
     * expander at public-render time.
     */
    public const LAYOUT_MODULES = [
        'jumbotron/skin-1',
        'content/skin-1',
        'pricing/skin-1',
        'footers/skin-1',
    ];

    public int $pageId;
    public string $title;
    public string $slug;
    public string $link;
    public int $adminId;

    private function __construct() {}

    /**
     * Build a fresh color-palette-test fixture and return the bundle.
     *
     * @param string|null $title Optional human title — defaults to a
     *                           timestamped "Palette smoke …".
     */
    public static function make(?string $title = null): self
    {
        $instance = new self();

        $instance->adminId = self::ensureAdminUser();
        self::ensureBootstrapActive();

        $instance->title = $title ?? ('Palette smoke ' . now()->format('Y-m-d H:i:s'));
        $instance->slug = self::SLUG_PREFIX . substr(
            md5($instance->title . microtime(true) . Str::random(6)),
            0,
            10
        );

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $instance->title,
            'url' => $instance->slug,
            'active_site_template' => 'Bootstrap',
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
            'content' => self::buildContentBody(),
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'ColorPaletteFactory::make: save_content returned a non-id value: ' . var_export($id, true)
            );
        }

        $instance->pageId = (int)$id;
        $instance->link = (string)content_link($instance->pageId);

        if ($instance->link === '') {
            throw new \RuntimeException(
                "ColorPaletteFactory::make: content_link({$instance->pageId}) returned empty"
            );
        }

        return $instance;
    }

    /**
     * Return the four layout module skins this factory composes, so
     * tests can assert every one is present after apply/save/reload.
     *
     * @return string[]
     */
    public static function layoutModules(): array
    {
        return self::LAYOUT_MODULES;
    }

    /**
     * Render the inline `<module>` tag blob that populates `content.content`.
     * Kept protected-static so tests can reconstruct the expected body
     * when diffing DB state without duplicating the layout list.
     */
    public static function buildContentBody(): string
    {
        $lines = [];
        foreach (self::LAYOUT_MODULES as $template) {
            $lines[] = '<module type="layouts" template="' . $template . '"/>';
        }
        return implode("\n", $lines);
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
     * Cascade-delete every leftover `color-palette-test-*` page and its
     * satellite rows. Safe to call from setUp/tearDown hooks.
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
