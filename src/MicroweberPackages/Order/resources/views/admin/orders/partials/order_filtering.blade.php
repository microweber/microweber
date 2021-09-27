<script>
    function orderRedirection(val) {
        alert(val);
    }

    $(document).ready(function () {


    });
</script>

<form method="get" class="js-form-order-filtering">

    <div class="manage-toobar d-flex justify-content-between align-items-center">

        <div id="cartsnav">

            <a href="{{route('admin.order.index')}}"
               class="btn btn-link btn-sm px-0 <?php if (!isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Completed orders"); ?></a>
            <a href="{{route('admin.order.abandoned')}}"
               class="btn btn-link btn-sm <?php if (isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Abandoned carts"); ?></a>
        </div>

    </div>

    <?php if ($orders->count() > 0 || $filteringResults) { ?>

    <div class="row" style="margin-top:25px;">

        <div class="col-md-6">
            <div class="form-group">
                <div class="input-group mb-0">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php _e("Price from"); ?></span>
                    </div>
                    <input type="number" class="form-control" value="{{$minPrice}}" name="minPrice" aria-label="">
                    <div class="input-group-append">
                        <span class="input-group-text"><?php _e("To"); ?></span>
                    </div>
                    <input type="number" class="form-control" value="{{$maxPrice}}" name="maxPrice" aria-label="">
                    <div class="input-group-append">
                        <span class="input-group-text">$</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <div class="input-group mb-0">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php _e("Date from"); ?></span>
                    </div>
                    <input type="text" class="form-control" value="{{$minDate}}" name="minDate" id="js-order-filter-date-from" aria-label="">
                    <div class="input-group-append">
                        <span class="input-group-text"><?php _e("To"); ?></span>
                    </div>
                    <input type="text" class="form-control" value="{{$maxDate}}" name="maxDate" id="js-order-filter-date-to" aria-label="">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i> </span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            mw.lib.require("air_datepicker");
            if ($.fn.datepicker) {
                $.fn.datepicker.language['en'] = {
                    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    daysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    today: 'Today',
                    clear: 'Clear',
                    dateFormat: 'yyyy-mm-dd',
                    firstDay: 0
                };

                var datePickerBaseSetup = {
                    language:'en',
                    timepicker: false,
                    onSelect: function (fd, d, picker) {
                        // Do nothing if selection was cleared
                        if (!d[0]) return;
                        if (!d[1]) return;

                        var dateFromRange = d[0].getFullYear() + "-" + numericMonth(d[0]) + "-" + numericDate(d[0]);
                        var dateToRange = d[1].getFullYear() + "-" + numericMonth(d[1]) + "-" + numericDate(d[1]);

                    }
                };

                $('#js-order-filter-date-from').datepicker(datePickerBaseSetup);
                $('#js-order-filter-date-to').datepicker(datePickerBaseSetup);
            }

        </script>

        <div class="col-md-3">
            <div class="d-inline-block mx-1">
                <button type="submit" name="filteringResults" value="true" class="btn btn-success btn-block">
                <i class="mdi mdi-filter"></i> <?php _e("Filter"); ?></button>
            </div>
            <div class="d-inline-block mx-1">
                <a href="{{route('admin.order.index')}}" class="btn btn-success btn-block">
                    <?php _e("Clear"); ?>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="js-table-sorting text-end my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
                <small><?php _e("Sort By"); ?>: &nbsp;</small>
                <div class="d-inline-block mx-1">
                    <select class="form-control" onchange="location = this.value;">

                        <option <?php if($orderBy == 'created_at' && $orderDirection == 'desc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=desc"><?php _e("Order date"); ?> <?php _e("[New > Old]"); ?></option>
                        <option <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=asc"><?php _e("Order date"); ?> <?php _e("[Old > New]"); ?></option>

                       {{-- <option <?php if($orderBy == 'order_status' && $orderDirection == 'desc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=desc"><?php _e("Status"); ?> <?php _e("[NEW]"); ?></option>
                        <option <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=asc"><?php _e("Status"); ?> <?php _e("[OLD]"); ?></option>
--}}

                        <option <?php if($orderBy == 'amount' && $orderDirection == 'desc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=desc"><?php _e("Amount"); ?> <?php _e("[High > Low]"); ?></option>
                        <option <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=asc"><?php _e("Amount"); ?> <?php _e("[Low > High]"); ?></option>


                    </select>
                </div>
            </div>
        </div>



    </div>

    <?php } ?>

</form>
