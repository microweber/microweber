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

@include('order::admin.orders.partials.javascripts')