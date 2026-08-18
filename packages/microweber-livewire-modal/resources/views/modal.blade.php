<div
    class="mw-livewire-modal-root"
    data-mw-livewire-modal-root="1"
    wire:id="{{ $this->getId() }}"
>
    @if(config('livewire-modal.include_css', true))
        <div wire:ignore>
            @include('livewire-modal::partials.styles')
        </div>
    @endif

    <div id="modal-holder-livewire" data-mw-modal-holder="1">
        @if(!empty($components))
            @foreach($components as $id => $component)
                @php
                    $modalAttributes = $component['modalAttributes'] ?? [];
                    $modalSettings = $component['modalSettings'] ?? [];
                    $skin = $modalAttributes['skin'] ?? ($modalSettings['skin'] ?? config('livewire-modal.skin', 'default'));
                    $zIndex = $component['zIndex'] ?? ($modalAttributes['zIndex'] ?? 1100);
                    $showBackdrop = $modalAttributes['showBackdrop']
                        ?? $modalSettings['showBackdrop']
                        ?? $modalSettings['overlay']
                        ?? true;
                    $closeOnClickAway = $modalAttributes['closeOnClickAway']
                        ?? $modalSettings['closeOnClickAway']
                        ?? $modalSettings['overlayClose']
                        ?? true;
                    $showCloseButton = $modalAttributes['showCloseButton']
                        ?? $modalSettings['showCloseButton']
                        ?? true;
                    $closeOnEscape = $modalAttributes['closeOnEscape']
                        ?? $modalSettings['closeOnEscape']
                        ?? true;
                    $width = $modalSettings['width'] ?? null;
                    $isInStack = in_array($id, $stack ?? [], true);
                    $isActive = ($activeComponent === $id) || $isInStack;
                @endphp

                @include('livewire-modal::skins.' . $skin, [
                    'id' => $id,
                    'component' => $component,
                    'zIndex' => $zIndex,
                    'showBackdrop' => (bool) $showBackdrop,
                    'closeOnClickAway' => (bool) $closeOnClickAway,
                    'showCloseButton' => (bool) $showCloseButton,
                    'closeOnEscape' => (bool) $closeOnEscape,
                    'width' => $width,
                    'isActive' => $isActive,
                    'isTop' => $activeComponent === $id,
                    'modalAttributes' => $modalAttributes,
                    'modalSettings' => $modalSettings,
                ])
            @endforeach
        @endif
    </div>

    @if(config('livewire-modal.include_js', true))
        <div wire:ignore>
            @include('livewire-modal::partials.scripts')
        </div>
    @endif
</div>
