@props(['selectedRange'=>null, 'label'=> 'Range', 'labelUnit'=>'', 'min'=>0, 'max'=>100])

<div wire:ignore>
    @php
    $rand = md5(time().rand(111111111,99999999));
    $min = $min ?? 0;
    $min = intval($min);
    $max = $max ?? 100;
    $max = intval($max);
    @endphp
    <div class="form-control-live-edit-label-wrapper">

        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between">

            <label class="live-edit-label">
                {{$label}}
            </label>

            <div>
                <input type="text" {!! $attributes->merge([]) !!} id="js-custom-range-value-{{$rand}}" class="form-control-live-edit-input form-control-input-range-slider" />
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

            noUiSlider.create(slider{{$rand}}, {
                start: customRangeValueField{{$rand}}.value,
                step:1,
                connect: [true, false],
                range: {
                    'min': {{$min}},
                    'max': {{$max}}
                }
            });

            slider{{$rand}}.noUiSlider.on('change', function(values, handle) {
                let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                customRangeValueField.value = parseFloat(values[handle]).toFixed();
                customRangeValueField.dispatchEvent(new Event('input'));
            });

            slider{{$rand}}.noUiSlider.on('update', function(values, handle) {
                let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                customRangeValueField.value = parseFloat(values[handle]).toFixed();
                customRangeValueField.dispatchEvent(new Event('update'));
            });

            customRangeValueField{{$rand}}.addEventListener('change', function() {
                slider{{$rand}}.noUiSlider.set(parseFloat(this.value).toFixed());
            });
        }
    </script>
    </div>


</div>
