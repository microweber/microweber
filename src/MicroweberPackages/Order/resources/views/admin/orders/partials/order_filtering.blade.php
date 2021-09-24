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

        <?php if (count($orders) != 0 || count($newOrders) != 0) { ?>
        <div
            class="js-table-sorting text-end my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
            <small><?php _e("Sort By"); ?>: &nbsp;</small>
            <div class="d-inline-block mx-1">
                <select class="form-control" onchange="location = this.value;">
                    <option <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=asc"><?php _e("Date"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'created_at' && $orderDirection == 'desc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=desc"><?php _e("Date"); ?> <?php _e("[DESC]"); ?></option>
                    <option <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=asc"><?php _e("Status"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'order_status' && $orderDirection == 'desc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=desc"><?php _e("Status"); ?> <?php _e("[DESC]"); ?></option>
                    <option <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=asc"><?php _e("Amount"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'amount' && $orderDirection == 'desc'): ?>selected="selected"
                            <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=desc"><?php _e("Amount"); ?> <?php _e("[DESC]"); ?></option>
                </select>
            </div>
        </div>
        <?php } ?>


    </div>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(function () {
            $("#slider-range").slider({
                range: true,
                min: {{$ordersMinPrice}},
                max: {{$ordersMaxPrice}},
                values: [{{$minPrice}}, {{$maxPrice}}],
                slide: function (event, ui) {
                    $('.js-shop-order-price-min').val(ui.values[0]);
                    $('.js-shop-order-price-max').val(ui.values[1]);
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });
            $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
        });
    </script>


    <div class="row">
        <div class="col-md-4">
            <div class="js-table-price-range">
                <p>
                    <label for="amount"><?php _e("Price range"); ?>:</label>
                    <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                </p>
                <input type="hidden" class="js-shop-order-price-min" value="{{$minPrice}}" name="minPrice"/>
                <input type="hidden" class="js-shop-order-price-max" value="{{$maxPrice}}" name="maxPrice"/>
                <div id="slider-range" class="mb-4"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="d-inline-block mx-1">
                <button type="submit" name="filteringResults" value="true" class="btn btn-success btn-block"><i
                        class="mdi mdi-filter"></i> <?php _e("Submit filter"); ?></button>
            </div>
        </div>
    </div>



</form>
