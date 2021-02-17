<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php print _e('Add new order'); ?></button>
            </h5>

            @include('order::admin.orders.partials.order_search')

        </div>
        <div class="card-body pt-3 pb-0">

            @include('order::admin.orders.partials.abandoned_filtering')

            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                @include('order::admin.orders.abandoned_card', ['order'=>$order])`
                <?php endforeach;?>
            <?php endif; ?>


            <div class="d-flex">
                <div class="mx-auto">
                    <?php echo $orders->links("pagination::bootstrap-4"); ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function mw_delete_shop_order(pid, iscart, e){
        if(e)e.preventDefault();
        var iscart = iscart || false;

        var r = confirm("<?php _ejs("Are you sure you want to delete this order"); ?>?");
        if (r == true) {
            $.post("<?php print api_url('delete_order') ?>", { id: pid,is_cart:iscart}, function(data) {
                window.location = window.location;
            });
        }
    }
</script>