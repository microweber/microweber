<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-0752fa — AI-817 follow-up: slider label binding + noUi-handle
 * aria-label propagation.
 *
 * Pre-fix state (probed via Playwright in Logo module-settings iframe):
 *   - <label> rendered with no `id` and no `for` attribute → orphaned label
 *     semantics (WCAG 1.3.1 Level A)
 *   - noUi-handle thumbs carried role="slider" + valid aria-valuenow/min/max
 *     BUT empty aria-label/aria-labelledby → screen readers announced
 *     "slider, 0 of 600" with no purpose (WCAG 4.1.2 Level A)
 *
 * Fix: shared MwInputSliderGroup blade template
 *   src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-input-slider.blade.php
 *
 *   1. Generate $sliderLabelId = $sliderId . "-label"
 *   2. Render <label id="{labelId}" for="{sliderId}">{label}</label>
 *   3. Pass labelId + labelText into Alpine x-data
 *   4. After noUiSlider.create(...), iterate .noUi-handle children and
 *      setAttribute('aria-labelledby', labelId) — fallback to aria-label
 *      when labelId doesn't resolve
 *
 * Carry: every module rendering MwInputSliderGroup inherits the fix
 * (Logo "Set Size" + "Set Font Size" confirmed via Playwright 2026-05-31).
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade
 * comments before negative regression assertions so docblock prose
 * mentioning the legacy empty-label pattern cannot false-fail.
 */
class Slider0752faAI817SliderAriaLabelContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-input-slider.blade.php'
        ));
    }

    private function stripBladeComments(string $source): string
    {
        return (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);
    }

    #[Test]
    public function php_block_derives_label_id_from_slider_id(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$sliderLabelId\s*=\s*\$sliderId\s*\.\s*["\']-label["\']/',
            $this->blade,
            'mw-input-slider.blade.php must derive $sliderLabelId from $sliderId for stable label↔control binding'
        );
    }

    #[Test]
    public function label_renders_with_id_and_for_attributes(): void
    {
        $this->assertMatchesRegularExpression(
            '/<label\s+id="\{\{\s*\$sliderLabelId\s*\}\}"\s+for="\{\{\s*\$sliderId\s*\}\}">/',
            $this->blade,
            '<label> must carry id={{ $sliderLabelId }} + for={{ $sliderId }} to bind label↔slider for AT (WCAG 1.3.1)'
        );
    }

    #[Test]
    public function alpine_xdata_carries_labelId_and_labelText_properties(): void
    {
        $this->assertStringContainsString(
            "labelId: @js(\$sliderLabelId)",
            $this->blade,
            'Alpine x-data must expose labelId for handle aria-labelledby propagation'
        );

        $this->assertStringContainsString(
            "labelText: @js(\$getLabel() ?: '')",
            $this->blade,
            'Alpine x-data must expose labelText as fallback when labelId cannot be resolved'
        );
    }

    #[Test]
    public function init_block_propagates_aria_labelledby_onto_noui_handles(): void
    {
        $this->assertMatchesRegularExpression(
            "/querySelectorAll\\(['\"]\\.noUi-handle['\"]\\)/",
            $this->blade,
            'init() must query .noUi-handle children of the slider component'
        );

        $this->assertMatchesRegularExpression(
            "/handle\\.setAttribute\\(['\"]aria-labelledby['\"],\\s*this\\.labelId\\)/",
            $this->blade,
            'init() must setAttribute(aria-labelledby, labelId) on every .noUi-handle for AT announcement'
        );

        $this->assertMatchesRegularExpression(
            "/handle\\.setAttribute\\(['\"]aria-label['\"],\\s*this\\.labelText\\)/",
            $this->blade,
            'init() must fall back to aria-label = labelText when labelId does not resolve'
        );
    }

    #[Test]
    public function init_block_propagates_after_nouislider_create(): void
    {
        $createPos = strpos($this->blade, 'noUiSlider.create(this.component');
        $forEachPos = strpos($this->blade, '.noUi-handle');

        $this->assertNotFalse($createPos, 'noUiSlider.create(...) call must exist');
        $this->assertNotFalse($forEachPos, '.noUi-handle querySelectorAll must exist');
        $this->assertGreaterThan(
            $createPos,
            $forEachPos,
            'handle iteration must run AFTER noUiSlider.create(...) — handles do not exist before create'
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
    public function legacy_orphaned_label_pattern_absent(): void
    {
        $stripped = $this->stripBladeComments($this->blade);

        $this->assertDoesNotMatchRegularExpression(
            '/<label>\s*\{\{\s*\$getLabel\(\)\s*\}\}\s*<\/label>/',
            $stripped,
            'Legacy orphaned <label>{{ $getLabel() }}</label> pattern (no id, no for) must not return — WCAG 1.3.1 Level A regression guard'
        );
    }
}
