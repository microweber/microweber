<?php
$rand = md5(time().rand(111,999));
?>
<div x-data="{customRange: false}">
    <div class="d-flex justify-content-between items-center">
        <div style="width: 90%;">
            <div class="form-range mb-2 text-green" id="range-slider-{{$rand}}}"></div>
        </div>
        <div>
           <span stype="cursor:pointer;" x-on:click="customRange =! customRange">
            {{__('Use custom range')}}
        </span>
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
                start: [20, 80],
                connect: true,
                range: {
                    'min': 0,
                    'max': 100
                }
            });
        });
    </script>
</div>
