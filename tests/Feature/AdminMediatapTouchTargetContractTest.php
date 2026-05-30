<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-mediatap — Media Library touch-target floors on mobile.
 *
 * DEFECTS (probed at 390x844 on /admin/media)
 *
 *   1. `.mw-media-unsplash-btn` ships at 110x34 — Add-from-Unsplash action
 *      button in the Media Library toolbar (width passes 44px; only height
 *      needs lifting).
 *
 *   2. `.mw-media-upload-btn` ships at 98x32 — project-bespoke media upload
 *      trigger. AI-855 (commit `bb7b2e6f85`) token-anchored the primary
 *      background colour via `rgba(var(--primary-500),1) !important` but
 *      left geometry untouched; this rule addresses height only, AI-855
 *      colour binding preserved.
 *
 *   3. Laravel default Tailwind-preset pagination buttons inside
 *      `.mw-media-pagination` ship at 74x38. Scope `.mw-media-pagination
 *      button` is project-bespoke wrapper-scoped so it never leaks to
 *      Filament's own `.fi-pagination` chrome.
 *
 * SCOPE NOTE
 *   All three rules carry `body[class*="fi-panel-admin"]` attribute-
 *   substring scope (mirrors the task-2026-05-30-fitasort sibling) so the
 *   fix reaches the core admin panel AND any sibling admin sub-panel
 *   (admin-newsletter etc.) without leaking to checkout / profile panels.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries rules inside the canonical `@media (max-width: 768px),
 *   (pointer: coarse)` block.
 */
class AdminMediatapTouchTargetContractTest extends TestCase
{
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));

        // Pre-strip CSS block comments so docblock prose mentioning rule
        // selectors does not satisfy negative or positive assertions on
        // its own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function mediatap_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.mw-media-unsplash-btn/s',
            $this->cssStripped,
            'mw-media-unsplash-btn rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function unsplash_and_upload_btns_lifted_to_44px_inline_flex(): void
    {
        // Both buttons are grouped in a single multi-selector rule
        // (.mw-media-unsplash-btn, .mw-media-upload-btn).
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-unsplash-btn\s*,\s*body\[class\*="fi-panel-admin"\]\s+\.mw-media-upload-btn\s*\{[^}]*min-height:\s*44px\s*!important[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            'unsplash + upload btns must lift to 44px via inline-flex + align-items: center + min-height: 44px all !important in a single multi-selector rule.'
        );
    }

    #[Test]
    public function pagination_button_lifted_to_44px_height(): void
    {
        // Laravel pagination ships sufficient width (74px) — only height
        // needs lifting. The .mw-media-pagination wrapper is project-
        // bespoke so the selector cannot leak to Filament .fi-pagination.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-pagination\s+button\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.mw-media-pagination button rule must lift min-height to 44px !important.'
        );
    }

    #[Test]
    public function mediatap_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, …) inherit
        // the fix.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-unsplash-btn/s',
            $this->cssStripped,
            'mediatap rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-upload-btn/s',
            $this->cssStripped,
            'mediatap rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-pagination\s+button/s',
            $this->cssStripped,
            'pagination rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function mediatap_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-mediatap', $this->css);
    }

    #[Test]
    public function mediatap_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rules must NOT escape the panel scope.
        // Checkout / profile panels keep their own button styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-unsplash-btn\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-unsplash-btn touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-upload-btn\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-upload-btn touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-pagination\s+button\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-pagination button touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
