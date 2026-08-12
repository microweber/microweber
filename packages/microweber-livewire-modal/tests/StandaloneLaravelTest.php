<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Modal;
use MicroweberPackages\LivewireModal\ModalComponent;
use PHPUnit\Framework\Attributes\Test;

/**
 * Validates the package can be used in a standalone Laravel app
 * (Orchestra Testbench) without any Microweber CMS bootstrap.
 */
class StandaloneLaravelTest extends TestCase
{
    #[Test]
    public function package_boots_without_cms(): void
    {
        $this->assertFalse(
            class_exists(\MicroweberPackages\App\LaravelApplication::class)
            && app() instanceof \MicroweberPackages\App\LaravelApplication
        );

        Livewire::test(Modal::class)->assertOk();
    }

    #[Test]
    public function full_open_close_cycle_works_standalone(): void
    {
        Livewire::test(Modal::class)
            ->call('openModal', 'demo-modal', ['title' => 'Standalone'])
            ->assertSet('activeComponent', fn ($id) => is_string($id) && str_starts_with($id, 'mwlm_'))
            ->call('closeModal')
            ->assertSet('activeComponent', null)
            ->assertSet('components', [])
            ->assertSet('stack', []);
    }

    #[Test]
    public function modal_component_api_is_public(): void
    {
        $modal = new class extends ModalComponent {
            public function render(): string
            {
                return '<div></div>';
            }
        };

        $modal->forceClose();
        $modal->skipPreviousModals(2, true);

        $this->assertTrue($modal->forceClose);
        $this->assertSame(2, $modal->skipModals);
        $this->assertTrue($modal->destroySkipped);
    }
}
