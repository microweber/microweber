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

    <div class="js-filtering-orders-box bg-primary-opacity-1 rounded p-4 mb-4" <?php if (!$filteringResults): ?>style="display: none"<?php endif;?>>
        <div class="row d-flex justify-content-between align-content-end">


        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="form-label"><?php _e("Order ID"); ?></label>
                <div class="input-group mb-0">
                    <input type="text" class="form-control" value="{{$id}}" name="id" aria-label="">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="form-label"><?php _e("Order Status"); ?></label>
                <div class="input-group mb-0">
                    <select class="form-control" name="status">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Date from"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i> </span>
                        </div>
                        <input type="text" class="form-control" value="{{$minDate}}" name="minDate" id="js-order-filter-date-from" aria-label="">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Date to"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i> </span>
                        </div>
                        <input type="text" class="form-control" value="{{$maxDate}}" name="maxDate" id="js-order-filter-date-to" aria-label="">
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Order amount from"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-currency-usd"></i> </span>
                        </div>
                        <input type="number" class="form-control" value="{{$minPrice}}" name="minPrice" aria-label="">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Order amount to"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-currency-usd"></i> </span>
                        </div>
                        <input type="number" class="form-control" value="{{$maxPrice}}" name="maxPrice" aria-label="">
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


            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Search"); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="text" class="form-control" value="{{$keyword}}" name="keyword" placeholder="Search orders by keyword, customer or products...">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{route('admin.order.index')}}" class="btn btn-link">
                        <?php _e("Reset filter"); ?>
                    </a>
                </div>
            </div>


    </div>
    </div>


    <?php } ?>

    <button type="submit" name="filteringResults" value="true" class="btn btn-outline-primary mr-2" style="display: none">
        <i class="mdi mdi-filter"></i> <?php _e("Submit"); ?>
    </button>

</form>
