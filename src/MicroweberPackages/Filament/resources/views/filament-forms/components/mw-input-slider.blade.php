@php
    $sliderId = "slider-" . Str::random(10);
    $startValue = $getState() ? [$getState()] : [0];
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()" :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    style="margin-bottom:50px"

>
    <div
        style="display: none"
    >
        {{ $getChildComponentContainer() }}
    </div>

    <script>

        function mwInputSlider({
                   element,
                   start,
                   connect,
                   range = {
                       min: 0,
                       max: 10
                   },
                   state,
                   step,
                   behaviour,
                   snap,
                   tooltips,
                   onChange = null,
                   }) {
            return {
                start,
                element,
                connect,
                range,
                component: null,
                state,
                step,
                behaviour,
                tooltips,
                onChange,
                init() {
                    this.component = document.getElementById(this.element);
                    noUiSlider.cssClasses.target += ' range-slider';

                    let slider = noUiSlider.create(this.component, {
                        start: window.Alpine.raw(start),
                        connect: window.Alpine.raw(connect),
                        range: window.Alpine.raw(range),
                        tooltips,
                        step: window.Alpine.raw(step),
                        behaviour: window.Alpine.raw(behaviour),
                        snap: window.Alpine.raw(snap),
                    });


                    this.component.noUiSlider.on('update', (values) => {
                        // console.log("Values :",values)

                        document.addEventListener('livewire:load', function () {
                            setInterval(() => Livewire.dispatch('nextSlot'), 4000);
                        })

                        for (let i = 0; i < values.length; i++) {
                            window.Livewire.dispatch(this.state[i], values[i])
                        }
                    });
                }
            }
        }
    </script>


    <div
        class="mb-[200px]"
        ax-load
        id="{{$sliderId}}"
        x-data="mwInputSlider({
                element: '{{$sliderId}}',
                start: @js($getStart()),
                state: @js($getStates()),
                connect: @js($getConnect()),
                range: @js($getRange()),
                step: @js($getStep()),
                behaviour: @js($getBehaviour()),
                snap:@js($getSnap()),
                tooltips: @js($getTooltips()),
            })">

    </div>

</x-dynamic-component>
