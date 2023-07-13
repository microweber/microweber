<div x-data="{customRange: false}">
    <div class="d-flex justify-content-between" >
        <div>
            <input type="range" class="form-range mb-2" value="40" min="0" max="100" step="10">
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

<?php
$rand = md5(time().rand(111,999));
?>
<div>
    <div id="slider-{{$rand}}}"></div>
    <script>
        $(document).ready(function() {
            var slider = document.getElementById('slider-{{$rand}}}');
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
