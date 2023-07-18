<div>
    <?php
    $rand = md5(time().rand(111,999));
    ?>
    <div class="form-control-live-edit-label-wrapper " x-data="{customRange: false, currentRangeValue: 0}">
        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between">
          <div class="col-10">
              <div class="form-range mt-1 " id="range-slider-{{$rand}}}"></div>
          </div>

            <input type="text" x-model="currentRangeValue" id="js-custom-range-value-{{$rand}}" class="form-control-live-edit-input col-auto" style="background-color: #f5f5f5; max-width: 45px;">

        </div>
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
