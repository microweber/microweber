@php
    use Filament\Support\Facades\FilamentView;

    $isDisabled = $isDisabled();
    $isLive = $isLive();
    $isLiveOnBlur = $isLiveOnBlur();
    $isLiveDebounced = $isLiveDebounced();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $liveDebounce = $getLiveDebounce();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
@endphp


<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>


    <div

        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"

        x-cloak
        wire:ignore
    >
        <input

            {{
               $getExtraInputAttributeBag()->merge(
                   [
                       'id' => $getId() . 'json',
                       $applyStateBindingModifiers('wire:model') => $statePath,
                   ], escape: false)
                }}


            type="text">




        <div>
            ddd-xxxxxwooooo

            <div id="slider"></div>
            <script>
                var slider = document.getElementById('slider');

                noUiSlider.create(slider, {
                    start: [4000],
                    step: 1000,
                    range: {
                        'min': [2000],
                        'max': [10000]
                    }
                });

            </script>
        </div>





    </div>
</x-dynamic-component>
