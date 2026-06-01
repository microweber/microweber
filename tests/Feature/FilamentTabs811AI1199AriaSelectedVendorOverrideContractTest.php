<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-tabs811 — AI-1199: Filament v5 native Tabs aria-selected gap.
 *
 * Pre-fix state probed via Playwright at /admin/layouts-module-settings:
 *   Stock vendor <x-filament::tabs.item> (vendor/filament/support/.../tabs/item.blade.php)
 *   merged 'aria-selected' => $active where $active defaults to boolean false.
 *   Blade drops boolean-false attributes from rendered HTML, so the DOM reported
 *   ariaSelected: null on EVERY non-active tab. When $hasAlpineActiveClasses was
 *   true (Alpine-driven state, the live-edit Tabs case), vendor bound only
 *   x-bind:class — never x-bind:aria-selected — so even when the active tab
 *   toggled in JS, AT never heard "selected".
 *
 * Fix: project-side vendor override at resources/views/vendor/filament/components/
 *      tabs/item.blade.php (note namespace path is the-package-name-not-the-laravel-style
 *      package slug because Spatie PackageServiceProvider->name('filament') in
 *      vendor/filament/support/src/SupportServiceProvider.php registers the view
 *      namespace as 'filament', NOT 'filament-support'; this Test pins the path so
 *      future audits cannot move the file back to the wrong vendor folder).
 *
 *   1. Static-active path: computed $staticAriaSelected = $active ? 'true' : 'false'
 *      merged as a string literal so Blade keeps the attribute on render.
 *   2. Alpine-driven path: x-bind:aria-selected="((<alpineActive>) ? 'true' : 'false')"
 *      so AT hears the role state change every time the active tab toggles.
 *
 * Carries to every Filament v5 Tabs surface project-wide (admin resource Tabs,
 * live-edit module-settings Tabs, future consumers).
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade comments
 * before negative regression assertions so docblock prose mentioning the legacy
 * boolean-false merge pattern cannot false-fail.
 */
class FilamentTabs811AI1199AriaSelectedVendorOverrideContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament/components/tabs/item.blade.php'
        ));
        $this->bladeStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
    }

    #[Test]
    public function override_lives_at_the_correct_filament_namespace_path(): void
    {
        $this->assertFileExists(
            base_path('resources/views/vendor/filament/components/tabs/item.blade.php'),
            'Vendor override MUST live at resources/views/vendor/filament/components/tabs/item.blade.php — Spatie PackageServiceProvider->name(\'filament\') in vendor/filament/support/src/SupportServiceProvider.php registers the view namespace as \'filament\', so an override at vendor/filament-support/ silently does not load'
        );

        $this->assertFileDoesNotExist(
            base_path('resources/views/vendor/filament-support/components/tabs/item.blade.php'),
            'Wrong-namespace override at vendor/filament-support/ must not exist — it would be a no-op and mislead future audits'
        );
    }

    #[Test]
    public function override_computes_static_aria_selected_string(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$staticAriaSelected\s*=\s*\$active\s*\?\s*\'true\'\s*:\s*\'false\'\s*;/s',
            $this->blade,
            'Override MUST compute $staticAriaSelected as a string literal so Blade keeps the attribute (Blade drops boolean-false merges from the rendered HTML)'
        );
    }

    #[Test]
    public function override_merges_computed_static_string_into_attributes(): void
    {
        $this->assertMatchesRegularExpression(
            '/->merge\(\[[^\]]*?\'aria-selected\'\s*=>\s*\$staticAriaSelected/s',
            $this->blade,
            '$attributes->merge() MUST carry aria-selected => $staticAriaSelected (the computed string), not $active (the boolean)'
        );
    }

    #[Test]
    public function override_binds_alpine_aria_selected_reactively(): void
    {
        $this->assertMatchesRegularExpression(
            '/x-bind:aria-selected="\(\(\{\{\s*\$alpineActive\s*\}\}\)\s*\?\s*\'true\'\s*:\s*\'false\'\)"/s',
            $this->blade,
            'Alpine-driven path MUST bind x-bind:aria-selected so the role state changes are announced by AT every time the active tab toggles'
        );
    }

    #[Test]
    public function alpine_class_and_aria_selected_bindings_are_co_located(): void
    {
        $matched = preg_match(
            '/@if\s*\(\s*\$hasAlpineActiveClasses\s*\)([\s\S]+?)@endif/',
            $this->blade,
            $m
        );
        $this->assertSame(1, $matched, 'Override MUST guard the Alpine bindings with @if ($hasAlpineActiveClasses)');

        $this->assertStringContainsString(
            'x-bind:class',
            $m[1],
            'x-bind:class MUST stay inside the Alpine guard'
        );
        $this->assertStringContainsString(
            'x-bind:aria-selected',
            $m[1],
            'x-bind:aria-selected MUST live inside the SAME @if ($hasAlpineActiveClasses) guard as x-bind:class so AT hears state changes only on Alpine-driven surfaces'
        );
    }

    #[Test]
    public function legacy_boolean_false_merge_pattern_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\'aria-selected\'\s*=>\s*\$active\s*,/s',
            $this->bladeStripped,
            'Legacy stock-vendor pattern aria-selected => $active (where $active defaults to boolean false and Blade drops it) MUST NOT return — WCAG 4.1.2 Level A regression guard'
        );
    }

    #[Test]
    public function task_markers_present_in_blade(): void
    {
        $this->assertStringContainsString(
            'AI-1199',
            $this->blade,
            'Override MUST carry the AI-1199 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-tabs811',
            $this->blade,
            'Override MUST carry the task-2026-05-31-tabs811 marker for grep-ability'
        );
    }
}
