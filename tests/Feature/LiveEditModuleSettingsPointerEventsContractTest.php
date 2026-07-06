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
 * Fix 2 — modal `inert` neutralization (root-cause fix, package-based):
 *   Diagnosed in-browser: an open Filament modal focus-trap marks background
 *   content `inert`. Inline form/schema-action modals render INSIDE `.fi-main`,
 *   so `.fi-main` (an ANCESTOR of the modal) gets `inert` and it propagates
 *   into the modal — visible but un-clickable, every click falls through to
 *   `.fi-main-ctn`. The microweber-filament-modal-teleport package clears
 *   `inert` on any element that contains an open modal, IN PLACE (no DOM move,
 *   so Livewire wire:model/wire:submit keep working). This supersedes both the
 *   old DOM-teleport IIFE (broke Livewire when the modal left the component
 *   root) and the CSS stacking-context attempt (inert is not CSS; had no effect).
 *   The package is registered as a Filament plugin: ModalTeleportPlugin::make().
 *   See: packages/microweber-filament-modal-teleport/
 *
 * References: AdminLiveEditPage.php:445, :463, :484
 *             packages/microweber-filament-modal-teleport/
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
    // Fix 2 — modal `inert` neutralization (package-based, in place)
    // -----------------------------------------------------------------------

    /**
     * The old DOM teleport IIFE must be REMOVED from iframe-page.blade.php.
     * The fix now lives in the microweber-filament-modal-teleport package
     * (clears the ancestor `inert`, no DOM relocation).
     */
    public function test_old_dom_teleport_iife_removed(): void
    {
        $this->assertStringNotContainsString(
            'layout.appendChild(modal)',
            $this->bladeExecutable,
            'Old DOM teleport (appendChild) must be removed — replaced by the inert-fix package.'
        );
    }

    /**
     * iframe-page.blade.php must reference the package in a comment so
     * developers know where the stacking-context fix now lives.
     */
    public function test_blade_references_package(): void
    {
        $this->assertStringContainsString(
            'microweber-filament-modal-teleport',
            $this->bladeSrc,
            'iframe-page.blade.php must reference the modal-teleport package.'
        );
    }

    /**
     * The package must ship the inert-neutralization JS: the assets view must
     * remove `inert` from elements that contain an open modal. Guards against a
     * regression back to the (ineffective) CSS/opacity or (Livewire-breaking)
     * DOM-teleport approaches.
     */
    public function test_package_assets_clear_inert_on_open_modals(): void
    {
        $root = dirname(__DIR__, 2);
        $assetsPath = $root . '/packages/microweber-filament-modal-teleport/resources/views/modal-teleport-assets.blade.php';
        $this->assertFileExists($assetsPath, 'Package assets view must exist.');

        $assets = file_get_contents($assetsPath);
        $this->assertStringContainsString(
            "removeAttribute('inert')",
            $assets,
            'Package must clear the ancestor `inert` that traps inline modals.'
        );
        // Must NOT move the modal in the DOM (that severs Livewire bindings).
        $this->assertStringNotContainsString(
            'appendChild(modal)',
            $assets,
            'Fix must be in place — no DOM relocation of the modal (breaks wire:model/wire:submit).'
        );
    }

    /**
     * ModalTeleportPlugin must be registered in the admin panel provider.
     */
    public function test_plugin_registered_in_admin_panel(): void
    {
        $root = dirname(__DIR__, 2);
        $providerPath = $root . '/src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php';
        $provider = file_get_contents($providerPath);

        $this->assertStringContainsString(
            'ModalTeleportPlugin::make()',
            $provider,
            'ModalTeleportPlugin must be registered in FilamentAdminPanelProvider.'
        );
    }
}
