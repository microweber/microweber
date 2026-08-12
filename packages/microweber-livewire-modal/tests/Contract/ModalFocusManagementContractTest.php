<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Pins a11y / focus-trap behaviours in the package scripts partial.
 */
class ModalFocusManagementContractTest extends TestCase
{
    private const SCRIPTS = __DIR__ . '/../../resources/views/partials/scripts.blade.php';
    private const DEFAULT_SKIN = __DIR__ . '/../../resources/views/skins/default.blade.php';

    #[Test]
    public function default_skin_carries_role_dialog_aria_modal_and_tabindex(): void
    {
        $blade = $this->readFile(self::DEFAULT_SKIN);

        $this->assertMatchesRegularExpression(
            '/class="js-modal-livewire[^"]*"[^>]*\srole="dialog"/s',
            $blade,
        );
        $this->assertMatchesRegularExpression(
            '/aria-modal="true"/',
            $blade,
        );
        $this->assertMatchesRegularExpression(
            '/tabindex="-1"/',
            $blade,
        );
    }

    #[Test]
    public function script_declares_tabbable_selector(): void
    {
        $blade = $this->readFile(self::SCRIPTS);

        $this->assertStringContainsString("'a[href]'", $blade);
        $this->assertStringContainsString("'button:not([disabled])'", $blade);
        $this->assertStringContainsString("'[tabindex]:not([tabindex=\"-1\"])'", $blade);
    }

    #[Test]
    public function keydown_handler_traps_tab_and_closes_on_escape(): void
    {
        $blade = $this->readFile(self::SCRIPTS);

        $this->assertMatchesRegularExpression(
            '/function\s+mwModalKeydownHandler\s*\(\s*event\s*\)\s*\{[\s\S]*?event\.key\s*===\s*\'Escape\'/s',
            $blade,
        );
        $this->assertMatchesRegularExpression(
            "/window\\.Livewire\\.dispatch\\('closeModal'/",
            $blade,
        );
    }

    #[Test]
    public function open_event_wires_trap_and_close_releases_it(): void
    {
        $blade = $this->readFile(self::SCRIPTS);

        $this->assertMatchesRegularExpression(
            "/Livewire\\.on\\(\\s*'activeModalComponentChanged'[\\s\\S]*?mwTrapFocusForModal\\(\\)/s",
            $blade,
        );
        $this->assertMatchesRegularExpression(
            "/Livewire\\.on\\(\\s*'closeModal'[\\s\\S]*?mwReleaseFocusForModal\\(\\)/s",
            $blade,
        );
    }

    #[Test]
    public function focus_stack_supports_nested_modal_restore(): void
    {
        $blade = $this->readFile(self::SCRIPTS);

        $this->assertMatchesRegularExpression('/let\s+mwModalFocusStack\s*=\s*\[\]/', $blade);
        $this->assertMatchesRegularExpression('/mwModalFocusStack\.push\(/', $blade);
        $this->assertMatchesRegularExpression('/mwModalFocusStack\.pop\(\)/', $blade);
    }

    #[Test]
    public function unbind_keydown_respects_nested_stack(): void
    {
        $blade = $this->readFile(self::SCRIPTS);

        $this->assertMatchesRegularExpression(
            '/function\s+mwUnbindModalKeydown\s*\(\s*\)\s*\{[\s\S]*?mwModalFocusStack\.length\s*>\s*0\s*\)\s*return/s',
            $blade,
        );
    }

    #[Test]
    public function default_skin_includes_close_button(): void
    {
        $blade = $this->readFile(self::DEFAULT_SKIN);
        $this->assertStringContainsString('mw-modal-close-x', $blade);
        $this->assertStringContainsString('data-mw-modal-close="1"', $blade);
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");
        $contents = file_get_contents($real);
        $this->assertNotFalse($contents);
        $this->assertNotEmpty($contents);

        return $contents;
    }
}
