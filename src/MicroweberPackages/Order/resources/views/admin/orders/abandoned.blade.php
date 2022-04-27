<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php _e('Add new order'); ?></button>
            </h5>
        </div>
        <div class="card-body pt-3 pb-0">

            @include('order::admin.orders.partials.abandoned_filtering')

            <?php if ($orders->count() > 0): ?>
                <?php foreach ($orders as $order): ?>
                @include('order::admin.orders.abandoned_card', ['order'=>$order])`
                <?php endforeach;?>
            <?php endif; ?>

            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box">
                            <h4><?php _e("You don’t have any orders yet"); ?></h4>
                            <p><?php _e("No abandoned carts found"); ?></p>
                        </div>
                    </div>
                </div>
            </div>
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
