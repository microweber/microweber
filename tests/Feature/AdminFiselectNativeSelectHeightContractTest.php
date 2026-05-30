<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-fiselect — Filament v5 native .fi-select-input
 * height touch-target floor on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/newsletter/subscribers)
 *
 *   <select class="fi-select-input"> on the bulk-upload field-mapping
 *   form ships at 536x36 — 8px short of the 44px floor. .fi-select-input
 *   is Filament v5's canonical class for native <select> components so
 *   the defect is cross-cutting across every admin resource using a
 *   native Filament select form component.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors sibling task-2026-05-30-mediatap + mediafil + mediacb +
 *   fitabs + leinput blocks). Checkout panel keeps its own existing
 *   .fi-select-input rule (uses --touch-target-min token). Pagination
 *   records-per-page select keeps its narrow override.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminFiselectNativeSelectHeightContractTest extends TestCase
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
        // selectors does not satisfy positive or negative assertions on its
        // own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function fiselect_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.fi-select-input/s',
            $this->cssStripped,
            '.fi-select-input rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function fi_select_input_lifted_to_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-select-input\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.fi-select-input rule must lift min-height to 44px !important.'
        );
    }

    #[Test]
    public function fiselect_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix. A plain class selector body.fi-panel-admin would NOT
        // match body.fi-panel-admin-newsletter.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-select-input/s',
            $this->cssStripped,
            'fiselect rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function fiselect_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-fiselect', $this->css);
    }

    #[Test]
    public function fiselect_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rule must NOT escape the panel scope. Checkout / profile
        // panels keep their own .fi-select-input styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-select-input\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            '.fi-select-input touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
