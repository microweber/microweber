<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Modal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\OptionsModal;
use PHPUnit\Framework\Attributes\Test;

class ModalOptionsTest extends TestCase
{
    #[Test]
    public function options_modal_can_disable_close_behaviours(): void
    {
        $this->assertFalse(OptionsModal::closeModalOnClickAway());
        $this->assertFalse(OptionsModal::closeModalOnEscape());
        $this->assertFalse(OptionsModal::showCloseButton());
        $this->assertFalse(OptionsModal::showBackdrop());
    }

    #[Test]
    public function open_modal_stores_disabled_options_on_instance(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'options-modal', ['title' => 'Opts']);

        $id = $host->get('activeComponent');
        $attrs = $host->get('components')[$id]['modalAttributes'];

        $this->assertFalse($attrs['closeOnClickAway']);
        $this->assertFalse($attrs['closeOnEscape']);
        $this->assertFalse($attrs['showCloseButton']);
        $this->assertFalse($attrs['showBackdrop']);
    }

    #[Test]
    public function modal_settings_legacy_keys_are_mapped(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'options-modal', ['title' => 'Opts']);

        $id = $host->get('activeComponent');
        $settings = $host->get('components')[$id]['modalSettings'];

        $this->assertArrayHasKey('overlay', $settings);
        $this->assertArrayHasKey('overlayClose', $settings);
        $this->assertArrayHasKey('width', $settings);
        $this->assertSame('500px', $settings['width']);
    }

    #[Test]
    public function modal_attributes_override_defaults_per_open(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'X'], [
                'showCloseButton' => false,
                'showBackdrop' => false,
                'closeOnEscape' => false,
                'closeOnClickAway' => false,
                'skin' => 'default',
            ]);

        $id = $host->get('activeComponent');
        $attrs = $host->get('components')[$id]['modalAttributes'];

        $this->assertFalse($attrs['showCloseButton']);
        $this->assertFalse($attrs['showBackdrop']);
        $this->assertFalse($attrs['closeOnEscape']);
        $this->assertFalse($attrs['closeOnClickAway']);
        $this->assertSame('default', $attrs['skin']);
    }

    #[Test]
    public function disabled_close_button_not_rendered_in_html(): void
    {
        $html = Livewire::test(Modal::class)
            ->call('openModal', 'options-modal', ['title' => 'NoX'])
            ->html();

        // Scripts/CSS may mention the close affordance; assert the button element is absent.
        $this->assertDoesNotMatchRegularExpression(
            '/<button[^>]*class="mw-modal-close-x"/',
            $html,
            'Close X button must not be rendered when showCloseButton is false.',
        );
        $this->assertStringContainsString('data-mw-modal-show-close-button="0"', $html);
    }
}
