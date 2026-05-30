<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-admform — Filament admin create/edit form touch-target
 * floors on mobile.
 *
 * DEFECTS (probed at 390x844 on /admin/pages/create — recurs on every
 * Filament create/edit form across posts, products, categories, users…)
 *
 *   1. `.fi-btn.fi-size-xl` ships at 42px height. Header actions like
 *      "Save & Live Edit" (181x42) and "Save" (81x42) render at the XL
 *      primitive. Filament pins both min-height and height as !important
 *      so the override must reset both (height wins over min-height when
 *      both are set on the same element).
 *
 *   2. `.mw-media-browser-secondary-btn` (the "Upload from your device"
 *      link inside the project-bespoke media browser dropzone) ships at
 *      152x20 — styled as inline-flex link with no padding. Lift to 44px
 *      via inline-flex + min-height + align-items: center.
 *
 *   3. `label.fi-fo-checkbox-list-option` (Filament checkbox-list option
 *      labels in Where to put it / Tags / Menus form sections) ships at
 *      24px. The AI-517 / AI-518 generic `label:has(.fi-checkbox-input)`
 *      pattern does NOT match this specific Filament class — lift via
 *      min-height + inline-flex + align-items: center on the label.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries rules inside the canonical
 *   `@media (max-width: 768px), (pointer: coarse)` block. Each rule
 *   scoped via `body.fi-panel-admin` for specificity bump over
 *   Filament's later-cascade primitive styles.
 */
class AdminAdmformCreatePageTouchTargetContractTest extends TestCase
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
    public function admform_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+\.fi-btn\.fi-size-xl/s',
            $this->cssStripped,
            'fi-btn.fi-size-xl rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function size_xl_button_overrides_both_min_height_and_height(): void
    {
        // The fi-btn.fi-size-xl primitive ships with both height: 42px
        // and min-height: 42px pinned !important — override requires
        // both properties (height wins over min-height when both set).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-btn\.fi-size-xl\s*\{[^}]*min-height:\s*44px\s*!important[^}]*height:\s*auto\s*!important[^}]*\}/s',
            $this->cssStripped,
            'fi-btn.fi-size-xl rule must override both min-height (44px) and height (auto) with !important.'
        );
    }

    #[Test]
    public function media_browser_secondary_btn_lifted_to_44px_inline_flex(): void
    {
        // The "Upload from your device" link ships at 20px (no padding).
        // Lift via inline-flex + align-items + min-height all !important.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-media-browser-secondary-btn\s*\{[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.mw-media-browser-secondary-btn rule must use inline-flex + align-items: center + min-height: 44px all !important.'
        );
    }

    #[Test]
    public function checkbox_list_option_label_lifted_to_44px_hit_area(): void
    {
        // Filament's specific checkbox-list-option label class is NOT
        // covered by the AI-517 / AI-518 generic label:has() pattern.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+label\.fi-fo-checkbox-list-option\s*\{[^}]*min-height:\s*44px\s*!important[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            'label.fi-fo-checkbox-list-option must lift to 44px via min-height + inline-flex + align-items: center all !important.'
        );
    }

    #[Test]
    public function admform_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-admform', $this->css);
    }

    #[Test]
    public function admform_rules_are_scoped_to_admin_panel_not_global(): void
    {
        // Defence: rules must NOT escape the body.fi-panel-admin scope.
        // Checkout / profile panels keep their own button styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-btn\.fi-size-xl\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-btn.fi-size-xl touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-browser-secondary-btn\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-browser-secondary-btn touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*label\.fi-fo-checkbox-list-option\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'label.fi-fo-checkbox-list-option touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
