<?php

declare(strict_types=1);

namespace MicroweberPackages\LiveEdit\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use MicroweberPackages\Filament\Support\MwDialogOptions;
use MicroweberPackages\LivewireModal\ModalComponent;

/**
 * Livewire-modal host that embeds a Filament module-settings page
 * (or any Livewire component) so event handlers survive module
 * switches and nested create/edit actions.
 */
class FilamentMwDialogModal extends ModalComponent
{
    public string $livewireComponent = '';

    /**
     * @var array<string, mixed>
     */
    public array $livewireParams = [];

    /**
     * @var array<string, mixed>
     */
    public array $liveEditIframeData = [];

    public string $title = 'Settings';

    /**
     * @var array<string, mixed>
     */
    public array $modalSettings = [];

    public function mount(
        string $livewireComponent = '',
        array $livewireParams = [],
        array $liveEditIframeData = [],
        string $title = 'Settings',
        array $modalSettings = [],
    ): void {
        $this->livewireComponent = $livewireComponent;
        $this->livewireParams = $livewireParams;
        $this->liveEditIframeData = $liveEditIframeData;
        $this->title = $title;
        $this->modalSettings = MwDialogOptions::merge(array_merge(
            ['title' => $title],
            $modalSettings,
        ));
    }

    /**
     * Swap the inner Filament component without tearing down the
     * livewire-modal / mw.dialog shell (keeps parent event handles).
     *
     * @param  array<string, mixed>|null  $payload
     */
    #[On('mwDialogLoadModule')]
    public function loadModule(mixed $payload = null): void
    {
        if (! is_array($payload)) {
            return;
        }

        if (isset($payload['livewireComponent']) && is_string($payload['livewireComponent'])) {
            $this->livewireComponent = $payload['livewireComponent'];
        }
        if (isset($payload['livewireParams']) && is_array($payload['livewireParams'])) {
            $this->livewireParams = $payload['livewireParams'];
        }
        if (isset($payload['liveEditIframeData']) && is_array($payload['liveEditIframeData'])) {
            $this->liveEditIframeData = $payload['liveEditIframeData'];
        }
        if (isset($payload['title']) && is_string($payload['title'])) {
            $this->title = $payload['title'];
        }
        if (isset($payload['modalSettings']) && is_array($payload['modalSettings'])) {
            $this->modalSettings = MwDialogOptions::merge(array_merge(
                $this->modalSettings,
                $payload['modalSettings'],
                ['title' => $this->title],
            ));
        }
    }

    public function refreshEmbedded(): void
    {
        $this->dispatch('refresh-page');
    }

    public function embeddedKey(): string
    {
        return 'mw-fd-' . md5($this->livewireComponent . json_encode($this->livewireParams));
    }

    public static function modalSkin(): string
    {
        return 'mw-dialog';
    }

    public static function showCloseButton(): bool
    {
        return false;
    }

    public static function showBackdrop(): bool
    {
        return false;
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public function render(): View
    {
        return view('microweber-live-edit::filament-mw-dialog-modal');
    }
}
