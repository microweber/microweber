@php
    $sliderId = "slider-" . Str::random(10);
    $sliderLabelId = $sliderId . "-label";
    $startValue = $getState() ? [$getState()] : [0];
@endphp

{{-- task-2026-05-31 AI-817 follow-up: slider label binding + noUi-handle aria-label propagation.
     Previously the label rendered with no htmlFor and the noUi-handle thumbs had empty
     aria-label/aria-labelledby — screen readers announced "slider, 0 of 600" with no purpose.
     Fix: id the label, point handles to it via aria-labelledby after slider init. --}}
<div>
    @if($getLabel())
        <label id="{{ $sliderLabelId }}" for="{{ $sliderId }}">{{ $getLabel() }}</label>
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
            labelId: @js($sliderLabelId),
            labelText: @js($getLabel() ?: ''),
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

                    // AI-817 follow-up: propagate the group label onto each noUi-handle so AT
                    // users hear the slider's purpose, not just the bare role + value range.
                    const handles = this.component.querySelectorAll('.noUi-handle');
                    handles.forEach((handle) => {
                        if (this.labelId && document.getElementById(this.labelId)) {
                            handle.setAttribute('aria-labelledby', this.labelId);
                        } else if (this.labelText) {
                            handle.setAttribute('aria-label', this.labelText);
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
