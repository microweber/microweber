<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-321ef4 — SiteStats widgets: normalize unknown/blank rows
 * so the admin → Site Statistics page no longer renders confusing literals.
 *
 * Bugs fixed:
 *   (a) LocationsWidget — `stats_geoip` stores unresolved IPs with literal
 *       `country_name='unknown'` and `country_code='unknown'` (NOT null), so
 *       Filament's `->placeholder('Unknown')` never fires. The page showed
 *       two columns of "unknown" lowercase strings.
 *   (b) LanguagesWidget — `stats_sessions.language='0'` (literal string)
 *       escapes the existing `!= ''` filter and renders as a blank row
 *       under the real "en" row.
 *   (c) RecentVisitorsWidget — same root cause as (a): leftJoin produces
 *       null country_name for missing geoip_id (placeholder OK) BUT joined
 *       'unknown' rows display the raw lowercase string.
 *
 * This test pins the file-level shapes that resolve all three. It is a
 * source-level contract — no DB hits, no Filament boot — so it stays cheap
 * and isolates regressions to the exact widget files.
 */
class SiteStats321ef4UnknownNormalizationContractTest extends TestCase
{
    private string $locations;
    private string $languages;
    private string $recentVisitors;

    protected function setUp(): void
    {
        parent::setUp();
        $base = base_path('Modules/SiteStats/Filament/Widgets');
        $this->locations = (string) file_get_contents("$base/LocationsWidget.php");
        $this->languages = (string) file_get_contents("$base/LanguagesWidget.php");
        $this->recentVisitors = (string) file_get_contents("$base/RecentVisitorsWidget.php");
    }

    #[Test]
    public function locations_widget_coerces_unknown_country_name_to_capitalized_display(): void
    {
        $this->assertMatchesRegularExpression(
            "/TextColumn::make\('country_name'\)[\s\S]*?->formatStateUsing\([\s\S]*?strtolower[\s\S]*?'unknown'[\s\S]*?'Unknown'/",
            $this->locations,
            'LocationsWidget country_name must coerce literal "unknown" string to "Unknown" display.'
        );
    }

    #[Test]
    public function locations_widget_coerces_unknown_country_code_to_dash(): void
    {
        $this->assertMatchesRegularExpression(
            "/TextColumn::make\('country_code'\)[\s\S]*?->formatStateUsing\([\s\S]*?strtolower[\s\S]*?'unknown'/",
            $this->locations,
            'LocationsWidget country_code must coerce literal "unknown" to a non-noisy display.'
        );
        // The code column collapses unknown to an em-dash placeholder.
        $this->assertStringContainsString("'—'", $this->locations);
    }

    #[Test]
    public function languages_widget_filters_literal_zero_string(): void
    {
        $this->assertMatchesRegularExpression(
            "/->where\(\s*'stats_sessions\\.language',\s*'!=',\s*'0'\s*\)/",
            $this->languages,
            'LanguagesWidget must explicitly filter literal "0" language tokens.'
        );
    }

    #[Test]
    public function languages_widget_filters_tokens_shorter_than_two_chars(): void
    {
        $this->assertMatchesRegularExpression(
            "/whereRaw\(\s*'CHAR_LENGTH\(stats_sessions\\.language\) >= 2'/",
            $this->languages,
            'LanguagesWidget must drop sub-2-char tokens (cannot be valid BCP-47).'
        );
    }

    #[Test]
    public function recent_visitors_widget_coerces_unknown_country_name(): void
    {
        $this->assertMatchesRegularExpression(
            "/TextColumn::make\('country_name'\)[\s\S]*?->placeholder\('Unknown'\)[\s\S]*?->formatStateUsing\([\s\S]*?strtolower[\s\S]*?'unknown'[\s\S]*?'Unknown'/",
            $this->recentVisitors,
            'RecentVisitorsWidget country_name must coerce literal "unknown" to "Unknown" display alongside null-placeholder.'
        );
    }

    #[Test]
    public function existing_query_filters_remain_in_place(): void
    {
        // Regression guard — the new filter must be ADDED to the existing
        // null + empty-string guard, not replace it.
        $this->assertStringContainsString("whereNotNull('stats_sessions.language')", $this->languages);
        $this->assertStringContainsString("'stats_sessions.language', '!=', ''", $this->languages);
    }

    #[Test]
    public function task_id_is_pinned_in_all_three_widget_files(): void
    {
        // Each touched widget carries the task id in a comment so future
        // grep-based audits can trace the change.
        $this->assertStringContainsString('task-2026-05-16-321ef4', $this->locations);
        $this->assertStringContainsString('task-2026-05-16-321ef4', $this->languages);
        $this->assertStringContainsString('task-2026-05-16-321ef4', $this->recentVisitors);
    }
}
