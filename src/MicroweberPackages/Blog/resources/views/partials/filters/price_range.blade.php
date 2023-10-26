<?php
$randomId = uniqid();
?>


        <h6>{{ $filter->controlName }}</h6>

    <div class="card-body px-1">

        <div class="js-range{{$randomId}} mb-3">
            <div class="row mb-2">
                <div class="col">
                    <label>{{_e('From')}}</label>
                    <input type="text" value="{{$filter->fromPrice}}" class="form-control js-from{{$randomId}}">
                </div>
                <div class="col">
                    <label>{{_e('To')}}</label>
                    <input type="text" value="{{$filter->toPrice}}" class="form-control js-to{{$randomId}}">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <input type="text" class="js-slider{{$randomId}}" data-min="{{$filter->minPrice}}" data-max="{{$filter->maxPrice}}" data-step="1" />
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary btn-block js-range-apply{{$randomId}}">{{_e('Apply')}}</button>

    </div>



<script type="text/javascript">

    mw.lib.require('ion_range_slider');

    $(document).ready(function () {

        $('.js-range-apply{{$randomId}}').click(function() {

            var from = $('.js-from{{$randomId}}');
            var to = $('.js-to{{$randomId}}');

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'min_price', from.val());
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'max_price', to.val());


            $('#{{$moduleId}}').attr('ajax_filter', encodeDataToURL(redirectFilterUrl));
            mw.reload_module('#{{$moduleId}}');
            window.history.pushState('{{ URL::current() }}', false, '?' + encodeDataToURL(redirectFilterUrl));

            ///window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);

        });

        $(".js-range{{$randomId}}").each(function (index) {

            var from = $(this).find('.js-from{{$randomId}}');
            var to = $(this).find('.js-to{{$randomId}}');
            var slider = $(this).find('.js-slider{{$randomId}}');

            slider.ionRangeSlider({
                from: {{$filter->fromPrice}},
                to: {{$filter->toPrice}},
                grid: false,
                type: "double",
                skin: "round",
                onChange: function (data) {
                    from.val(data.from);
                    to.val(data.to);
                }
            });

            this_range = slider.data("ionRangeSlider");

            from.on('change', function () {
                this_range.update({
                    from: from.val()
                });
            });

            to.on('change', function () {
                this_range.update({
                    to: to.val()
                });
            });
        });
    });
</script>
