<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-leinput — .form-control-live-edit-input touch-target
 * floor inside admin panel forms on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/pages/create)
 *
 *   .form-control-live-edit-input inside the mw-tree-component content
 *   picker ships at 247x36 — 8px under the 44px floor. The class is
 *   shared across MicroweberUI components, Module admin options,
 *   filepicker URL and AI prompt fields, and template-settings sidebar
 *   selects.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors sibling task-2026-05-30-mediatap + mediafil + mediacb +
 *   fitabs blocks). Live Edit canvas iframe (body.mw-live-edit-page) is
 *   a separate body context and intentionally not touched here.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminLeinputLiveEditInputHeightContractTest extends TestCase
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
    public function leinput_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.form-control-live-edit-input/s',
            $this->cssStripped,
            '.form-control-live-edit-input rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function live_edit_input_lifted_to_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.form-control-live-edit-input\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.form-control-live-edit-input rule must lift min-height to 44px !important.'
        );
    }

    #[Test]
    public function leinput_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.form-control-live-edit-input/s',
            $this->cssStripped,
            'leinput rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function leinput_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-leinput', $this->css);
    }

    #[Test]
    public function leinput_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rule must NOT escape the panel scope. Live Edit canvas
        // (body.mw-live-edit-page) keeps its own input visual rhythm.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.form-control-live-edit-input\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            '.form-control-live-edit-input touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
