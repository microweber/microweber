<div
    class="mw-filament-dialog-inner"
    data-testid="filament-mw-dialog-body"
    data-mw-embedded-component="{{ $livewireComponent }}"
    wire:key="{{ $this->embeddedKey() }}"
>
    @if($livewireComponent !== '')
        @livewire($livewireComponent, [
            'params' => $livewireParams,
            'liveEditIframeData' => $liveEditIframeData,
            'embeddedInDialog' => true,
        ], key($this->embeddedKey()))
    @endif
</div>
