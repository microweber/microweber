<?php

namespace Modules\WordPressMigration\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

/**
 * Phase-11 marketing tile for the admin dashboard.
 *
 * Shows "Migrating from WordPress?" only when `content` is empty —
 * the empty-state moment when a brand-new install is most likely to
 * be coming from a WordPress source. Once the operator has any
 * content at all (imported or otherwise), the tile disappears for
 * good so it doesn't turn into chrome nobody reads.
 *
 * The tile links to the Filament resource at
 * /admin/word-press-migration-resource which is the single sidebar
 * entry for the whole import flow.
 */
class WordPressImportCtaWidget extends Widget
{
    // Sort just after the welcome greeting (-2) and before the stats
    // cards (0+) so it reads as part of the greeting block rather than
    // as a misplaced stats card.
    protected static ?int $sort = -1;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'microweber-module-wordpressmigration::widgets.dashboard-cta';

    protected static bool $isLazy = false;

    /**
     * Only render when the live `content` table is empty. We read
     * the count once at render time — admin dashboards don't tolerate
     * long-lived queries, but this is a single primary-key count and
     * the empty-state branch short-circuits after the first row
     * lands, so the cost is bounded.
     */
    public static function canView(): bool
    {
        return DB::table('content')->limit(1)->count() === 0;
    }

    public function getImportUrl(): string
    {
        return url('/admin/word-press-migration-resource');
    }
}
