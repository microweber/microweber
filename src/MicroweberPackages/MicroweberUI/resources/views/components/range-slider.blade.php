@props(['selectedRange'=>null, 'label'=> 'Range', 'labelUnit'=>'', 'step'=>1, 'min'=>0, 'max'=>100])

<div wire:ignore>
    @php
    $rand = md5(time().rand(111111111,99999999));
    $min = $min ?? 0;
    $max = $max ?? 100;
    $step = $step ?? 1;
    @endphp
    <div class="form-control-live-edit-label-wrapper">

        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between">

            <label class="live-edit-label">
                {{$label}}
            </label>

            <div>
                <input type="number" {!! $attributes->merge([]) !!} id="js-custom-range-value-{{$rand}}" class="form-control-live-edit-input form-control-input-range-slider" />
                <label>
                    {{$labelUnit}}
                </label>
            </div>
        </div>

        <div class="form-range mt-1" id="range-slider-{{$rand}}}"></div>
    </div>

    <div>
    <script>

        $(document).ready(function() {
          loadSlider{{$rand}}();
        });

        function loadSlider{{$rand}}() {
            let slider{{$rand}} = document.getElementById('range-slider-{{$rand}}}');
            let customRangeValueField{{$rand}} = document.getElementById('js-custom-range-value-{{$rand}}');

            customRangeValueField{{$rand}}.dispatchEvent(new Event('loaded'));

            setTimeout(() => {
                noUiSlider.create(slider{{$rand}}, {
                    start: customRangeValueField{{$rand}}.value,
                    step: {{$step}},
                    connect: [true, false],
                    range: {
                        'min': {{$min}},
                        'max': {{$max}}
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
    </div>


</div>
