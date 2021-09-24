<script>
    $(document).ready(function() {


    });
</script>

<form method="get" class="js-form-order-filtering">
    <input type="hidden" name="orderDirection" value="<?php echo $orderDirection; ?>" class="js-form-order-filtering-direction" />
    <div class="manage-toobar d-flex justify-content-between align-items-center">
        <?php if (count($orders) != 0 || count($newOrders) != 0) { ?>
        <div id="cartsnav">

            <a href="{{route('admin.order.index')}}" class="btn btn-link btn-sm px-0 <?php if (!isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Completed orders"); ?></a>
            <a href="{{route('admin.order.abandoned')}}" class="btn btn-link btn-sm <?php if (isset($abandoned)): ?>font-weight-bold text-dark active<?php else: ?>text-muted<?php endif; ?>"><?php _e("Abandoned carts"); ?></a>
        </div>

        <div class="js-table-sorting text-end my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
            <small><?php _e("Sort By"); ?>: &nbsp;</small>

            <div class="d-inline-block mx-1">
                <select class="form-control" name="orderBy">
                    <option <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="created_at"><?php _e("Date"); ?></option>
                    <option <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="order_status"><?php _e("Status"); ?></option>
                    <option <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>selected="selected"<?php endif;?> value="amount"><?php _e("Amount"); ?></option>
                </select>
            </div>

            <div class="d-inline-block mx-1">
                <button type="submit" class="btn btn-success"><i class="mdi mdi-filter"></i> <?php _e("Submit filter"); ?></button>
            </div>

        </div>
        <?php } ?>
    </div>
</form>
