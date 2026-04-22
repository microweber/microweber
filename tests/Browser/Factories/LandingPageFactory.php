<?php

namespace Tests\Browser\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Modules\Page\Models\Page;

/**
 * Fixture builder for live-edit landing-page Dusk tests.
 *
 * Seeds the pre-conditions that every landing-page test assumes:
 *   1. An admin user `admin@admin.com` / `admin` exists with
 *      `is_admin=1` (mirrors the credentials baked into AdminLoginTrait).
 *   2. The globally-active site template is "Bootstrap" — the widest
 *      skin catalog and the one the landing-page plan targets.
 *   3. A fresh `landing-test-<ts>-<rand>` Page row exists with the
 *      clean.blade.php layout and no content, ready for the live-edit
 *      Insert Layout flow to populate.
 *
 * Factory instances carry the ids/slugs they created so tests can
 * call {@see cleanup()} per-instance without touching unrelated rows.
 * The static {@see cleanupAll()} helper wipes every `landing-test-*`
 * page in case an earlier run leaked fixtures.
 *
 * Usage:
 *   $landing = LandingPageFactory::make('Homepage smoke');
 *   $browser->visit('/admin/live-edit?url=' . urlencode($landing->link));
 *   ...
 *   $landing->cleanup();
 */
class LandingPageFactory
{
    public const ADMIN_EMAIL = 'admin@admin.com';
    public const ADMIN_USERNAME = 'admin';
    public const ADMIN_PASSWORD = 'admin';
    public const SLUG_PREFIX = 'landing-test-';

    public int $pageId;
    public string $title;
    public string $slug;
    public string $link;
    public int $adminId;

    private function __construct() {}

    /**
     * Build a fresh landing-test fixture and return the bundle.
     *
     * @param string|null $title Optional human title — defaults to a
     *                           timestamped "Landing smoke …".
     */
    public static function make(?string $title = null): self
    {
        $instance = new self();

        $instance->adminId = self::ensureAdminUser();
        self::ensureBootstrapActive();

        $instance->title = $title ?? ('Landing smoke ' . now()->format('Y-m-d H:i:s'));
        $instance->slug = self::SLUG_PREFIX . substr(md5($instance->title . microtime(true) . Str::random(6)), 0, 10);

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $instance->title,
            'url' => $instance->slug,
            'active_site_template' => 'Bootstrap',
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
            'content' => '',
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'LandingPageFactory::make: save_content returned a non-id value: ' . var_export($id, true)
            );
        }

        $instance->pageId = (int)$id;
        $instance->link = (string)content_link($instance->pageId);

        if ($instance->link === '') {
            throw new \RuntimeException(
                "LandingPageFactory::make: content_link({$instance->pageId}) returned empty"
            );
        }

        return $instance;
    }

    /**
     * Guarantee an admin user matching AdminLoginTrait's credentials.
     * Creates the row if missing; repairs is_admin / password if stale.
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
     * Delete this factory's own page row (best-effort).
     */
    public function cleanup(): void
    {
        try {
            Page::where('id', $this->pageId)->delete();
        } catch (\Throwable) {
            // best-effort — already gone / connection closed
        }
    }

    /**
     * Delete every leftover "landing-test-*" page. Safe to call from
     * setUp/tearDown hooks to scrub state from previous runs.
     */
    public static function cleanupAll(): void
    {
        try {
            Page::where('url', 'like', self::SLUG_PREFIX . '%')->delete();
        } catch (\Throwable) {
            // best-effort
        }
    }
}
