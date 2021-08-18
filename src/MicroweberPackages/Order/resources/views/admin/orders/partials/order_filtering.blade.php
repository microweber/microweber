<script>
    $(document).ready(function() {
        $('.js-sort-btn').click(function (e) {

            var direction = $('.js-form-order-filtering-direction').val();

            if (direction == '') {
                $('.js-form-order-filtering-direction').val('desc');
            }

            if (direction == 'desc') {
                $('.js-form-order-filtering-direction').val('asc');
            }

            if (direction == 'asc') {
                $('.js-form-order-filtering-direction').val('desc');
            }

        });
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
                <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="orderBy" value="created_at">
                    <?php _e("Date"); ?>
                    <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>
                    <i class="mdi mdi-chevron-down text-muted"></i>
                    <?php else: ?>
                    <i class="mdi mdi-chevron-up text-muted"></i>
                    <?php endif; ?>
                </button>
            </div>
            <div class="d-inline-block mx-1">
                <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="orderBy" value="order_status">
                    <?php _e("Status"); ?>
                    <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>
                    <i class="mdi mdi-chevron-down text-muted"></i>
                    <?php else: ?>
                    <i class="mdi mdi-chevron-up text-muted"></i>
                    <?php endif; ?>
                </button>
            </div>
            <div class="d-inline-block mx-1">
                <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="orderBy" value="amount">
                    <?php _e("Amount"); ?>
                    <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>
                    <i class="mdi mdi-chevron-down text-muted"></i>
                    <?php else: ?>
                    <i class="mdi mdi-chevron-up text-muted"></i>
                    <?php endif; ?>
                </button>
            </div>
        </div>
        <?php } ?>
    </div>
</form>
