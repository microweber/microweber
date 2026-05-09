<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-126 / AI-140 + AI-141 + AI-142 — mobile Live Edit fixes
 * + footer href fix.
 *
 * Pins:
 *   - live-edit-mobile.css scopes the sidebar collapse +
 *     toolbar-label rules to `.mw-live-edit-page` + ≤768px.
 *   - Sidebar collapses to 56px width on mobile.
 *   - Sidebar item labels are visually hidden (sr-only pattern).
 *   - Toolbar icon buttons stack the aria-label text below the
 *     icon via ::after.
 *   - Bootstrap default footer skin no longer has `href=""`;
 *     mailto: link in place.
 *
 * Style after the cycle-52..125 contract tests.
 */
class MobileLiveEditAndFooterHrefContractTest extends TestCase
{
    private const CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css';
    private const FOOTER = 'Templates/Bootstrap/resources/views/modules/layouts/templates/footers/skin-1.blade.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function mobile_css_scopes_to_live_edit_and_max_768(): void
    {
        $src = $this->read(self::CSS);

        $this->assertStringContainsString(
            'AI-140 + AI-141',
            $src,
            'live-edit-mobile.css must carry the AI-140/141 audit-trail comment'
        );

        // Both rules wrapped in @media (max-width: 768px).
        $this->assertMatchesRegularExpression(
            '/@media \\(max-width:\\s*768px\\)\\s*\\{/',
            $src,
            'mobile CSS must scope every rule under @media (max-width: 768px)'
        );

        // Scoped under .mw-live-edit-page so the rules don't leak
        // to non-live-edit Filament panels.
        $this->assertMatchesRegularExpression(
            '/\\.mw-live-edit-page\\s+\\.fi-sidebar/',
            $src,
            'sidebar collapse rule must be scoped under `.mw-live-edit-page`'
        );
    }

    #[Test]
    public function ai_140_sidebar_collapses_to_56px(): void
    {
        $src = $this->read(self::CSS);

        $this->assertMatchesRegularExpression(
            '/\\.mw-live-edit-page\\s+\\.fi-sidebar\\s*\\{[\\s\\S]{0,200}width:\\s*56px/',
            $src,
            'sidebar must collapse to 56px width on mobile'
        );

        // Labels must be visually hidden via the sr-only clip
        // pattern (NOT display:none, which would also hide from AT).
        $this->assertMatchesRegularExpression(
            '/\\.fi-sidebar-item-label[\\s\\S]{0,500}clip:\\s*rect\\(0,\\s*0,\\s*0,\\s*0\\)/',
            $src,
            'sidebar labels must use the sr-only clip pattern'
        );
    }

    #[Test]
    public function ai_141_toolbar_buttons_stack_aria_label(): void
    {
        $src = $this->read(self::CSS);

        $this->assertStringContainsString(
            'flex-direction: column',
            $src,
            'toolbar icon-buttons must stack icon + label vertically'
        );

        // ::after content reads from aria-label so the visible text
        // matches what SR users hear.
        $this->assertMatchesRegularExpression(
            '/\\.live-edit-toolbar\\s+\\.fi-icon-btn::after\\s*\\{[\\s\\S]{0,300}content:\\s*attr\\(aria-label\\)/',
            $src,
            'toolbar icon-button ::after must read content from attr(aria-label)'
        );
    }

    #[Test]
    public function ai_142_default_footer_skin_uses_mailto_not_empty_href(): void
    {
        $src = $this->read(self::FOOTER);

        $this->assertStringNotContainsString(
            '<a href="">mail@yourcompany.com</a>',
            $src,
            'Footer skin-1 must NOT carry the legacy `href=""` placeholder'
        );

        $this->assertStringContainsString(
            '<a href="mailto:mail@yourcompany.com">mail@yourcompany.com</a>',
            $src,
            'Footer skin-1 must carry a real `mailto:` link to the visible address'
        );

        // Audit-trail.
        $this->assertStringContainsString(
            'AI-142 / A11Y-07 (cycle-126',
            $src,
            'Footer skin-1 must carry the AI-142 audit-trail comment'
        );
    }
}
