{{--
  Default modal skin.
  - X close button (togglable via $showCloseButton)
  - Backdrop / click-away (togglable)
  - Escape handled in shared JS when closeOnEscape is true
--}}
<div
    class="js-modal-livewire mw-livewire-modal {{ $isActive ? 'active' : '' }} {{ $isTop ? 'is-top' : 'is-stacked' }}"
    id="js-modal-livewire-id-{{ $id }}"
    wire:key="{{ $id }}"
    role="dialog"
    aria-modal="true"
    tabindex="-1"
    data-mw-modal-instance="{{ $id }}"
    data-mw-modal-backdrop="{{ $showBackdrop ? '1' : '0' }}"
    data-mw-modal-close-on-click-away="{{ $closeOnClickAway ? '1' : '0' }}"
    data-mw-modal-close-on-escape="{{ $closeOnEscape ? '1' : '0' }}"
    data-mw-modal-show-close-button="{{ $showCloseButton ? '1' : '0' }}"
    @if(!$closeOnClickAway) data-mw-modal-no-backdrop-close="1" @endif
    @if(!$showBackdrop) data-mw-modal-no-backdrop="1" @endif
    style="z-index: {{ (int) $zIndex }};{{ $isActive ? '' : ' display:none;' }}"
>
    <div
        class="js-modal-livewire-content mw-livewire-modal-content"
        @if(!empty($width)) style="max-width: {{ $width }}; width: {{ $width }};" @endif
    >
        @if($showCloseButton)
            <button
                type="button"
                class="mw-modal-close-x"
                aria-label="Close"
                title="Close"
                data-mw-modal-close="1"
                data-mw-modal-close-for="{{ $id }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        @endif

        @livewire($component['name'], $component['arguments'] ?? $component['attributes'] ?? [], key($id))
    </div>
</div>
