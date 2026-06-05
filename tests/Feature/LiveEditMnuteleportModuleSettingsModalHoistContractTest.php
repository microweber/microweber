<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-mnumodal-teleport — Live-edit module-settings modal hit-test
 * escape (teleport .fi-modal to <body>).
 *
 * Every live-edit Module Settings page renders, inside the live-edit slide-over,
 * as a NESTED iframe through the shared layout
 * filament/components/layout/live-edit-module-settings.blade.php. A module's
 * Filament action modal (Menu "Add menu item"/"Edit", Accordion/Tabs "Create
 * with AI", Content "New content", …) mounts DEEP inside the settings form
 * schema. In that nested position the modal's fixed .fi-modal-window-ctn is
 * composited away from its layout box, so elementFromPoint at the modal inputs
 * returns <body> and REAL CLICKS never reach the fields — the dialog renders but
 * is completely un-usable. Verified in-browser pre-fix on the Menu module: a real
 * mouse click at an input's exact coordinates left document.activeElement on
 * <body>; post-fix the same click focuses the input.
 *
 * The fix (mirroring the proven canvas teleport in iframe-page.blade.php) hoists
 * every nested .fi-modal up to be a direct child of <body> on each Livewire
 * commit, and is hardened against MULTIPLE / NESTED / STACKED modals and the
 * two-Livewire-component layout:
 *   - hoists ALL nested modals (querySelectorAll, not querySelector);
 *   - prunes a hoisted copy ONLY when no Filament action is mounted anywhere AND
 *     the modal has no visibly-rendered window (so an unrelated commit can never
 *     yank an open dialog, and a closed dialog never orphans at <body>).
 *
 * Verified in-browser across Menu, Accordion and Tabs module settings: the
 * create/edit dialog is hoisted to <body> and its first input is hit-testable.
 *
 * Because the hook lives in the SHARED layout and selects modals purely by
 * structure (.mw-live-edit-page-wrapper .fi-modal), it covers every module's
 * settings modal without per-module code.
 */
class LiveEditMnuteleportModuleSettingsModalHoistContractTest extends TestCase
{
    private string $layout;

    /** Source with JS/Blade comments stripped, for "absence" assertions. */
    private string $code;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php'
        ));

        // Pre-strip comments so negative assertions can't self-match on prose
        // (selector-self-match guard).
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->layout);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $stripped);
        $this->code = $stripped;
    }

    #[Test]
    public function hoists_every_nested_modal_to_body(): void
    {
        // querySelectorAll (ALL nested modals — stack-safe), not querySelector.
        $this->assertMatchesRegularExpression(
            "/querySelectorAll\(\s*'\.fi-modal'\s*\)[\s\S]{0,120}document\.body\.appendChild/",
            $this->code,
            'The hook must hoist every nested .fi-modal out to <body> via querySelectorAll + appendChild.'
        );
        $this->assertStringContainsString(".mw-live-edit-page-wrapper", $this->code,
            'The hook must scope the nested-modal lookup to the settings wrapper.');
    }

    #[Test]
    public function prune_is_gated_on_no_mounted_action_and_no_visible_window(): void
    {
        // Prune only when nothing is mounted...
        $this->assertMatchesRegularExpression(
            "/if\s*\(\s*!mwAnyActionMounted\(\)\s*\)/",
            $this->code,
            'Pruning must be gated on mwAnyActionMounted() being false.'
        );
        // ...AND the candidate modal has no visible window.
        $this->assertMatchesRegularExpression(
            "/if\s*\(\s*!mwModalHasVisibleWindow\(m\)\s*\)\s*\{\s*m\.remove\(\)/",
            $this->code,
            'A hoisted modal may only be removed when it has no visibly-rendered window.'
        );
    }

    #[Test]
    public function mounted_action_check_covers_all_action_buckets(): void
    {
        foreach (['mountedActions', 'mountedTableActions', 'mountedFormComponentActions',
            'mountedInfolistActions', 'mountedTableBulkActions'] as $key) {
            $this->assertStringContainsString($key, $this->code,
                "mwAnyActionMounted() must inspect the {$key} bucket.");
        }
        // fail-safe: on error assume a modal is open (never prune blindly)
        $this->assertMatchesRegularExpression('/catch\s*\(\s*e\s*\)\s*\{\s*return true;/', $this->code,
            'mwAnyActionMounted() must fail-safe to true so an error never triggers a blind prune.');
    }

    #[Test]
    public function registered_on_livewire_commit_via_animation_frame(): void
    {
        $this->assertMatchesRegularExpression(
            "/Livewire\.hook\(\s*'commit'[\s\S]{0,160}requestAnimationFrame\(mwHoistSettingsModal\)/",
            $this->code,
            'The hoist must run after each Livewire commit, deferred to requestAnimationFrame.'
        );
    }

    #[Test]
    public function teleport_only_runs_inside_the_iframe_surface(): void
    {
        // The hook lives inside the @if ($isIframe) + self !== top guard, so the
        // standalone /admin/<module>-module-settings page (which works without a
        // trap) is never altered.
        $this->assertStringContainsString('@if ($isIframe)', $this->layout);
        $this->assertStringContainsString('self !== top', $this->layout);
        $ifPos = strpos($this->layout, '@if ($isIframe)');
        $hookPos = strpos($this->layout, 'mwHoistSettingsModal');
        $endifPos = strrpos($this->layout, '@endif');
        $this->assertNotFalse($hookPos);
        $this->assertTrue($ifPos < $hookPos && $hookPos < $endifPos,
            'The teleport hook must sit inside the @if ($isIframe) … @endif block.');
    }

    #[Test]
    public function no_blade_component_tag_in_script_region_regression_guard(): void
    {
        // task-2026-06-05: a literal blade component tag (angle-bracket x-dash…)
        // written inside a JS comment was parsed by Blade as a real component and
        // broke compilation. Only the legitimate layout component tag may carry
        // that shape, and it must be a panels layout tag — never an x-filament
        // modal tag anywhere in the file.
        $tag = '<' . 'x-';
        $occurrences = substr_count($this->layout, $tag);
        $this->assertSame(1, $occurrences,
            'Exactly one blade component tag (the layout base) may appear; none inside the script/comments.');
        $this->assertStringContainsString('<' . 'x-filament-panels::layout.base', $this->layout,
            'The single component tag must be the panels layout base.');
    }
}
