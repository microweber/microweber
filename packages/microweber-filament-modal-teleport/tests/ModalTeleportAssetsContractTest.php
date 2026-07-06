<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Contract tests for modal-teleport-assets.blade.php.
 *
 * The fix clears the `inert` attribute that an open Filament modal's focus-trap
 * places on an ancestor (`.fi-main`) of an inline modal — which propagates into
 * the modal and makes it un-clickable. The fix must be IN PLACE (no DOM move,
 * no x-teleport) so Livewire wire:model/wire:submit keep working. These are
 * pure file-content assertions — no browser/Filament runtime needed.
 */
class ModalTeleportAssetsContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = file_get_contents(
            __DIR__ . '/../resources/views/modal-teleport-assets.blade.php'
        );
    }

    // ─── Asset injection ────────────────────────────────────────────────

    #[Test]
    public function blade_injects_js_via_script_tag(): void
    {
        $this->assertStringContainsString(
            '<script data-mw-modal-teleport>',
            $this->blade,
            'Must inject JS via a <script> tag with the data-mw-modal-teleport marker.'
        );
    }

    #[Test]
    public function blade_injects_style_tag_for_css_file(): void
    {
        $this->assertStringContainsString(
            '<style data-mw-modal-teleport>',
            $this->blade,
            'Must inject the (minimal) CSS via a <style> tag with the marker.'
        );
        $this->assertStringContainsString(
            'file_get_contents($cssPath)',
            $this->blade,
            'CSS must be read from the path resolved in PHP (not __DIR__ inside the compiled view).'
        );
    }

    // ─── Inert neutralization (the actual fix) ──────────────────────────

    #[Test]
    public function js_clears_inert_attribute(): void
    {
        $this->assertStringContainsString(
            "removeAttribute('inert')",
            $this->blade,
            'Must clear the ancestor `inert` that traps the inline modal.'
        );
    }

    #[Test]
    public function js_targets_only_inerted_ancestors_of_open_modals(): void
    {
        // Scope: query inerted elements and only un-inert those containing an
        // open modal, so legitimate inert (loading overlays, etc.) is untouched.
        $this->assertStringContainsString(
            '[inert]',
            $this->blade,
            'Must look up elements carrying the `inert` attribute.'
        );
        $this->assertMatchesRegularExpression(
            '/fi-modal-open|fi-modal-window-ctn/',
            $this->blade,
            'Must gate un-inerting on an actually-open modal.'
        );
    }

    #[Test]
    public function js_self_heals_via_mutation_observer_on_inert(): void
    {
        $this->assertStringContainsString(
            'MutationObserver',
            $this->blade,
            'Must observe for `inert` being (re)applied.'
        );
        $this->assertMatchesRegularExpression(
            "/attributeFilter:\s*\[[^\]]*'inert'/",
            $this->blade,
            'MutationObserver attributeFilter must include `inert`.'
        );
    }

    // ─── Must be IN PLACE — no DOM relocation, no teleport ──────────────

    #[Test]
    public function js_does_not_relocate_the_modal(): void
    {
        // Moving the modal out of its Livewire component root severs
        // wire:model/wire:submit — the action silently no-ops. Forbidden.
        $this->assertDoesNotMatchRegularExpression(
            '/\.appendChild\s*\(\s*modal/i',
            $this->blade,
            'Must NOT relocate the modal (breaks Livewire bindings).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.insertBefore\s*\(\s*modal/i',
            $this->blade,
            'Must NOT insertBefore the modal.'
        );
    }

    #[Test]
    public function js_does_not_use_x_teleport(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/x-teleport/i',
            $this->blade,
            'Must NOT use x-teleport (breaks Livewire morphdom).'
        );
    }

    #[Test]
    public function js_does_not_reintroduce_opacity_stacking_hack(): void
    {
        // The opacity/transform "stacking context" approach was verified to have
        // no effect (the cause is `inert`, not stacking). Guard against relapse.
        $this->assertDoesNotMatchRegularExpression(
            "/setProperty\(\s*['\"]opacity['\"]/",
            $this->blade,
            'Must NOT set opacity (ineffective — the cause is inert, not stacking).'
        );
    }
}
