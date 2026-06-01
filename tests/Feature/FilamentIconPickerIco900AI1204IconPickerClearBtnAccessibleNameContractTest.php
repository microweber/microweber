<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-ico900 — AI-1204: Filament v5 mw-icon-picker clear-button
 * accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/btn-module-settings:
 *   The clear-button rendered by the icon-picker (the small button next to
 *   "Pick icon", visible only when an icon is selected) carried NO aria-label,
 *   NO title, NO visible text content. It contained only a heroicon-o-x-circle
 *   SVG via the at-svg directive. AT users heard "button" with no indication
 *   that it removes the chosen icon. WCAG 4.1.2 Level A regression.
 *
 * Sister-fix to AI-1199 / AI-1200 / AI-1201 / AI-1202 / AI-1203 — same Filament v5
 * native-component accessibility-gap family.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade comments
 * before negative regression assertions so docblock prose mentioning the legacy
 * label-absent pattern cannot false-fail.
 */
class FilamentIconPickerIco900AI1204IconPickerClearBtnAccessibleNameContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-icon-picker.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function blade_file_exists(): void
    {
        $this->assertFileExists(
            base_path('src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-icon-picker.blade.php'),
            'mw-icon-picker blade MUST exist at the canonical path'
        );
    }

    #[Test]
    public function php_declares_remove_icon_label_via_translation_helper(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwAi1204RemoveIconLabel\s*=\s*__\(\s*[\'"]Remove icon[\'"]\s*\)\s*;/',
            $this->blade,
            'Blade @php block MUST declare $mwAi1204RemoveIconLabel via the Microweber __() translation helper so the accessible name is localizable'
        );
    }

    #[Test]
    public function clear_button_carries_aria_label_bound_to_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/<x-filament::button[\s\S]*?x-show="state"[\s\S]*?:aria-label="\$mwAi1204RemoveIconLabel"[\s\S]*?<\/x-filament::button>/',
            $this->blade,
            'Clear button MUST carry :aria-label="$mwAi1204RemoveIconLabel" so screen-reader users hear "Remove icon" instead of just "button" — WCAG 4.1.2 Level A'
        );
    }

    #[Test]
    public function clear_button_carries_title_bound_to_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/<x-filament::button[\s\S]*?x-show="state"[\s\S]*?:title="\$mwAi1204RemoveIconLabel"[\s\S]*?<\/x-filament::button>/',
            $this->blade,
            'Clear button MUST carry :title="$mwAi1204RemoveIconLabel" so mouse users see the hover tooltip — defence-in-depth with aria-label'
        );
    }

    #[Test]
    public function clear_button_decorative_svg_marked_aria_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '/@svg\(\s*[\'"]heroicon-o-x-circle[\'"]\s*,\s*\[[^\]]*[\'"]aria-hidden[\'"]\s*=>\s*[\'"]true[\'"][^\]]*\]\s*\)/',
            $this->blade,
            'Decorative SVG MUST carry aria-hidden="true" — otherwise screen-readers may double-announce the icon alongside the button label'
        );
    }

    #[Test]
    public function clear_button_decorative_svg_marked_focusable_false(): void
    {
        $this->assertMatchesRegularExpression(
            '/@svg\(\s*[\'"]heroicon-o-x-circle[\'"]\s*,\s*\[[^\]]*[\'"]focusable[\'"]\s*=>\s*[\'"]false[\'"][^\]]*\]\s*\)/',
            $this->blade,
            'Decorative SVG MUST carry focusable="false" — IE/Edge legacy bug otherwise focuses the SVG itself when the parent button is tabbed'
        );
    }

    #[Test]
    public function label_is_dynamic_not_hardcoded_literal(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/:aria-label="[\'"]Remove icon[\'"]"/',
            $this->bladeStripped,
            'aria-label MUST bind to $mwAi1204RemoveIconLabel (translatable via __()) — NOT a hardcoded literal string that bypasses i18n'
        );
    }

    #[Test]
    public function pick_icon_button_text_preserved(): void
    {
        $this->assertStringContainsString(
            'Pick icon',
            $this->blade,
            'Primary "Pick icon" button text MUST be preserved — it provides the accessible name for the picker trigger'
        );
    }

    #[Test]
    public function alpine_x_data_state_wiring_preserved(): void
    {
        $this->assertStringContainsString(
            "\$entangle",
            $this->blade,
            'Alpine x-data state-entangle wiring MUST be preserved — the picker depends on $wire entanglement to round-trip the icon class to Livewire state'
        );
    }

    #[Test]
    public function alpine_x_show_and_x_on_click_clear_preserved(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-show="state"[\s\S]*?x-on:click="state = \'\'"/',
            $this->blade,
            'Clear button MUST preserve x-show="state" + x-on:click="state = \'\'" — these are the Alpine wiring that makes the clear-X visible only when an icon is selected, and resets state on click'
        );
    }

    #[Test]
    public function icon_picker_promise_wiring_preserved(): void
    {
        $this->assertStringContainsString(
            'mw.app.iconPicker.pickIcon',
            $this->blade,
            'The mw.app.iconPicker.pickIcon API wiring MUST be preserved — this is the canonical picker trigger'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1204',
            $this->blade,
            'Blade MUST carry the AI-1204 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-ico900',
            $this->blade,
            'Blade MUST carry the task-2026-05-31-ico900 marker for grep-ability'
        );

        $this->assertStringContainsString(
            'AI-1203',
            $this->blade,
            'Blade SHOULD cite AI-1203 in the docblock so the sister-fix lineage is discoverable from this file'
        );
    }
}
