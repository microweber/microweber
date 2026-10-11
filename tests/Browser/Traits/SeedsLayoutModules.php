<?php

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Database\Facades\DatabaseManager;
use Modules\Content\Models\Content;

/**
 * Seeds a page whose main content region is filled with layout-skin modules,
 * so browser tests can render the template's layouts and assert their mobile
 * behaviour (no horizontal overflow, symmetric side padding, …).
 *
 * The skins are injected into the content_fields "content" region (the same
 * store the Live-Edit editable region uses), each wrapped in a
 * <div data-skin-marker="category/skin"> so a test can measure a single skin.
 */
trait SeedsLayoutModules
{
    /**
     * Representative skins spanning every layout category, including the ones
     * most prone to mobile side-padding / overflow issues.
     *
     * @return list<string>
     */
    public static function qaLayoutSkins(): array
    {
        return [
            'header/skin-1',
            'call-to-action/skin-1',
            'features/skin-1',
            'content/skin-1',
            'blog/skin-1',
            'gallery/skin-1',
            'price_lists/skin-1',
            'team/skin-1',
            'testimonials/skin-1',
            'contacts/skin-1',
            'titles/skin-1',
            'text-block/skin-1',
            'footers/skin-1',
            // NOTE: ecommerce/* skins embed the dynamic products-listing module,
            // whose grid is owned by the shop module (not the layout skin), so
            // its mobile layout is out of scope for this layout-padding test.
        ];
    }

    /**
     * Create (or refresh) the QA layouts page and return its public URL slug.
     *
     * @param  list<string>|null  $skins
     */
    protected function seedLayoutModulesPage(string $url = 'qa-layouts-mobile', ?array $skins = null): string
    {
        $skins = $skins ?? static::qaLayoutSkins();

        $value = '';
        foreach ($skins as $skin) {
            $value .= '<div data-skin-marker="' . $skin . '">'
                . '<module type="layouts" template="' . $skin . '"/>'
                . '</div>';
        }

        if (function_exists('app') && app()->bound(DatabaseManager::class)) {
            DatabaseManager::extended_save_set_permission(true);
        }

        $existing = Content::where('url', $url)->first();
        $payload = [
            'title' => 'QA Layouts Mobile',
            'url' => $url,
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
        ];
        if ($existing) {
            $payload['id'] = $existing->id;
        }
        $contentId = save_content($payload);

        DB::table('content_fields')->updateOrInsert(
            ['rel_type' => 'content', 'rel_id' => $contentId, 'field' => 'content'],
            ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
        );

        if (function_exists('mw_post_update')) {
            // Bust any cached rendering of this content so the seed is live.
            try {
                app()->cache_manager->delete('content');
            } catch (\Throwable) {
                // best-effort cache bust
            }
        }

        return $url;
    }
}
