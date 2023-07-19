@props(['selectedRange'=>null])

<div>
    @php
    $rand = md5(time().rand(111,999));
    @endphp
    <div class="form-control-live-edit-label-wrapper " x-data="{customRange: false, currentRangeValue: {{$selectedRange}}">
        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between">
            <label class="live-edit-form-control-settings">Range slider label </label>

            <input type="text"
                   
                   x-model="currentRangeValue"
                   id="js-custom-range-value-{{$rand}}" class="form-control-live-edit-input form-control-input-range-slider">

        </div>

      <div class="form-range mt-1 " id="range-slider-{{$rand}}}"></div>
    </div>

    <script>
        $(document).ready(function() {
            var slider = document.getElementById('range-slider-{{$rand}}}');
            noUiSlider.create(slider, {
                start: [0],
                step:1,
                connect: [true, false],
                range: {
                    'min': 0,
                    'max': 100
                }
            });

            slider.noUiSlider.on('update', function(values, handle) {
                let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
                customRangeValueField.value = parseFloat(values[handle]).toFixed();
                customRangeValueField.dispatchEvent(new Event('input'));
            });

            let customRangeValueField = document.getElementById('js-custom-range-value-{{$rand}}');
            customRangeValueField.addEventListener('change', function() {
                slider.noUiSlider.set(parseFloat(this.value).toFixed());
            });

        });
    </script>


</div>
