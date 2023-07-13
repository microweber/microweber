<?php
$rand = md5(time().rand(111,999));
?>
<div x-data="{customRange: false}">
    <div class="d-flex justify-content-between items-center">
        <div style="width: 90%;">
            <div class="form-range mt-1" id="range-slider-{{$rand}}}"></div>
        </div>
        <div>
           <button type="button" class="btn btn-white btn-sm" x-on:click="customRange =! customRange">
            {{__('Use custom range')}}
        </button>
        </div>
    </div>
    <div x-show="customRange">
        <input type="number" class="form-control" value="0">
    </div>
</div>

<div>

    <script>
        $(document).ready(function() {
            var slider = document.getElementById('range-slider-{{$rand}}}');
            noUiSlider.create(slider, {
                start: [0],
                connect: true,
                range: {
                    'min': 0,
                    'max': 100
                }
            });
        });
    </script>
</div>
