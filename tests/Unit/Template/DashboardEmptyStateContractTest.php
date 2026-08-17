<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-269 + AI-272 contract test (task-2026-05-13-e64357).
 *
 * Pins the structural shape of the new dashboard empty-state widget that
 * surfaces on a fresh install (the AI-245 "wall of zeros" fix):
 *
 *   - DashboardEmptyStateWidget class is registered between the welcome
 *     greeting and the quick-stats grid (sort -1).
 *   - The widget's canView() gate queries the `content` table — so once
 *     the user creates any page/post/product the widget vanishes.
 *   - getQuickActions() returns the four first-action CTAs (Create page /
 *     Write post / Add product / Customize theme) — no more, no fewer.
 *   - The blade view emits a `[data-mw-empty-state-cta]` element for
 *     every action and the wrapping `.mw-empty-state-grid` so assistive
 *     tech / browser tests can enumerate the CTA set.
 *   - The SCSS source defines `.mw-empty-state-cta { min-height: 44px;
 *     min-width: 44px; }` (WCAG 2.5.5 touch-target).
 *   - The built theme bundle carries the empty-state classes — guards
 *     against an unbuilt package regressing the production styles.
 *
 * Deliberately NOT pinned:
 *   - Setup-step progress indicator (AC #4) — deferred until install
 *     state plumbing exists.
 *   - Dismissible help banner (AC #5) — deferred until user-preferences
 *     plumbing exists.
 */
class DashboardEmptyStateContractTest extends TestCase
{
    private const WIDGET_CLASS_PATH = __DIR__ . '/../../../app/Filament/Admin/Widgets/DashboardEmptyStateWidget.php';
    private const WIDGET_BLADE_PATH = __DIR__ . '/../../../resources/views/filament/admin/widgets/dashboard-empty-state-widget.blade.php';
    private const DASHBOARD_PAGE_PATH = __DIR__ . '/../../../app/Filament/Admin/Pages/Dashboard.php';
    private const THEME_SCSS_PATH = __DIR__ . '/../../../packages/microweber-filament-theme/resources/assets/css/microweber/admin/dashboard.css';
    private const THEME_CSS_BUILT = __DIR__ . '/../../../public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    #[Test]
    public function dashboard_registers_the_empty_state_widget_between_welcome_and_stats(): void
    {
        $php = $this->readFile(self::DASHBOARD_PAGE_PATH);

        $welcomePos    = strpos($php, 'WelcomeWidget::class');
        $emptyStatePos = strpos($php, 'DashboardEmptyStateWidget::class');
        $statsPos      = strpos($php, 'DashboardQuickStatsWidget::class');

        $this->assertNotFalse($welcomePos,    'WelcomeWidget must be registered.');
        $this->assertNotFalse($emptyStatePos, 'DashboardEmptyStateWidget must be registered in Dashboard::getWidgets().');
        $this->assertNotFalse($statsPos,      'DashboardQuickStatsWidget must still be registered.');

        $this->assertGreaterThan($welcomePos, $emptyStatePos,
            'EmptyStateWidget must be registered AFTER WelcomeWidget so source order matches sort intent.');
        $this->assertLessThan($statsPos, $emptyStatePos,
            'EmptyStateWidget must be registered BEFORE DashboardQuickStatsWidget so it renders above the stats grid.');
    }

    #[Test]
    public function widget_class_sort_is_minus_one_between_welcome_minus_two_and_stats_zero(): void
    {
        $php = $this->readFile(self::WIDGET_CLASS_PATH);

        $this->assertMatchesRegularExpression(
            '/protected\s+static\s+\?int\s+\$sort\s*=\s*-1\s*;/',
            $php,
            'DashboardEmptyStateWidget must declare $sort = -1 (between WelcomeWidget at -2 and DashboardQuickStatsWidget at 0).'
        );
    }

    #[Test]
    public function can_view_gates_visibility_on_content_table_being_empty(): void
    {
        $php = $this->readFile(self::WIDGET_CLASS_PATH);

        $this->assertMatchesRegularExpression(
            '/public\s+static\s+function\s+canView\s*\(\s*\)\s*:\s*bool/',
            $php,
            'DashboardEmptyStateWidget must define canView(): bool.'
        );

        $this->assertMatchesRegularExpression(
            "/DB::table\\('content'\\)\\s*->\\s*where\\('is_deleted',\\s*0\\)\\s*->\\s*count\\(\\)\\s*===\\s*0/",
            $php,
            'canView() must gate visibility on `content` table count === 0 (only show on truly empty installs).'
        );
    }

    #[Test]
    public function get_quick_actions_returns_exactly_four_first_action_ctas(): void
    {
        $php = $this->readFile(self::WIDGET_CLASS_PATH);

        $start = strpos($php, 'public function getQuickActions(): array');
        $this->assertNotFalse($start, 'getQuickActions() must exist on the widget.');

        $body = substr($php, $start);
        $endMarker = strpos($body, "\n    }");
        $this->assertNotFalse($endMarker, 'getQuickActions() body must terminate before the next method.');
        $body = substr($body, 0, $endMarker);

        $this->assertMatchesRegularExpression(
            "/'key'\\s*=>\\s*'create_page'/",
            $body,
            'getQuickActions() must include the create_page CTA (AI-272 AC #2 "Create your first page").'
        );
        $this->assertMatchesRegularExpression(
            "/'key'\\s*=>\\s*'write_post'/",
            $body,
            'getQuickActions() must include the write_post CTA (AI-272 AC #3 "Write blog post").'
        );
        $this->assertMatchesRegularExpression(
            "/'key'\\s*=>\\s*'add_product'/",
            $body,
            'getQuickActions() must include the add_product CTA (AI-272 AC #3 "Add product").'
        );
        $this->assertMatchesRegularExpression(
            "/'key'\\s*=>\\s*'customize_theme'/",
            $body,
            'getQuickActions() must include the customize_theme CTA (AI-272 AC #3 "Customize theme").'
        );

        $keyCount = preg_match_all("/'key'\\s*=>\\s*'/", $body);
        $this->assertSame(
            4,
            $keyCount,
            'getQuickActions() must return EXACTLY 4 CTAs (no more, no fewer) — found ' . $keyCount . '.'
        );
    }

    #[Test]
    public function blade_view_emits_data_hook_for_each_cta(): void
    {
        $blade = $this->readFile(self::WIDGET_BLADE_PATH);

        $this->assertMatchesRegularExpression(
            '/data-mw-empty-state-cta="\{\{\s*\$action\[\'key\'\]\s*\}\}"/',
            $blade,
            'Each CTA must carry data-mw-empty-state-cta="{{ $action[\'key\'] }}" so tests + assistive tech can enumerate.'
        );

        $this->assertMatchesRegularExpression(
            '/class="[^"]*\bmw-empty-state-grid\b[^"]*"/',
            $blade,
            'The CTA grid wrapper must carry the .mw-empty-state-grid class so the responsive grid rule applies.'
        );

        $this->assertMatchesRegularExpression(
            '/<x-filament-widgets::widget>/',
            $blade,
            'Blade must wrap content in <x-filament-widgets::widget> so Filament renders it as a widget panel.'
        );
    }

    #[Test]
    public function scss_source_pins_44px_touch_target_floor_on_every_cta(): void
    {
        $scss = $this->readFile(self::THEME_SCSS_PATH);

        $this->assertMatchesRegularExpression(
            '/\.mw-empty-state-cta\s*\{[^}]*min-height:\s*44px[^}]*min-width:\s*44px/s',
            $scss,
            '.mw-empty-state-cta must declare both min-height: 44px AND min-width: 44px (WCAG 2.5.5 touch target — AI-272 AC #2).'
        );
    }

    #[Test]
    public function scss_source_stacks_grid_on_mobile_then_auto_fit_above_sm(): void
    {
        $scss = $this->readFile(self::THEME_SCSS_PATH);

        $this->assertMatchesRegularExpression(
            '/\.mw-empty-state-grid\s*\{[^}]*grid-template-columns:\s*1fr[^}]*\}/s',
            $scss,
            '.mw-empty-state-grid must default to grid-template-columns: 1fr (stacked on mobile — AI-272 AC #6).'
        );

        $this->assertMatchesRegularExpression(
            '/@media\s*\(min-width:\s*576px\)\s*\{[^@]*\.mw-empty-state-grid\s*\{[^}]*grid-template-columns:\s*repeat\(auto-fit,\s*minmax\(240px,\s*1fr\)\)/s',
            $scss,
            '.mw-empty-state-grid must switch to repeat(auto-fit, minmax(240px, 1fr)) inside @media (min-width: 576px) so the layout grids above the sm breakpoint.'
        );
    }

    #[Test]
    public function built_theme_bundle_carries_the_empty_state_selectors(): void
    {
        if (!file_exists(self::THEME_CSS_BUILT)) {
            $this->markTestSkipped('Built filament-theme bundle missing — run `npm run build` in packages/microweber-filament-theme.');
        }

        $built = $this->readFile(self::THEME_CSS_BUILT);

        $this->assertStringContainsString(
            'mw-dashboard-empty-state',
            $built,
            'Built bundle must carry .mw-dashboard-empty-state — run npm run build if missing.'
        );
        $this->assertStringContainsString(
            'mw-empty-state-cta',
            $built,
            'Built bundle must carry .mw-empty-state-cta selectors.'
        );
        $this->assertStringContainsString(
            'mw-empty-state-grid',
            $built,
            'Built bundle must carry .mw-empty-state-grid selectors.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
