<script>
    function orderRedirection(val) {
        alert(val);
    }

    $(document).ready(function() {


    });
</script>

<form method="get" class="js-form-order-filtering">

    <div class="manage-toobar d-flex justify-content-between align-items-center">
        <?php if (count($orders) != 0 || count($newOrders) != 0) { ?>
        <div id="cartsnav">

            <a href="{{route('admin.order.index')}}" class="btn btn-link btn-sm px-0 <?php if (!isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Completed orders"); ?></a>
            <a href="{{route('admin.order.abandoned')}}" class="btn btn-link btn-sm <?php if (isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Abandoned carts"); ?></a>
        </div>

        <div class="js-table-sorting text-end my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
            <small><?php _e("Sort By"); ?>: &nbsp;</small>

            <div class="d-inline-block mx-1">
                <select class="form-control" onchange="location = this.value;"> 
                    <option <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=asc"><?php _e("Date"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'created_at' && $orderDirection == 'desc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=desc"><?php _e("Date"); ?> <?php _e("[DESC]"); ?></option>
                    <option <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=asc"><?php _e("Status"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'order_status' && $orderDirection == 'desc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=desc"><?php _e("Status"); ?> <?php _e("[DESC]"); ?></option>
                    <option <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=asc"><?php _e("Amount"); ?> <?php _e("[ASC]"); ?></option>
                    <option <?php if($orderBy == 'amount' && $orderDirection == 'desc'): ?>selected="selected"<?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=desc"><?php _e("Amount"); ?> <?php _e("[DESC]"); ?></option>
                </select>
            </div>

            <div class="d-inline-block mx-1">
                <button type="submit" class="btn btn-success"><i class="mdi mdi-filter"></i> <?php _e("Submit filter"); ?></button>
            </div>

        </div>
        <?php } ?>
    </div>
</form>
