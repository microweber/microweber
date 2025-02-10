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

    $rand = $getId();
    $rangeMin = $rangeMin ?? 0;
    $rangeMax = $rangeMax ?? 100;
    $rangeStep = $rangeStep ?? 1;
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>



awfwa
    <script>

        document.addEventListener('alpine:init', () => {
            loadSlider{{$rand}}();
        });

        alert(44);

        function loadSlider{{$rand}}() {

            alert(33);

            let slider{{$rand}} = document.getElementById('range-slider-{{$rand}}');
            let customRangeValueField{{$rand}} = document.getElementById('js-custom-range-value-{{$rand}}');

            customRangeValueField{{$rand}}.dispatchEvent(new Event('loaded'));

            setTimeout(() => {
                noUiSlider.create(slider{{$rand}}, {
                    start: customRangeValueField{{$rand}}.value,
                    step: {{$rangeStep}},
                    connect: [true, false],
                    range: {
                        'min': {{$rangeMin}},
                        'max': {{$rangeMax}}
                    }
                });

                slider{{$rand}}.noUiSlider.on('change', function(values, handle) {
                    let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                    customRangeValueField.value = parseFloat(values[handle]).toString();
                    customRangeValueField.dispatchEvent(new Event('input'));
                });

                slider{{$rand}}.noUiSlider.on('slide', function(values, handle) {
                    let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                    customRangeValueField.value = parseFloat(values[handle]).toString();
                    customRangeValueField.dispatchEvent(new Event('slide'));
                });

                customRangeValueField{{$rand}}.addEventListener('input', function() {
                    slider{{$rand}}.noUiSlider.set(parseFloat(this.value).toString());

                    let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                    customRangeValueField.dispatchEvent(new Event('slide'));
                });
            }, 100);
        }
    </script>




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

            id="js-custom-range-value-{{$rand}}"

            {{
               $getExtraInputAttributeBag()->merge(
                   [
                       $applyStateBindingModifiers('wire:model') => $statePath,
                   ], escape: false)
                }}


            type="text">


        <div>

            <div class="form-range mt-1" id="range-slider-{{$rand}}"></div>


        </div>





    </div>
</x-dynamic-component>
