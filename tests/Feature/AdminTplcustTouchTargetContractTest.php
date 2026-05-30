<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-tplcust — Admin /template-customization touch-target
 * floor on mobile (390x844 probe).
 *
 * DEFECT (probed at 390x844 on /admin/template-customization)
 *
 *   8 interactive elements rendered under the 44x44 WCAG 2.5.5 floor:
 *     - 3 Filament Actions (Reset to Defaults / Export Settings / Import
 *       Settings) at Size::Large rendered at 98x42 (2px short).
 *     - 2 x-filament::button size="sm" (Refresh Preview / View Live Site)
 *       rendered at ~36px (8px short).
 *     - 2 zoom +/- icon buttons rendered at 28x28 (16px short on each axis).
 *     - 1 Reload button at ~32px (12px short).
 *     - 3 device-view toggle icon buttons (desktop/tablet/mobile) at 28x28.
 *
 * FIX
 *   Inline style attribute injection WITH !important flag — guaranteed
 *   cascade-winner over Filament's own .fi-btn.fi-size-lg / .fi-size-sm
 *   rules (which themselves use !important), NO dependency on Tailwind
 *   JIT compile. Tier-3 probe confirmed two things: (1) Tailwind JIT did
 *   NOT compile a fresh min-h-[44px] arbitrary class added to this Blade
 *   (pre-existing min-w-[60px] in the same file DID compile — so the
 *   issue is specifically "new arbitrary value added post-build", not
 *   "this file is unscanned"). (2) Bare inline style without !important
 *   was overridden by Filament's own !important min-height declarations
 *   (DOM showed inlineStyle="min-height:44px;" but computed min-height
 *   stayed at 36px for size="sm" buttons). The !important flag is
 *   mandatory.
 *
 *     - 3 Actions in AdminTemplateCustomizerPage.php carry
 *       ->extraAttributes(['style' => 'min-height:44px !important;']).
 *     - 2 x-filament::button carry style="min-height:44px !important;".
 *     - 2 zoom buttons + 3 device toggle buttons carry
 *       style="min-width:44px !important;min-height:44px !important;"
 *       alongside the existing p-1.5.
 *     - 1 Reload button carries style="min-height:44px !important;"
 *       alongside px-2.5 py-1.5.
 *
 * SCOPE NOTE
 *   Surgical inline-style fix chosen over a broad mobile-touch.css rule
 *   because (a) no body class scopes this page individually beyond
 *   fi-panel-admin, (b) project rule prohibits broad fi-btn rules without
 *   explicit dispatch, (c) the inline-style approach has zero build-step
 *   dependency.
 */
class AdminTplcustTouchTargetContractTest extends TestCase
{
    private string $php;
    private string $phpStripped;
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->php = (string) file_get_contents(base_path(
            'Modules/Settings/Filament/Pages/AdminTemplateCustomizerPage.php'
        ));
        // Strip PHP block + line comments (selector-self-match guard per
        // task-7aa48a UNIFORMITY-RULE).
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->php);
        $this->phpStripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->blade = (string) file_get_contents(base_path(
            'Modules/Settings/resources/views/filament/admin/pages/template-customizer.blade.php'
        ));
        // Strip Blade {{-- ... --}} + PHP block comments before negative
        // assertions so docblock prose mentioning attribute strings does not
        // satisfy the source-presence assertions.
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $stripped);
        $this->bladeStripped = $stripped;
    }

    #[Test]
    public function reset_to_defaults_action_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            "/Action::make\('resetToDefaults'\)[\s\S]*?->extraAttributes\(\['style'\s*=>\s*'min-height:44px !important;'\]\)/",
            $this->phpStripped,
            'Reset to Defaults action must carry extraAttributes style min-height:44px !important.'
        );
    }

    #[Test]
    public function export_settings_action_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            "/Action::make\('exportSettings'\)[\s\S]*?->extraAttributes\(\['style'\s*=>\s*'min-height:44px !important;'\]\)/",
            $this->phpStripped,
            'Export Settings action must carry extraAttributes style min-height:44px !important.'
        );
    }

    #[Test]
    public function import_settings_action_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            "/Action::make\('importSettings'\)[\s\S]*?->extraAttributes\(\['style'\s*=>\s*'min-height:44px !important;'\]\)/",
            $this->phpStripped,
            'Import Settings action must carry extraAttributes style min-height:44px !important.'
        );
    }

    #[Test]
    public function refresh_preview_filament_button_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/wire:click="\$dispatch\(\'refreshPreview\'\)"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'Refresh Preview x-filament::button must carry style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function view_live_site_filament_button_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/href="\{\{\s*site_url\(\)\s*\}\}"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'View Live Site x-filament::button must carry style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function zoom_minus_button_carries_inline_min_44_square(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-on:click="scale = Math\.max\(0\.5, scale - 0\.1\)"[\s\S]*?style="min-width:44px !important;min-height:44px !important;"/',
            $this->bladeStripped,
            'Zoom-out button must carry inline style="min-width:44px !important;min-height:44px !important;".'
        );
    }

    #[Test]
    public function zoom_plus_button_carries_inline_min_44_square(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-on:click="scale = Math\.min\(1\.0, scale \+ 0\.1\)"[\s\S]*?style="min-width:44px !important;min-height:44px !important;"/',
            $this->bladeStripped,
            'Zoom-in button must carry inline style="min-width:44px !important;min-height:44px !important;".'
        );
    }

    #[Test]
    public function reload_button_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-on:click="refreshFrame\(\)"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'Reload button must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function device_toggle_buttons_carry_inline_min_44_square(): void
    {
        // Pin all 3 device toggle buttons (desktop / tablet / mobile) by
        // their distinct title attribute.
        foreach (['Desktop View', 'Tablet View', 'Mobile View'] as $title) {
            $this->assertMatchesRegularExpression(
                '/style="min-width:44px !important;min-height:44px !important;"[^>]*title="' . preg_quote($title, '/') . '"/',
                $this->bladeStripped,
                "Device toggle button (title=\"{$title}\") must carry inline style=\"min-width:44px !important;min-height:44px !important;\"."
            );
        }
    }

    #[Test]
    public function tplcust_task_marker_present_in_php_and_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-30-tplcust', $this->php);
        $this->assertStringContainsString('task-2026-05-30-tplcust', $this->blade);
    }

    #[Test]
    public function no_legacy_28x28_zoom_button_remains(): void
    {
        // Defence: pre-fix shape was `p-1.5` on the icon buttons with NO
        // min-w / min-h companion (neither Tailwind class NOR inline style).
        // Walk each <button>...</button> element containing an
        // `x-on:click="scale = Math.(max|min)..."` handler and assert it
        // carries the inline 44x44 style. Slice-per-element scan avoids
        // PCRE backtracking ambiguity from a single greedy regex.
        if (!preg_match_all('~<button\b[^>]*x-on:click="scale = Math\.(?:max|min)[^"]*"[^>]*>~', $this->bladeStripped, $matches)) {
            $this->fail('Expected 2 zoom buttons in template-customizer.blade.php (none found).');
        }
        $this->assertCount(2, $matches[0], 'Expected exactly 2 zoom buttons (zoom-out + zoom-in).');
        foreach ($matches[0] as $tag) {
            $this->assertStringContainsString(
                'style="min-width:44px !important;min-height:44px !important;"',
                $tag,
                "Legacy 28x28 zoom button shape detected — missing inline 44x44 !important style:\n{$tag}"
            );
        }
    }

    #[Test]
    public function pivot_rationale_documented_in_docblock(): void
    {
        // Lock in the rationale comment so a future contributor doesn't
        // revert the pivot back to class="min-h-[44px]" without first
        // reading why inline style is load-bearing here.
        $this->assertStringContainsString(
            'Tailwind JIT',
            $this->blade,
            'Blade must carry a docblock explaining why inline style was chosen over a Tailwind class.'
        );
    }
}
