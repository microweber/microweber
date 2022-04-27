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
               class="btn btn-link btn-sm px-0 <?php if (!isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>">
                <?php _e("Completed orders"); ?> (<?php echo $orders_count; ?>)
            </a>

        <a href="{{route('admin.order.abandoned')}}"
               class="btn btn-link btn-sm <?php if (isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>">
            <?php _e("Abandoned carts"); ?> (<?php echo $abandoned_count; ?>)
        </a>

        </div>

    </div>

    <?php if ($orders->count() > 0 || $filteringResults) { ?>

    <div class="js-filtering-orders-box bg-primary-opacity-1 rounded p-4 mb-4" <?php if (!$filteringResults): ?>style="display: none"<?php endif;?>>
        <div class="row d-flex justify-content-between align-content-end">


        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="form-label"><?php _e("Order ID"); ?></label>
                <div class="input-group mb-0">
                    <input type="text" class="form-control" value="{{$id}}" name="id" placeholder="<?php _e("Order ID");?>">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="form-label"><?php _e("Order Status"); ?></label>
                <div class="input-group mb-0">
                    <select name="orderStatus" class="selectpicker" data-width="100%">

                        <option value="" @if($orderStatus == '') selected="selected" @endif>
                            <?php _e('All'); ?>
                        </option>

                        <option value="new" @if($orderStatus == 'new') selected="selected" @endif>
                            <?php _e('New'); ?>
                        </option>

                        <option value="pending" @if($orderStatus == 'pending') selected="selected" @endif>
                            <?php _e('Pending'); ?>
                        </option>

                        <option value="completed" @if($orderStatus == 'completed') selected="selected" @endif>
                            <?php _e('Completed'); ?>
                        </option>
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
                        <input type="text" class="form-control" value="{{$minDate}}" name="minDate" id="js-order-filter-date-from" placeholder="<?php _e("Set the orders from date");?>">
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
                        <input type="text" class="form-control" value="{{$maxDate}}" name="maxDate" id="js-order-filter-date-to" placeholder="<?php _e("Set the orders to date");?>">
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Order amount from"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{get_currency_symbol()}}</span>
                        </div>
                        <input type="number" class="form-control" value="{{$minPrice}}" name="minPrice" placeholder="<?php _e("Show the order with minimum amount");?>">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Order amount to"); ?></label>
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{get_currency_symbol()}} </span>
                        </div>
                        <input type="number" class="form-control" value="{{$maxPrice}}" name="maxPrice" placeholder="<?php _e("Show the order with maximum amount");?>">
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

            $(document).ready(function () {
                var searchOrdersByProduct = new mw.autoComplete({
                    element: "#js-orders-search-by-products",
                    placeholder: "<?php if ($productKeyword) { echo $productKeyword; } else { _e("Search by products..."); }?>",
                    autoComplete:true,
                    ajaxConfig: {
                        method: 'get',
                        url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&content_type=product&keyword=${val}'
                    },
                    map: {
                        value: 'id',
                        title: 'title',
                        image: 'picture'
                    }
                });
                $(searchOrdersByProduct).on("change", function (e, val) {
                    $(".js-orders-search-product").val(val[0].id).trigger('change')
                    $(".js-orders-search-product-keyword").val(val[0].title).trigger('change')
                });
            });
        </script>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Search by products"); ?></label>
                    <div class="input-group">
                        <input type="hidden" class="js-orders-search-product-keyword" name="productKeyword" value="{{$productKeyword}}" />
                        <input type="hidden" class="js-orders-search-product" name="productId" value="{{$productId}}" />
                        <div id="js-orders-search-by-products"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Payment Status"); ?></label>
                    <div class="input-group mb-0">

                        <select name="isPaid" class="selectpicker" data-width="100%">

                            <option value="" @if($isPaid == '') selected="selected" @endif>
                                <?php _e('All'); ?>
                            </option>

                            <option value="1" @if($isPaid == '1') selected="selected" @endif>
                                <?php _e('Paid'); ?>
                            </option>

                            <option value="0" @if($isPaid == '0') selected="selected" @endif>
                                <?php _e('Unpaid'); ?>
                            </option>

                        </select>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="form-label"><?php _e("Search"); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="text" class="form-control" value="{{$keyword}}" name="keyword" placeholder="<?php _e("Free search by phone, name, email etc...");?>">
                    </div>
                </div>
            </div>

            <div class="col-md-6">


            </div>

            <div class="col-md-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <button type="submit" name="filteringResults" value="true" class="js-submit-order-filtering btn btn-primary mr-2">
                        <?php _e("Submit this criteria"); ?>
                    </button>

                    <a href="{{route('admin.order.index')}}" class="btn btn-outline-primary">
                        <?php _e("Reset filter"); ?>
                    </a>
                </div>
            </div>


    </div>
    </div>


    <?php } ?>


</form>
