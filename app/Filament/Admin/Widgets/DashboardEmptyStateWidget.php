<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/*
 * AI-269 + AI-272 (task-2026-05-13-e64357) — admin dashboard new-install
 * empty-state guidance widget.
 *
 * On a fresh install the dashboard renders four `0` cards (the "wall of
 * zeros" tester flagged in the AI-245 cognitive-load audit). This widget
 * appears ABOVE the quick-stats grid when no Content rows exist yet and
 * surfaces 4 first-action CTAs (Create page / Write post / Add product /
 * Customize theme) so a new site owner has a clear next step instead of
 * staring at zeros.
 *
 * Bounded slice — explicitly OUT OF SCOPE here:
 *   - 3/5-step setup progress indicator (no install-state plumbing yet;
 *     `mw_is_installed()` is binary).
 *   - Dismissible help banner (no user-preferences plumbing yet).
 * Both deferred to AI-269/272 follow-up tickets when the install-state
 * counter / per-user preferences land.
 *
 * Visibility: `canView()` returns true ONLY when the `content` table is
 * empty (zero pages, posts, products, etc. combined). Cached for 60s so
 * the dashboard isn't queried per page load. Once the user creates any
 * Content row the widget disappears on the next 60s cache window — the
 * stats grid alone is the right shape for non-empty installs.
 */
class DashboardEmptyStateWidget extends Widget
{
    /*
     * Sort -1: above the stats grid (sort 0) but below the welcome
     * greeting (sort -2). The greeting answers "who am I?", this widget
     * answers "what do I do next?", the stats grid answers "how are we
     * doing?" — in cognitive order top-to-bottom.
     */
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.dashboard-empty-state-widget';

    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Cache::remember('mw_dashboard_empty_state_visible', 60, function () {
            try {
                return DB::table('content')->where('is_deleted', 0)->count() === 0;
            } catch (\Throwable $e) {
                // If the content table doesn't exist yet (mid-install) the
                // dashboard is unreachable anyway — return false so we don't
                // throw inside the widget render.
                return false;
            }
        });
    }

    public function getHeading(): string
    {
        return "Let's get your site started";
    }

    public function getSubheading(): string
    {
        return "Pick a first action below — you can always add more later from the sidebar.";
    }

    /*
     * Four first-action CTAs. Each entry MUST carry a `min_w_44` flag for
     * the contract test — the AI-272 acceptance criterion requires every
     * CTA to be at least 44×44px to meet WCAG 2.5.5 touch-target size.
     * The blade template applies a `min-height: 44px; min-width: 44px` rule
     * scoped to `.mw-empty-state-cta` so all four buttons satisfy it.
     */
    public function getQuickActions(): array
    {
        $prefix = function_exists('mw_admin_prefix_url') ? mw_admin_prefix_url() : 'admin';

        return [
            [
                'key'         => 'create_page',
                'label'       => 'Create your first page',
                'description' => 'Start with an About, Services or Contact page.',
                'icon'        => 'heroicon-o-document-plus',
                'color'       => 'blue',
                'url'         => url($prefix . '/content/page/create'),
            ],
            [
                'key'         => 'write_post',
                'label'       => 'Write a blog post',
                'description' => 'Share news, updates or an article with your audience.',
                'icon'        => 'heroicon-o-pencil-square',
                'color'       => 'green',
                'url'         => url($prefix . '/content/post/create'),
            ],
            [
                'key'         => 'add_product',
                'label'       => 'Add a product',
                'description' => 'List an item to sell in your online shop.',
                'icon'        => 'heroicon-o-shopping-bag',
                'color'       => 'orange',
                'url'         => url($prefix . '/content/product/create'),
            ],
            [
                'key'         => 'customize_theme',
                'label'       => 'Customize your theme',
                'description' => 'Open Live Edit to change layout, colors and content.',
                'icon'        => 'heroicon-o-paint-brush',
                'color'       => 'pink',
                'url'         => url($prefix . '/live-edit'),
            ],
        ];
    }
}
