<?php

namespace Modules\Content\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/**
 * task-2026-05-16-299f78 / AI-732 Phase 1 — admin page listing
 * distinct content_type values with counts. Jira:
 *   https://microweber.atlassian.net/browse/AI-732
 *
 * Designer dispatch 2026-05-16T15:11:43: Microweber's Content
 * model supports custom content_type strings, but the admin had
 * no UI for managing the type schema. `/admin/custom-fields`
 * routed to the front-end homepage (wired-but-orphaned Filament
 * page).
 *
 * Phase 1 scope (this ticket): list distinct content_type values
 * with row counts + empty state. CTA + create-form deferred to
 * Phase 2 (AI-732-followup). Per-type list views + field-aware
 * editing deferred to Phase 3.
 *
 * Access gated to admins (consistent with the AI-133 / SEC-02
 * pattern on ContentResource).
 *
 * task-2026-05-28-c5d4b9 / AI-1081 — Content Types IA restructure.
 * Jira: https://microweber.atlassian.net/browse/AI-1081
 *
 * Pre-fix: $navigationGroup = 'Settings'. Designer dispatch
 * framed the pre-state as "Website group", but recon found the
 * actual pre-state value was 'Settings' (the legacy AI-732 Phase 1
 * docblock prose claimed "Sits in the Website nav group alongside
 * Pages / Posts", but the source attribute disagreed with the
 * docblock since AI-732 ship — a docblock-vs-source drift). Either
 * way, "Content Types" is a schema-management surface (taxonomy of
 * content_type strings consumed across Page/Post/Product/custom
 * types) — it belongs in the dedicated "Website Settings" sidebar
 * group (already declared in FilamentAdminPanelProvider.php lines
 * 242-248 alongside Shop Settings / Email Settings / Customization
 * Settings) rather than the generic catch-all 'Settings' group.
 * Sibling surfaces in Website Settings: site title / favicon /
 * homepage / language / SEO / sitemap.
 */
class ContentTypesPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $title = 'Content Types';
    protected static ?string $slug = 'content-types';
    // task-2026-05-28-c5d4b9 / AI-1081 — moved from 'Settings' (legacy
    // generic catch-all) to 'Website Settings' (dedicated IA sidebar
    // group). Pre-fix value: 'Settings'. Despite the legacy AI-732
    // Phase 1 docblock claiming "Website nav group", the actual
    // source value was 'Settings' — a docblock-vs-source drift fixed
    // in this ship.
    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';
    protected static ?int $navigationSort = 90;

    protected string $view = 'modules.content::filament.admin.pages.content-types-page';

    public array $contentTypes = [];

    public static function canAccess(): bool
    {
        return function_exists('is_admin') && is_admin();
    }

    public function mount(): void
    {
        $this->contentTypes = $this->loadContentTypes();
    }

    /**
     * Aggregate distinct content_type rows + counts from the
     * content table. Skips soft-deleted rows + groups by type.
     * Returns a list of ['type' => string, 'count' => int]
     * ordered by count descending.
     */
    protected function loadContentTypes(): array
    {
        // task-2026-05-26-b2a545: map null/empty content_type to 'page' (Microweber default)
        return DB::table('content')
            ->select(DB::raw("COALESCE(NULLIF(content_type, ''), 'page') as content_type_label"), DB::raw('COUNT(*) as row_count'))
            ->where(function ($q) { // task-2026-05-22-3b90dd AI-876: deleted_at does not exist on content table; is_deleted is the soft-delete column
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->groupBy('content_type_label')
            ->orderByDesc('row_count')
            ->get()
            ->map(fn ($row) => [
                'type' => (string) $row->content_type_label,
                'count' => (int) $row->row_count,
            ])
            ->all();
    }

    public function getTitle(): string
    {
        return static::$title ?? 'Content Types';
    }

    public function getHeading(): string
    {
        return 'Content Types';
    }

    // task-2026-05-23-78686e / AI-1044 — user-facing copy replaces developer language.
    public function getSubheading(): ?string
    {
        return "Manage your site's content structure. Add custom field schemas to pages, posts, and products.";
    }
}
