<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins the browser-side guards that keep the mw-dialog module-settings modal
 * usable. Both live in <script> blocks inside Blade partials (no JS build), so a
 * PHPUnit token check is the only regression net.
 *
 * 1. Autosize + scroll — core mw.dialog pins a fixed 320px holder height for DOM
 *    content (autoHeight only autosizes iframes), which clipped the settings form
 *    with no scroll. The skin clears the fixed height, forces centerMode 'center'
 *    (so it recenters when shorter, not only when taller) and re-centers on holder
 *    resize.
 * 2. Dedupe — a repeated open request (double-click / duplicate event) must not
 *    stack a second identical dialog; the key is released on close.
 * 3. The Filament decorate scan must skip windows already hosted by the skin, so
 *    the two mechanisms can never double-decorate one dialog.
 */
class FilamentMwDialogAutosizeDedupeContractTest extends TestCase
{
    private const SKIN_SCRIPTS = 'packages/microweber-livewire-modal/resources/views/partials/scripts.blade.php';

    private const LIVEEDIT_SCRIPTS = 'src/MicroweberPackages/LiveEdit/resources/views/partials/filament-mw-dialog-scripts.blade.php';

    private function read(string $relative): string
    {
        $path = base_path($relative);
        $this->assertFileExists($path, $relative . ' is missing');

        return (string) file_get_contents($path);
    }

    #[Test]
    public function skin_clears_the_fixed_holder_height_and_forces_center_mode(): void
    {
        $src = $this->read(self::SKIN_SCRIPTS);

        // Autosize: the core's fixed 320px inline height is cleared so the holder
        // shrink-wraps its content.
        $this->assertStringContainsString("holder.style.height = 'auto'", $src);

        // Recenter symmetrically (default 'intuitive' only moves down when taller).
        $this->assertStringContainsString("centerMode: 'center'", $src);

        // Keep it centered/contained when the inner form changes height.
        $this->assertStringContainsString('ResizeObserver', $src);
        $this->assertStringContainsString('_mwDialogResizeObserver', $src);
    }

    #[Test]
    public function skin_disconnects_the_resize_observer_on_unwrap(): void
    {
        $src = $this->read(self::SKIN_SCRIPTS);

        // Cleanup must live inside mwUnwrapDialog so a closed dialog doesn't leak
        // an observer holding the detached node.
        $unwrap = strstr($src, 'function mwUnwrapDialog');
        $this->assertIsString($unwrap, 'mwUnwrapDialog missing');
        $this->assertStringContainsString('_mwDialogResizeObserver.disconnect()', (string) $unwrap);
    }

    #[Test]
    public function live_edit_open_path_dedupes_repeated_module_settings_opens(): void
    {
        $src = $this->read(self::LIVEEDIT_SCRIPTS);

        $this->assertStringContainsString('__mwModuleDialogOpenKeys', $src);
        $this->assertStringContainsString('function moduleDialogKey', $src);

        // The guard must short-circuit before dispatching a second openModal.
        $open = strstr($src, 'function openFromModuleSettings');
        $this->assertIsString($open, 'openFromModuleSettings missing');
        $this->assertStringContainsString('__mwModuleDialogOpenKeys[dialogKey]', (string) $open);

        // Keys are released on close so the same module can be re-opened.
        $this->assertStringContainsString("Livewire.on('closeModal'", $src);
        $this->assertStringContainsString("Livewire.on('modalStackCleared'", $src);
    }

    #[Test]
    public function decorate_scan_skips_windows_already_hosted_by_the_skin(): void
    {
        $src = $this->read(self::LIVEEDIT_SCRIPTS);

        $decorate = strstr($src, 'function decorateFilamentWindow');
        $this->assertIsString($decorate, 'decorateFilamentWindow missing');
        $this->assertStringContainsString('mw-livewire-modal-mw-dialog', (string) $decorate);
    }

    #[Test]
    public function decorate_path_has_a_native_drag_fallback_without_jquery_ui(): void
    {
        $src = $this->read(self::LIVEEDIT_SCRIPTS);

        // The drag must not hard-depend on jQuery UI: when it is absent the
        // window is still made movable via a native Pointer Events fallback.
        $this->assertStringContainsString('function makeWindowDraggableNative', $src);
        $this->assertStringContainsString('data-mw-native-drag', $src);
        $this->assertStringContainsString("addEventListener('pointerdown'", $src);

        // The fallback must actually be reached on the no-jQuery-UI branch.
        $decorate = strstr($src, 'function decorateFilamentWindow');
        $this->assertIsString($decorate, 'decorateFilamentWindow missing');
        $this->assertStringContainsString('makeWindowDraggableNative(windowEl)', (string) $decorate);
    }
}
