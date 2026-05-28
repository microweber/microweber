<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-c5d4b9 / AI-1081 — Content Types IA restructure.
 * Jira: https://microweber.atlassian.net/browse/AI-1081
 *
 * Moves the Content Types admin page from the legacy generic
 * 'Settings' nav group into the dedicated 'Website Settings'
 * sidebar group (already declared in FilamentAdminPanelProvider.php
 * alongside Shop Settings / Email Settings / Customization Settings).
 *
 * Recon delta from dispatch: the agent-pm Batch 3 dispatch framed
 * the pre-state as "Website group". Actual source pre-state was
 * 'Settings' (the legacy AI-732 Phase 1 docblock prose claimed
 * "Sits in the Website nav group alongside Pages / Posts" but the
 * source attribute said 'Settings' — a docblock-vs-source drift
 * since AI-732 ship). This ship reconciles source + docblock at
 * the canonical 'Website Settings' value.
 *
 * Selector-self-match guard: setUp() pre-strips PHP line + block
 * comments before any absence assertion in case docblock prose
 * mentions a literal pre-state signature.
 */
class AdminC5d4b9AI1081ContentTypesNavGroupContractTest extends TestCase
{
    private const CONTENT_TYPES_PAGE = 'Modules/Content/Filament/Admin/Pages/ContentTypesPage.php';

    private const ADMIN_PANEL_PROVIDER = 'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php';

    private string $pageSource;

    private string $pageSourceStripped;

    private string $providerSource;

    private string $providerSourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pageSource = (string) file_get_contents(base_path(self::CONTENT_TYPES_PAGE));
        $this->pageSourceStripped = $this->stripPhpComments($this->pageSource);

        $this->providerSource = (string) file_get_contents(base_path(self::ADMIN_PANEL_PROVIDER));
        $this->providerSourceStripped = $this->stripPhpComments($this->providerSource);
    }

    private function stripPhpComments(string $source): string
    {
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    // ---------------------------------------------------------------------
    // Group A -- $navigationGroup attribute on ContentTypesPage is the
    //            canonical 'Website Settings' value (post-fix)
    // ---------------------------------------------------------------------

    #[Test]
    public function content_types_page_navigation_group_is_website_settings(): void
    {
        $this->assertMatchesRegularExpression(
            "/protected\\s+static\\s+string\\|\\\\UnitEnum\\|null\\s+\\\$navigationGroup\\s*=\\s*'Website Settings'\\s*;/",
            $this->pageSourceStripped,
            "AI-1081: ContentTypesPage MUST declare \$navigationGroup = 'Website Settings'. This routes the Content Types admin surface into the dedicated Website Settings IA group alongside site title / favicon / homepage / language / SEO / sitemap."
        );
    }

    #[Test]
    public function legacy_settings_only_group_value_absent_from_source(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "/\\\$navigationGroup\\s*=\\s*'Settings'\\s*;/",
            $this->pageSourceStripped,
            "AI-1081: legacy '\$navigationGroup = 'Settings';' pre-state MUST be absent — replaced with 'Website Settings' per the IA restructure. Comment-strip guard ensures docblock prose referencing the literal pre-state token does not trip this assertion."
        );
    }

    // ---------------------------------------------------------------------
    // Group B -- 'Website Settings' group declaration exists in
    //            FilamentAdminPanelProvider (target group must be real)
    // ---------------------------------------------------------------------

    #[Test]
    public function website_settings_group_declared_in_admin_panel_provider(): void
    {
        $this->assertMatchesRegularExpression(
            "/'Website Settings'\\s*=>\\s*NavigationGroup::make\\(\\)/",
            $this->providerSourceStripped,
            "AI-1081: 'Website Settings' NavigationGroup MUST be declared in FilamentAdminPanelProvider so the ContentTypesPage \$navigationGroup target resolves to a real sidebar group."
        );

        $this->assertMatchesRegularExpression(
            "/->label\\(\\s*'Website Settings'\\s*\\)/",
            $this->providerSourceStripped,
            "AI-1081: 'Website Settings' NavigationGroup MUST carry a ->label('Website Settings') call so the sidebar header renders correctly."
        );
    }

    // ---------------------------------------------------------------------
    // Group C -- task-id + AI-1081 markers (audit grep contract)
    // ---------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_page_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-c5d4b9',
            $this->pageSource,
            'AI-1081: ContentTypesPage MUST carry the AI-1081 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1081',
            $this->pageSource,
            'AI-1081: ContentTypesPage MUST cite the AI-1081 ticket ID.'
        );
    }

    // ---------------------------------------------------------------------
    // Group D -- recon-delta documented (dispatch said "Website group"
    //            but actual pre-state was 'Settings')
    // ---------------------------------------------------------------------

    #[Test]
    public function recon_delta_documented_in_page_source(): void
    {
        $anchorPos = strpos($this->pageSource, 'task-2026-05-28-c5d4b9 / AI-1081');
        $this->assertNotFalse($anchorPos, 'AI-1081: marker anchor must be present.');

        $slice = substr($this->pageSource, $anchorPos, 2000);

        $this->assertStringContainsString(
            'Settings',
            $slice,
            'AI-1081: comment block MUST document the actual pre-state value ('
            . "'Settings'" . ') so future audits can trace the IA migration.'
        );
    }

    // ---------------------------------------------------------------------
    // Group E -- other ContentTypesPage attributes preserved (regression
    //            guard): icon + slug + sort order + title unchanged
    // ---------------------------------------------------------------------

    #[Test]
    public function other_page_attributes_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            "/\\\$navigationIcon\\s*=\\s*'heroicon-o-rectangle-stack'\\s*;/",
            $this->pageSourceStripped,
            "AI-1081 regression guard: \$navigationIcon MUST remain 'heroicon-o-rectangle-stack'."
        );
        $this->assertMatchesRegularExpression(
            "/\\\$slug\\s*=\\s*'content-types'\\s*;/",
            $this->pageSourceStripped,
            "AI-1081 regression guard: \$slug MUST remain 'content-types' (URL path unchanged)."
        );
        $this->assertMatchesRegularExpression(
            "/\\\$navigationSort\\s*=\\s*90\\s*;/",
            $this->pageSourceStripped,
            "AI-1081 regression guard: \$navigationSort MUST remain 90 (ordering within the new Website Settings group)."
        );
        $this->assertMatchesRegularExpression(
            "/\\\$title\\s*=\\s*'Content Types'\\s*;/",
            $this->pageSourceStripped,
            "AI-1081 regression guard: \$title MUST remain 'Content Types' (heading unchanged)."
        );
    }
}
