@php
    $sliderId = "slider-" . Str::random(10);
    $startValue = $getState() ? [$getState()] : [0];
@endphp

<div>
    @if($getLabel())
        <label>{{ $getLabel() }}</label>
    @endif

    <div
        style="display: none"
    >
        {{ $getChildComponentContainer() }}
    </div>

    <div
        class="mb-1"
        ax-load
        id="{{$sliderId}}"
        x-data="{
            start: @js($getStart()),
            element: '{{$sliderId}}',
            connect: @js($getConnect()),
            range: @js($getRange()),
            component: null,
            state: @js($getStates()),
            step: @js($getStep()),
            behaviour: @js($getBehaviour()),
            tooltips: @js($getTooltips()),
            snap: @js($getSnap()),

            onChange: null,
            wire: null,
            init() {
                this.component = document.getElementById(this.element);
                this.wire = this.$wire || window.Livewire?.first();

                if (typeof noUiSlider !== 'undefined') {
                    noUiSlider.cssClasses.target += ' range-slider';

                    let slider = noUiSlider.create(this.component, {
                        start: this.start,
                        connect: this.connect,
                        range: this.range,
                        tooltips: this.tooltips,
                        step: this.step,
                        behaviour: this.behaviour,
                        snap: this.snap,
                        format: {
                            from: Number,
                            to: function(value) {
                                return (parseInt(value));
                            }
                        }
                    });

                    this.component.noUiSlider.on('change', (values) => {
                        // console.log('Values :', values)

                        for (let i = 0; i < values.length; i++) {
                            const statePath = this.state[i];
                            const value = parseFloat(values[i]);

                            // Try multiple approaches to update Livewire state
                            try {
                                // Method 1: Use stored wire reference
                                if (this.wire) {
                                    this.wire.set(statePath, value);
                                }
                                // Method 2: Find component by closest wire:id
                                else {
                                    const wireElement = this.component.closest('[wire\\:id]');
                                    if (wireElement) {
                                        const wireId = wireElement.getAttribute('wire:id');
                                        const component = window.Livewire.find(wireId);
                                        if (component) {
                                            component.set(statePath, value);
                                        }
                                    }
                                }
                            } catch (error) {
                                console.warn('Could not update Livewire state directly:', error);
                            }

                            // Always dispatch event as well for the hidden inputs
                            window.Livewire.dispatch('updated-' + statePath.replace(/\./g, '-'), {value: value});
                        }
                    });
                } else {
                    console.error('noUiSlider is not loaded');
                }
            }
        }">

    </div>
</div>
