<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php print _e('Add new order'); ?></button>
            </h5>

            @include('order::admin.orders.partials.order_search')

        </div>
        <div class="card-body pt-3 pb-0">

            @include('order::admin.orders.partials.order_filtering')

            <?php if (count($newOrders) > 0): ?>
                 <label class="control-label mb-3 mt-3"><?php print _e('New orders'); ?></label>
            <?php foreach ($newOrders as $order): ?>
                @include('order::admin.orders.order_card')
            <?php endforeach;?>
            <?php endif; ?>

            <?php if (count($orders) > 0): ?>
            <label class="control-label mb-3 mt-3"><?php print _e('All orders'); ?></label>
            <?php foreach ($orders as $order): ?>
                @include('order::admin.orders.order_card')
            <?php endforeach;?>
             <?php endif; ?>

            <?php if (($filteringResults == true) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_results.png'); ">
                            <h4><?php _e("No orders found for this query filtering"); ?></h4>
                            <p><?php _e("Try with other filters"); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                            <h4><?php _e("You don’t have any orders yet"); ?></h4>
                            <p><?php _e("Here you can track your orders"); ?></p>
                            <br/>
                            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded"><?php _e("Add order"); ?></a>
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