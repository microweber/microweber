<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-0752fa — AI-817 follow-up: shared mw-color-picker close-X
 * accessible-name restoration. Carries to every ColorPicker consumer
 * (Logo, Social-links, Background, Theme).
 *
 * Pre-fix state (probed via Playwright in Social-links module-settings,
 * Design tab > Icon Styles > Icon Color / Icon Hover Color color pickers):
 *   - <button type="button" x-on:click="togglePanelVisibility()"> close-X
 *     rendered with no text + no aria-label + no title
 *   - <svg> child had no aria-hidden / focusable attributes
 *   - Screen readers announced "button" with no purpose (WCAG 4.1.2 Level A)
 *
 * Fix: src/MicroweberPackages/Filament/resources/views/filament-forms/
 *      components/mw-color-picker.blade.php
 *
 *   1. Add aria-label="Close color picker" + title="Close color picker"
 *      on the <button> wrapper
 *   2. Add aria-hidden="true" + focusable="false" on the <svg> so AT
 *      ignores the decorative icon and reads the button label only
 *
 * Carry: every ColorPicker on every module-settings surface inherits the
 * fix (Social-links Icon Color + Icon Hover Color confirmed via Playwright
 * 2026-05-31; Background + Theme + future consumers carry by inheritance).
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade
 * comments before negative regression assertions so docblock prose
 * mentioning the legacy unlabeled-button pattern cannot false-fail.
 */
class ColorPicker0752faAI817ClosePanelAriaLabelContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-color-picker.blade.php'
        ));
    }

    private function stripBladeComments(string $source): string
    {
        return (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);
    }

    #[Test]
    public function close_button_carries_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button\b[^>]*\baria-label="Close color picker"/s',
            $this->blade,
            'Close-X button must carry aria-label="Close color picker" for AT announcement (WCAG 4.1.2 Level A)'
        );
    }

    #[Test]
    public function close_button_carries_title_attribute(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button\b[^>]*\btitle="Close color picker"/s',
            $this->blade,
            'Close-X button must carry title="Close color picker" for sighted-user mouse-hover affordance'
        );
    }

    #[Test]
    public function close_button_svg_is_hidden_from_at(): void
    {
        $this->assertMatchesRegularExpression(
            '/<svg\b[^>]*\baria-hidden="true"[^>]*>/s',
            $this->blade,
            'Decorative <svg> inside close-X must carry aria-hidden="true" so AT reads button label only, not the SVG'
        );

        $this->assertMatchesRegularExpression(
            '/<svg\b[^>]*\bfocusable="false"[^>]*>/s',
            $this->blade,
            'Decorative <svg> inside close-X must carry focusable="false" so it stays out of the tab order'
        );
    }

    #[Test]
    public function aria_label_and_click_handler_belong_to_same_button(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button\b[^>]*x-on:click="togglePanelVisibility\(\)"[^>]*aria-label="Close color picker"|<button\b[^>]*aria-label="Close color picker"[^>]*x-on:click="togglePanelVisibility\(\)"/s',
            $this->blade,
            'aria-label and the x-on:click="togglePanelVisibility()" handler must live on the SAME <button> element'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-817',
            $this->blade,
            'blade must carry the AI-817 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31',
            $this->blade,
            'blade must carry the task-2026-05-31 marker for grep-ability across surfaces'
        );
    }

    #[Test]
    public function legacy_unlabeled_close_button_pattern_absent(): void
    {
        $stripped = $this->stripBladeComments($this->blade);

        $this->assertDoesNotMatchRegularExpression(
            '/<button\s+type="button"\s+x-on:click="togglePanelVisibility\(\)"\s+class="text-gray-400[^"]*"\s*>\s*<svg\s+class="w-4 h-4"\s+fill="none"\s+stroke="currentColor"\s+viewBox="0 0 24 24">/s',
            $stripped,
            'Legacy unlabeled <button>...<svg> pattern (no aria-label, no title, no aria-hidden on svg) must not return — WCAG 4.1.2 Level A regression guard'
        );
    }
}
