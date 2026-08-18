<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Modal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\MwDialogSkinModal;
use PHPUnit\Framework\Attributes\Test;

class MwDialogSkinTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \Livewire\Livewire::component('mw-dialog-skin-modal', MwDialogSkinModal::class);
    }

    #[Test]
    public function mw_dialog_skin_is_used_and_options_are_rendered(): void
    {
        $html = Livewire::test(Modal::class)
            ->call('openModal', 'mw-dialog-skin-modal', ['title' => 'Hello'])
            ->html();

        $this->assertStringContainsString('data-mw-dialog-skin="1"', $html);
        $this->assertStringContainsString('data-mw-modal-skin="mw-dialog"', $html);
        $this->assertStringContainsString('data-testid="mw-livewire-mw-dialog"', $html);
        $this->assertStringContainsString('data-mw-dialog-options', $html);
        $this->assertStringContainsString('autoHeight', $html);
        $this->assertStringContainsString('draggable', $html);
        $this->assertDoesNotMatchRegularExpression(
            '/<button[^>]*class="mw-modal-close-x"/',
            $html,
        );
    }

    #[Test]
    public function modal_attributes_store_mw_dialog_flags(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'mw-dialog-skin-modal', ['title' => 'Hello']);

        $id = $host->get('activeComponent');
        $attrs = $host->get('components')[$id]['modalAttributes'];

        $this->assertSame('mw-dialog', $attrs['skin']);
        $this->assertFalse($attrs['showCloseButton']);
        $this->assertFalse($attrs['showBackdrop']);
        $this->assertArrayHasKey('autoHeight', $attrs);
        $this->assertArrayHasKey('draggable', $attrs);
        $this->assertTrue($attrs['autoHeight']);
        $this->assertTrue($attrs['draggable']);
    }

    #[Test]
    public function wrap_script_is_present_in_package_js(): void
    {
        $scripts = (string) file_get_contents(
            dirname(__DIR__) . '/resources/views/partials/scripts.blade.php'
        );

        $this->assertStringContainsString('mwMaybeWrapWithDialog', $scripts);
        $this->assertStringContainsString('mw.dialog', $scripts);
        $this->assertStringContainsString('autoHeight', $scripts);
    }
}
