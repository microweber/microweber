<?php
only_admin_access();


?>

<?php $orders = get_orders(); ?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><?php _e("Recent Orders") ?></span>
        <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-plus"></span>Add New order</span>
        <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-order"></span><strong>2</strong> New orders</span>
    </div>
    <div class="dr-list">
        <div class="orders-holder" id="shop-orders">
            <?php include(modules_path() . 'shop/orders/views/orders-list.php'); ?>
        </div>
    </div>
</div>
