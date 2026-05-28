<?php

/**
 * Contract test for the module-settings slide-over interaction fix.
 *
 * Two independent fixes shipped together:
 *
 * Fix 1 — PHP null-coalescing guards (task-2026-05-28):
 *   AdminLiveEditPage::openModuleSettingsAction() mounts three closures
 *   (modalIcon / label / form) that each accessed $arguments['data'] directly,
 *   crashing with "Undefined array key data" (HTTP 500 on Livewire update)
 *   whenever the action was mounted without arguments. All three now read
 *   $data = $arguments['data'] ?? []; so the action mounts safely with or
 *   without a data key present.
 *
 * Fix 2 — JS DOM teleport (replaced broken CSS pointer-events approach):
 *   fi-modal is rendered by Filament inside fi-main-ctn. fi-main-ctn creates
 *   a CSS stacking context during Filament panel transitions (opacity, transform)
 *   that traps fi-modal-window-ctn (position:fixed, z:200) in the same
 *   compositor layer, causing Chrome's elementFromPoint to return fi-main-ctn
 *   for ALL modal coordinates — modal inputs are un-clickable.
 *   CSS-only approaches (pointer-events:none, isolation, overflow) all failed
 *   in testing. Proven fix: after each Livewire commit, an IIFE in
 *   iframe-page.blade.php teleports fi-modal to be a direct sibling of
 *   fi-main-ctn (child of fi-layout), removing it from the stacking context.
 *   Stale copies from prior commit cycles are cleaned up via :scope > .fi-modal
 *   before appending the freshly-rendered one.
 *
 * References: AdminLiveEditPage.php:445, :463, :484
 *             iframe-page.blade.php (fi-modal stacking-context escape IIFE)
 */

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class LiveEditModuleSettingsPointerEventsContractTest extends TestCase
{
    /** Executable (comment-stripped) source for PHP and Blade files. */
    private string $phpSrc;
    private string $phpExecutable;
    private string $bladeSrc;
    private string $bladeExecutable;

    protected function setUp(): void
    {
        parent::setUp();

        // Use dirname(__DIR__, 2) so this test works pre-Laravel-boot without base_path().
        $root = dirname(__DIR__, 2);

        // --- PHP source ---
        $phpPath = $root . '/src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php';
        $this->phpSrc = (string) file_get_contents($phpPath);

        // Strip PHP block + line comments so absence-assertions are not fooled
        // by docblock prose that mentions the literal token.
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->phpSrc);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);
        $this->phpExecutable = $stripped;

        // --- Blade source (iframe-page.blade.php — contains the teleport IIFE) ---
        $bladePath = $root . '/src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php';
        $this->bladeSrc = (string) file_get_contents($bladePath);

        // Strip JS line comments and Blade comments for absence-checks.
        $bladeStripped = preg_replace('~/\*.*?\*/~s', '', $this->bladeSrc);
        $bladeStripped = preg_replace('~//[^\n]*~', '', $bladeStripped);
        $bladeStripped = preg_replace('~\{\{--.*?--\}\}~s', '', $bladeStripped);
        $this->bladeExecutable = $bladeStripped;
    }

    // -----------------------------------------------------------------------
    // PHP Fix — null-coalescing guards
    // -----------------------------------------------------------------------

    /**
     * The modalIcon closure must read $arguments['data'] with ?? [].
     * Without the guard, mounting the action without a 'data' argument
     * crashes with "Undefined array key data" (HTTP 500).
     */
    public function test_modal_icon_closure_has_null_coalescing_guard(): void
    {
        // Locate openModuleSettingsAction in the PHP source
        $start = strpos($this->phpExecutable, 'openModuleSettingsAction');
        $this->assertNotFalse($start, 'openModuleSettingsAction must exist');

        // Slice 6 000 chars — enough to cover all three closures
        $slice = substr($this->phpExecutable, $start, 6000);

        $this->assertStringContainsString(
            "\$data = \$arguments['data'] ?? []",
            $slice,
            'modalIcon closure (and others) must guard $arguments["data"] with ?? []'
        );
    }

    /**
     * The label closure must also guard $arguments['data'].
     */
    public function test_label_closure_has_null_coalescing_guard(): void
    {
        $start = strpos($this->phpExecutable, 'openModuleSettingsAction');
        $this->assertNotFalse($start);
        $slice = substr($this->phpExecutable, $start, 6000);

        // There must be AT LEAST three occurrences of the guard in the action
        $count = substr_count($slice, "\$data = \$arguments['data'] ?? []");
        $this->assertGreaterThanOrEqual(
            3,
            $count,
            'All three closures (modalIcon, label, form) must each guard $arguments["data"] with ?? []'
        );
    }

    /**
     * The form closure must guard $data['params'] as well (prior fix).
     */
    public function test_form_closure_guards_params_key(): void
    {
        $start = strpos($this->phpExecutable, 'openModuleSettingsAction');
        $this->assertNotFalse($start);
        $slice = substr($this->phpExecutable, $start, 6000);

        $this->assertStringContainsString(
            "\$params = \$data['params'] ?? []",
            $slice,
            'form closure must guard $data["params"] with ?? []'
        );
    }

    /**
     * No bare (non-guarded) $arguments['data'] access must remain in the file.
     * The pattern $arguments['data'] without ??) would crash on missing key.
     *
     * Layer-1 belt: comment-stripped source. Layer-2 suspenders: N/A here
     * because the assertion is ABSENCE of the unsafe pattern.
     */
    public function test_no_bare_arguments_data_access_without_null_coalescing(): void
    {
        // After comment-strip, find any occurrence of $arguments['data'] NOT
        // immediately followed by " ?? " (safe) or " ==" or similar comparison.
        // The safe form is: $arguments['data'] ?? []
        // The unsafe form is: $arguments['data'] on its own on the RHS of =
        $count = preg_match_all(
            '~\$arguments\[.data.\]\s*(?!\s*\?\?)~',
            $this->phpExecutable
        );

        $this->assertSame(
            0,
            $count,
            'No bare $arguments["data"] access without ?? null-coalescing should remain in AdminLiveEditPage'
        );
    }

    // -----------------------------------------------------------------------
    // JS Fix — DOM teleport out of fi-main-ctn stacking context
    // -----------------------------------------------------------------------

    /**
     * iframe-page.blade.php must contain the teleport IIFE that moves fi-modal
     * to be a direct child of fi-layout (sibling of fi-main-ctn) after each
     * Livewire commit. This is the proven fix for modal inputs being blocked
     * by fi-main-ctn's compositor layer — confirmed by elementsFromPoint
     * returning fi-modal-window-ctn after teleport vs fi-main-ctn before.
     */
    public function test_blade_contains_teleport_iife(): void
    {
        $this->assertStringContainsString(
            '(function ()',
            $this->bladeSrc,
            'Teleport IIFE must be present in iframe-page.blade.php'
        );
        $this->assertStringContainsString(
            'doTeleport',
            $this->bladeSrc,
            'Teleport function doTeleport must be defined'
        );
        $this->assertStringContainsString(
            'layout.appendChild(modal)',
            $this->bladeSrc,
            'Teleport must move fi-modal into fi-layout via appendChild'
        );
    }

    /**
     * The teleport function must clean up stale copies accumulated from prior
     * Livewire commit cycles before appending the fresh fi-modal. Livewire's
     * morphdom re-inserts fi-modal into fi-main-ctn on each commit; without
     * this cleanup, teleported orphans accumulate in fi-layout.
     */
    public function test_teleport_removes_stale_copies_via_scope_selector(): void
    {
        $this->assertStringContainsString(
            ':scope > .fi-modal',
            $this->bladeSrc,
            'Teleport must clean up stale copies via :scope > .fi-modal on fi-layout'
        );
        $this->assertStringContainsString(
            '.remove()',
            $this->bladeSrc,
            'Stale teleported copies must be removed before appending the fresh fi-modal'
        );
    }

    /**
     * The modal selector inside doTeleport must target .fi-modal INSIDE
     * .fi-main-ctn — not the bare document-level .fi-modal, which would
     * wrongly select an already-teleported copy sitting in fi-layout.
     */
    public function test_teleport_modal_selector_targets_fi_main_ctn(): void
    {
        $this->assertStringContainsString(
            ".fi-main-ctn .fi-modal",
            $this->bladeSrc,
            'Modal selector must be .fi-main-ctn .fi-modal (not bare .fi-modal)'
        );
    }

    /**
     * The teleport hook must be registered via Livewire.hook('commit') so it
     * fires after each Livewire commit+morph cycle. The succeed callback
     * ensures doTeleport only runs on successful commits, and requestAnimationFrame
     * defers until after Alpine has also processed its reactive updates.
     */
    public function test_teleport_registered_via_livewire_commit_succeed(): void
    {
        $this->assertStringContainsString(
            "Livewire.hook('commit'",
            $this->bladeSrc,
            "Teleport must register via Livewire.hook('commit', ...)"
        );
        $this->assertStringContainsString(
            'ref.succeed(',
            $this->bladeSrc,
            'succeed callback must be used so teleport only runs on successful commits'
        );
        $this->assertStringContainsString(
            'requestAnimationFrame(doTeleport)',
            $this->bladeSrc,
            'rAF defers teleport until after Alpine reactive updates settle'
        );
    }

    /**
     * The hook registration must guard for the case where Livewire is not yet
     * initialized, by listening for livewire:initialized before registering.
     */
    public function test_teleport_has_livewire_initialized_fallback(): void
    {
        $this->assertStringContainsString(
            'livewire:initialized',
            $this->bladeSrc,
            'Must listen for livewire:initialized as fallback when Livewire boots after x-init'
        );
    }
}
