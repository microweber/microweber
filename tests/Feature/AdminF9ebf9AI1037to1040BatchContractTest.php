<?php

use Tests\TestCase;

/**
 * Contract test — AI-1037 / AI-1038 / AI-1039 / AI-1040 batch
 * task-2026-05-22-f9ebf9 / task-2026-05-22-5beaa2 / task-2026-05-22-558c11 / task-2026-05-22-3906fc
 *
 * AI-1037: SiteStats slug changed from 'site-stats' to 'site-statistics' so /admin/site-statistics works.
 * AI-1038: Media library thumbnail label gains title tooltip with full filename + size.
 * AI-1039: Date filter inputs gain visible label wrappers ("From" / "To").
 * AI-1040: Dashboard chart footer stat divs gain visible text labels ("visitors", "bounce rate").
 *
 * Selector-self-match guard: Blade block comments and PHP comments stripped before assertions.
 */
class AdminF9ebf9AI1037to1040BatchContractTest extends TestCase
{
    private string $siteStatsPageSrc;
    private string $echartsWidgetSrc;
    private string $echartsWidgetExec;
    private string $mediaLibrarySrc;
    private string $mediaLibraryExec;
    private string $scssSrc;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->siteStatsPageSrc = (string) file_get_contents(
            base_path('Modules/SiteStats/Filament/Pages/SiteStatsPage.php')
        );

        $echartsRaw = (string) file_get_contents(
            base_path('Modules/SiteStats/resources/views/filament/echarts-widget.blade.php')
        );
        $this->echartsWidgetSrc = $echartsRaw;
        $s = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $echartsRaw);
        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $s);
        $this->echartsWidgetExec = $s;

        $mediaRaw = (string) file_get_contents(
            base_path('Modules/MediaLibrary/resources/views/filament/admin/pages/media-library-page.blade.php')
        );
        $this->mediaLibrarySrc = $mediaRaw;
        $m = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $mediaRaw);
        $m = preg_replace('~/\*[\s\S]*?\*/~s', '', $m);
        $this->mediaLibraryExec = $m;

        $this->scssSrc = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber-theme-v3.scss')
        );

        $bundlePath = base_path('public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css');
        $this->bundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ── Group A: AI-1037 — slug = site-statistics ────────────────────────────

    public function test_site_stats_slug_is_site_statistics(): void
    {
        $this->assertStringContainsString(
            "'site-statistics'",
            $this->siteStatsPageSrc,
            'SiteStatsPage slug must be site-statistics so /admin/site-statistics works'
        );
    }

    public function test_old_site_stats_slug_removed(): void
    {
        $stripped = preg_replace('~//[^\n]*~', '', $this->siteStatsPageSrc);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped);
        $this->assertStringNotContainsString(
            "'site-stats'",
            $stripped,
            'Old site-stats slug must no longer appear in executable code'
        );
    }

    public function test_echarts_widget_view_more_link_updated_to_site_statistics(): void
    {
        $this->assertStringContainsString(
            '/admin/site-statistics',
            $this->echartsWidgetExec,
            '"View more" link must point to /admin/site-statistics after slug rename'
        );
    }

    public function test_echarts_widget_old_site_stats_link_removed(): void
    {
        $this->assertStringNotContainsString(
            '/admin/site-stats\')',
            $this->echartsWidgetExec,
            'Old /admin/site-stats link must be replaced in echarts-widget.blade.php'
        );
    }

    // ── Group B: AI-1038 — media library thumbnail tooltip ───────────────────

    public function test_media_grid_label_has_title_tooltip(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-media-grid-label["\s][^>]*title=~',
            $this->mediaLibraryExec,
            'Media grid label div must have a title attribute for filename hover tooltip'
        );
    }

    public function test_media_tooltip_includes_filename(): void
    {
        $this->assertStringContainsString(
            '$item->filename',
            $this->mediaLibraryExec,
            'Tooltip must include the full filename (not just basename) for context'
        );
    }

    public function test_media_tooltip_includes_file_size(): void
    {
        $this->assertStringContainsString(
            'file_size',
            $this->mediaLibraryExec,
            'Tooltip must include file size when available'
        );
    }

    public function test_media_label_still_uses_str_limit(): void
    {
        $this->assertStringContainsString(
            'Str::limit',
            $this->mediaLibraryExec,
            'Media label still truncates long names via Str::limit'
        );
    }

    // ── Group C: AI-1039 — date filter labels ────────────────────────────────

    public function test_date_filter_from_has_visible_label(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-media-filter-date-wrap[\s\S]{0,300}From~',
            $this->mediaLibraryExec,
            '"From" label must appear inside a .mw-media-filter-date-wrap label element'
        );
    }

    public function test_date_filter_to_has_visible_label(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-media-filter-date-wrap[\s\S]{0,300}To~',
            $this->mediaLibraryExec,
            '"To" label must appear inside a .mw-media-filter-date-wrap label element'
        );
    }

    public function test_date_inputs_have_aria_labels(): void
    {
        $this->assertStringContainsString(
            'aria-label="From date"',
            $this->mediaLibraryExec,
            'From date input must have aria-label for accessibility'
        );
        $this->assertStringContainsString(
            'aria-label="To date"',
            $this->mediaLibraryExec,
            'To date input must have aria-label for accessibility'
        );
    }

    public function test_date_label_css_in_scss(): void
    {
        $this->assertStringContainsString(
            'mw-media-filter-date-label',
            $this->scssSrc,
            'SCSS must define .mw-media-filter-date-label for the visible label text'
        );
        $this->assertStringContainsString(
            'mw-media-filter-date-wrap',
            $this->scssSrc,
            'SCSS must define .mw-media-filter-date-wrap for the label+input row'
        );
    }

    // ── Group D: AI-1040 — chart footer visible labels ───────────────────────

    public function test_footer_has_visitors_label(): void
    {
        $this->assertStringContainsString(
            'visitors',
            $this->echartsWidgetExec,
            '"visitors" text label must appear next to the visitor count in the chart footer'
        );
    }

    public function test_footer_has_bounce_rate_label(): void
    {
        $this->assertStringContainsString(
            'bounce rate',
            $this->echartsWidgetExec,
            '"bounce rate" text label must appear next to the bounce percentage'
        );
    }

    public function test_footer_stat_label_css_in_scss(): void
    {
        $this->assertStringContainsString(
            'mw-stats-footer-label',
            $this->scssSrc,
            'SCSS must define .mw-stats-footer-label for the footer stat text labels'
        );
    }

    // ── Group E: built bundle ─────────────────────────────────────────────────

    public function test_bundle_contains_mw_stats_footer_label(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not built');
        }
        $this->assertStringContainsString(
            'mw-stats-footer-label',
            $this->bundle,
            'Built bundle must contain .mw-stats-footer-label CSS'
        );
    }

    public function test_bundle_contains_mw_media_filter_date_label(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not built');
        }
        $this->assertStringContainsString(
            'mw-media-filter-date-label',
            $this->bundle,
            'Built bundle must contain .mw-media-filter-date-label CSS'
        );
    }

    // ── Group F: task-id markers ──────────────────────────────────────────────

    public function test_ai1037_task_id_in_site_stats_page(): void
    {
        $this->assertStringContainsString('task-2026-05-22-f9ebf9', $this->siteStatsPageSrc,
            'SiteStatsPage must carry AI-1037 task-id marker');
    }

    public function test_ai1040_task_id_in_echarts_widget(): void
    {
        $this->assertStringContainsString('task-2026-05-22-3906fc', $this->echartsWidgetSrc,
            'echarts-widget must carry AI-1040 task-id marker');
    }

    public function test_ai1038_task_id_in_media_library(): void
    {
        $this->assertStringContainsString('task-2026-05-22-5beaa2', $this->mediaLibrarySrc,
            'media-library-page.blade.php must carry AI-1038 task-id marker');
    }

    public function test_ai1039_task_id_in_media_library(): void
    {
        $this->assertStringContainsString('task-2026-05-22-558c11', $this->mediaLibrarySrc,
            'media-library-page.blade.php must carry AI-1039 task-id marker');
    }
}
