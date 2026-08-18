<?php

declare(strict_types=1);

namespace MicroweberPackages\LiveEdit\tests;

use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Http\Livewire\FilamentMwDialogModal;
use MicroweberPackages\LivewireModal\Modal;
use Modules\Video\Filament\VideoModuleSettings;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FilamentMwDialogModalTest extends TestCase
{
    #[Test]
    public function host_uses_mw_dialog_skin_and_embeds_component(): void
    {
        $this->assertSame('mw-dialog', FilamentMwDialogModal::modalSkin());
        $this->assertFalse(FilamentMwDialogModal::showCloseButton());
        $this->assertFalse(FilamentMwDialogModal::showBackdrop());

        $html = Livewire::test(FilamentMwDialogModal::class, [
            'livewireComponent' => VideoModuleSettings::class,
            'livewireParams' => ['id' => 'unit-video'],
            'title' => 'Video',
        ])->html();

        $this->assertStringContainsString('data-testid="filament-mw-dialog-body"', $html);
        $this->assertStringContainsString('data-mw-embedded-component', $html);
    }

    #[Test]
    public function load_module_swaps_the_embedded_component(): void
    {
        $component = Livewire::test(FilamentMwDialogModal::class, [
            'livewireComponent' => VideoModuleSettings::class,
            'livewireParams' => ['id' => 'a'],
            'title' => 'Video',
        ]);

        $component->call('loadModule', [
            'livewireComponent' => VideoModuleSettings::class,
            'livewireParams' => ['id' => 'b'],
            'title' => 'Video settings',
        ]);

        $this->assertSame(VideoModuleSettings::class, $component->get('livewireComponent'));
        $this->assertSame('Video settings', $component->get('title'));
        $this->assertSame(['id' => 'b'], $component->get('livewireParams'));
    }

    #[Test]
    public function livewire_modal_stack_can_open_filament_mw_dialog(): void
    {
        $host = Livewire::test(Modal::class)
            ->call('openModal', 'filament-mw-dialog', [
                'livewireComponent' => VideoModuleSettings::class,
                'livewireParams' => ['id' => 'stack-video'],
                'title' => 'Video',
            ]);

        $this->assertNotNull($host->get('activeComponent'));
        $id = $host->get('activeComponent');
        $this->assertSame('filament-mw-dialog', $host->get('components')[$id]['name']);
        $this->assertSame('mw-dialog', $host->get('components')[$id]['modalAttributes']['skin']);
    }
}
