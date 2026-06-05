<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-sec813 — AI-1202: Filament v5 native Section collapse-button accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/contact-form-module-settings:
 *   Every <button class="fi-icon-btn fi-section-collapse-btn"> rendered by Filament's
 *   collapsible Section header carried NO aria-label, NO title, NO visible text
 *   content. Stock upstream template at vendor/filament/support/resources/views/
 *   components/section/index.blade.php:103-110 instantiates <x-filament::icon-button>
 *   WITHOUT the label prop. AT users heard "button" with no indication of which
 *   section the toggle controls. WCAG 4.1.2 Level A regression.
 *
 * Sister-fix to AI-1199 (Tabs aria-selected), AI-1200 (Toggle aria-checked), AI-1201
 * (Toggle accessible-name) — same Filament v5 vendor-override accessibility-gap
 * family. Lives at the filament namespace path because Spatie
 * PackageServiceProvider->name('filament') in
 * vendor/filament/support/src/SupportServiceProvider.php registers the view namespace
 * as 'filament', NOT 'filament-support'.
 *
 * Fix: inject :label="$mwSectionCollapseLabel" into the <x-filament::icon-button>
 *      invocation. Filament's icon-button component accepts 'label' => null and
 *      merges it as 'aria-label' => $label into the rendered button, also setting
 *      title when no tooltip is present.
 *
 *      Label shape: "Toggle section: <stripped-heading>" when heading is filled,
 *      "Toggle section" otherwise.
 *
 * Verified post-fix via Playwright DOM probe at /admin/contact-form-module-settings:
 *   3 collapse buttons each carry aria-label="Toggle section: <heading>"
 *     - "Toggle section: From Fields"
 *     - "Toggle section: Auto Respond Settings"
 *     - "Toggle section: Advanced"
 *
 * Carries to every Filament v5 collapsible Section surface project-wide.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade comments
 * before negative regression assertions so docblock prose mentioning the legacy
 * label-prop-absent pattern cannot false-fail.
 */
class FilamentSectionSec813AI1202CollapseBtnAccessibleNameContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament/components/section/index.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function override_lives_at_the_correct_filament_namespace_path(): void
    {
        $this->assertFileExists(
            base_path('resources/views/vendor/filament/components/section/index.blade.php'),
            'Vendor override MUST live at resources/views/vendor/filament/components/section/index.blade.php — Spatie PackageServiceProvider->name(\'filament\') in vendor/filament/support/src/SupportServiceProvider.php registers the view namespace as \'filament\', so an override at vendor/filament-support/ silently does not load'
        );

        $this->assertFileDoesNotExist(
            base_path('resources/views/vendor/filament-support/components/section/index.blade.php'),
            'Wrong-namespace override at vendor/filament-support/ must not exist — it would be a no-op and mislead future audits'
        );
    }

    #[Test]
    public function override_computes_collapse_label_with_heading_branch(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwSectionCollapseLabel\s*=\s*\$hasHeading\s*\?\s*\'Toggle section:\s*\'\s*\.\s*trim\(strip_tags\(\(string\)\s*\$heading\)\)\s*:\s*\'Toggle section\'\s*;/s',
            $this->blade,
            'Override MUST compute $mwSectionCollapseLabel via the $hasHeading ternary so each collapse button announces "Toggle section: <heading>" (or fallback "Toggle section" when heading is null) — derived screen-reader label, stripped of any HTML in $heading'
        );
    }

    #[Test]
    public function override_injects_label_prop_into_icon_button_invocation(): void
    {
        $this->assertMatchesRegularExpression(
            '/<x-filament::icon-button[\s\S]*?:label="\$mwSectionCollapseLabel"[\s\S]*?class="fi-section-collapse-btn"/s',
            $this->blade,
            'Override MUST inject :label="$mwSectionCollapseLabel" into the <x-filament::icon-button> invocation that renders .fi-section-collapse-btn so the rendered button carries a non-empty accessible name (ARIA 1.2 requirement for icon-only buttons; Filament icon-button merges label into aria-label)'
        );
    }

    #[Test]
    public function label_is_dynamic_not_hardcoded_string(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/:label="\'[^\']*\'"/s',
            $this->bladeStripped,
            'label prop MUST be wired to $mwSectionCollapseLabel (dynamic per-section heading), NOT a hardcoded string literal — otherwise every collapse button announces the same name'
        );
    }

    #[Test]
    public function override_preserves_collapsible_branch_guard(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$collapsible\s*\)\s*<x-filament::icon-button/s',
            $this->blade,
            'Override MUST preserve the @if ($collapsible) guard around the icon-button so non-collapsible sections do not render a phantom collapse toggle'
        );
    }

    #[Test]
    public function override_preserves_icon_button_upstream_props(): void
    {
        $upstreamProps = [
            'color="gray"',
            ':icon="\\Filament\\Support\\Icons\\Heroicon::ChevronUp"',
            ':icon-alias="\\Filament\\Support\\View\\SupportIconAlias::SECTION_COLLAPSE_BUTTON"',
            'x-on:click.stop="isCollapsed = ! isCollapsed"',
            'class="fi-section-collapse-btn"',
        ];

        foreach ($upstreamProps as $prop) {
            $this->assertStringContainsString(
                $prop,
                $this->blade,
                sprintf(
                    'Override MUST preserve the upstream icon-button prop %s — dropping it would break the collapse-button wiring',
                    $prop
                )
            );
        }
    }

    #[Test]
    public function override_preserves_section_x_data_isCollapsed_state(): void
    {
        $this->assertStringContainsString(
            'isCollapsed:',
            $this->blade,
            'Override MUST preserve the x-data isCollapsed initializer so the toggle Alpine state wiring still works'
        );

        $this->assertStringContainsString(
            '$persist',
            $this->blade,
            'Override MUST preserve the $persist(...) branch so persistCollapsed sections still remember their state across reloads'
        );
    }

    #[Test]
    public function override_preserves_collapse_window_event_listeners(): void
    {
        $events = [
            'x-on:collapse-section.window',
            'x-on:expand',
            'x-on:expand-section.window',
            'x-on:open-section.window',
            'x-on:toggle-section.window',
        ];

        foreach ($events as $event) {
            $this->assertStringContainsString(
                $event,
                $this->blade,
                sprintf(
                    'Override MUST preserve the %s listener so cross-component collapse/expand events still flow',
                    $event
                )
            );
        }
    }

    #[Test]
    public function override_preserves_aria_expanded_on_content_region(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-bind:aria-expanded="\(!\s*isCollapsed\)\.toString\(\)"/s',
            $this->blade,
            'Override MUST preserve x-bind:aria-expanded on the fi-section-content-ctn so AT users hear the expanded/collapsed state of the section content region'
        );
    }

    #[Test]
    public function override_preserves_header_branches_and_slots(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$hasHeader\s*\)\s*<header/s',
            $this->blade,
            'Override MUST preserve the @if ($hasHeader) <header> branch so sections with no header content still skip the header block'
        );

        $this->assertStringContainsString(
            '$afterHeader',
            $this->blade,
            'Override MUST preserve the $afterHeader slot rendering so existing consumers (custom header actions) keep working'
        );

        $this->assertStringContainsString(
            '$footer',
            $this->blade,
            'Override MUST preserve the $footer slot rendering so existing consumers keep working'
        );

        $this->assertStringContainsString(
            'fi-section-content',
            $this->blade,
            'Override MUST preserve the .fi-section-content wrapper so existing CSS targeting it keeps working'
        );
    }

    #[Test]
    public function legacy_icon_button_without_label_pattern_absent(): void
    {
        $iconButtonBlock = '';
        if (preg_match('/<x-filament::icon-button(.*?)\/>/s', $this->bladeStripped, $m)) {
            $iconButtonBlock = $m[1];
        }

        $this->assertNotSame(
            '',
            $iconButtonBlock,
            'Override MUST contain a <x-filament::icon-button .../> invocation'
        );

        $this->assertStringContainsString(
            ':label=',
            $iconButtonBlock,
            'Legacy upstream icon-button invocation without the :label prop (which leaves the rendered button with no accessible name) MUST NOT return — WCAG 4.1.2 Level A regression guard'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1202',
            $this->blade,
            'Override MUST carry the AI-1202 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-sec813',
            $this->blade,
            'Override MUST carry the task-2026-05-31-sec813 marker for grep-ability'
        );

        $this->assertStringContainsString(
            'AI-1201',
            $this->blade,
            'Override SHOULD cite AI-1201 in the docblock so the sister-fix lineage (Toggle accessible-name + Tabs aria-selected + Toggle aria-checked) is discoverable from this file'
        );
    }
}
