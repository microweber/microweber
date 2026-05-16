<x-filament-panels::page>
    {{--
        task-2026-05-16-da59c9: wrap the SiteStats page in
        `.mw-site-stats-page` so the inline <style> block below can
        scope all compact-table rules to THIS page only. Without the
        wrapper any `.fi-ta-cell { padding: ... }` rule would leak to
        every Filament table across the admin (Coupons, Orders, ...).

        Inline <style> tag is intentional — the page blade is
        runtime-interpreted (no build step needed), the CSS sits
        next to the markup it styles, and the SiteStats module owns
        its own page-specific polish without touching the global
        Filament theme bundle.
    --}}
    <div class="mw-site-stats-page">
        <style>
            /*
             * Compact site-stats tables. Filament's default table cell
             * padding is `py-3 px-4` (12px × 16px). On dense data
             * surfaces (Top Pages / Referrers / Locations / Browsers /
             * Languages / Recent Visitors — each shows 5-10 rows of
             * short data) the default rhythm leaves a lot of empty
             * vertical space.
             *
             * Pull padding down to py-1.5 px-3 (6px × 12px), drop
             * font-size half a step, and tighten header rows. Net
             * effect: each row ~30px tall instead of ~52px — the
             * whole stats page fits more above the fold.
             */
            .mw-site-stats-page .fi-ta-cell {
                padding-top: 0.375rem;    /* 6px */
                padding-bottom: 0.375rem;
                padding-left: 0.75rem;    /* 12px */
                padding-right: 0.75rem;
                font-size: 0.8125rem;     /* 13px */
                line-height: 1.35;
            }
            .mw-site-stats-page .fi-ta-header-cell {
                padding-top: 0.375rem;
                padding-bottom: 0.375rem;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                font-size: 0.6875rem;     /* 11px */
                letter-spacing: 0.04em;
                text-transform: uppercase;
                font-weight: 600;
            }
            /*
             * Filament wraps cell text in a `.fi-ta-text-item` with
             * its own min-height and inner padding for icon-prefixed
             * variants. Tighten those too.
             */
            .mw-site-stats-page .fi-ta-text-item {
                min-height: auto;
            }
            .mw-site-stats-page .fi-ta-text {
                font-size: 0.8125rem;
                line-height: 1.35;
            }
            /*
             * Pagination row at the bottom of each table is its own
             * `py-3` toolbar. Tighten so it doesn't visually anchor
             * a thick footer below 5-row tables.
             */
            .mw-site-stats-page .fi-ta-pagination {
                padding-top: 0.375rem;
                padding-bottom: 0.375rem;
            }
            .mw-site-stats-page .fi-ta-pagination .fi-pagination-page-records {
                font-size: 0.75rem;       /* 12px */
            }
            /*
             * Each Filament widget renders inside `.fi-section` with
             * its own 6-unit padding. Bring the section padding down
             * one notch on stats so the chrome doesn't dominate the
             * dense tables it wraps.
             */
            .mw-site-stats-page .fi-section-content-ctn,
            .mw-site-stats-page .fi-section-content {
                padding: 0.75rem;          /* 12px — was 24px */
            }
            .mw-site-stats-page .fi-section-header {
                padding-top: 0.625rem;     /* 10px */
                padding-bottom: 0.625rem;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            .mw-site-stats-page .fi-section-header-heading {
                font-size: 0.9375rem;     /* 15px — was 18px */
            }
            /*
             * Tighten the row gap between widget-cards in the grid
             * so the page doesn't feel "airy" at the cost of the
             * table compaction inside each card.
             */
            .mw-site-stats-page .mt-6 {
                margin-top: 1rem !important;  /* 16px — was 24px */
            }
        </style>

        @livewire(\Modules\SiteStats\Filament\Widgets\StatsOverviewCards::class)

        <div class="mt-6">
            @livewire(\Modules\SiteStats\Filament\Widgets\VisitorsChartWidget::class)
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                @livewire(\Modules\SiteStats\Filament\Widgets\TopPagesWidget::class)
            </div>
            <div>
                @livewire(\Modules\SiteStats\Filament\Widgets\ReferrersWidget::class)
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                @livewire(\Modules\SiteStats\Filament\Widgets\LocationsWidget::class)
            </div>
            <div>
                @livewire(\Modules\SiteStats\Filament\Widgets\BrowsersWidget::class)
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                @livewire(\Modules\SiteStats\Filament\Widgets\LanguagesWidget::class)
            </div>
            <div></div>
        </div>

        <div class="mt-6">
            @livewire(\Modules\SiteStats\Filament\Widgets\RecentVisitorsWidget::class)
        </div>
    </div>
</x-filament-panels::page>
