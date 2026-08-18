{{--
  Bare skin — no chrome. Used when an external wrapper (mw.dialog)
  provides close button, backdrop, drag, and resize.
--}}
@php
    $dialogOptions = [
        'closeButton' => $showCloseButton,
        'overlay' => $showBackdrop,
        'overlayClose' => $closeOnClickAway,
        'closeOnEscape' => $closeOnEscape,
        'autoHeight' => $modalSettings['autoHeight'] ?? $modalAttributes['autoHeight'] ?? true,
        'autosize' => $modalSettings['autosize'] ?? $modalAttributes['autosize'] ?? true,
        'autoScroll' => $modalSettings['autoScroll'] ?? $modalAttributes['autoScroll'] ?? true,
        'draggable' => $modalSettings['draggable'] ?? $modalAttributes['draggable'] ?? true,
        'width' => $width ?? ($modalSettings['width'] ?? ($modalAttributes['width'] ?? null)),
        'height' => $modalSettings['height'] ?? $modalAttributes['height'] ?? null,
        'title' => $modalSettings['title'] ?? $modalAttributes['title'] ?? '',
        'scrollMode' => $modalSettings['scrollMode'] ?? $modalAttributes['scrollMode'] ?? 'inside',
    ];
@endphp
<div
    class="js-modal-livewire mw-livewire-modal mw-livewire-modal-bare {{ $isActive ? 'active' : '' }} {{ $isTop ? 'is-top' : 'is-stacked' }}"
    id="js-modal-livewire-id-{{ $id }}"
    wire:key="{{ $id }}"
    role="dialog"
    aria-modal="true"
    tabindex="-1"
    data-mw-modal-instance="{{ $id }}"
    data-mw-modal-skin="bare"
    data-mw-modal-backdrop="{{ $showBackdrop ? '1' : '0' }}"
    data-mw-modal-close-on-click-away="{{ $closeOnClickAway ? '1' : '0' }}"
    data-mw-modal-close-on-escape="{{ $closeOnEscape ? '1' : '0' }}"
    data-mw-modal-show-close-button="{{ $showCloseButton ? '1' : '0' }}"
    data-mw-dialog-options="{{ e(json_encode($dialogOptions)) }}"
    @if(!$closeOnClickAway) data-mw-modal-no-backdrop-close="1" @endif
    @if(!$showBackdrop) data-mw-modal-no-backdrop="1" @endif
    style="z-index: {{ (int) $zIndex }};{{ $isActive ? '' : ' display:none;' }}"
>
    <div
        class="js-modal-livewire-content mw-livewire-modal-content mw-livewire-modal-content-bare"
        data-mw-dialog-content="{{ $id }}"
        @if(!empty($width)) style="max-width: {{ $width }}; width: {{ $width }};" @endif
    >
        @livewire($component['name'], $component['arguments'] ?? $component['attributes'] ?? [], key($id))
    </div>
</div>
