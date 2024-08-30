<?php
must_have_access();


?>
<script>
    $(document).ready(function () {
        $('.new-close').on('click', function (e) {
            e.stopPropagation();
            var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
            $(item).removeClass('active')
            $('.mw-accordion-content', item).stop().slideUp(function () {

            });
        });
    });
</script>

<?php $orders = get_orders('limit=5'); ?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-shop"></i> <?php _e("Recent Orders") ?></span>
        <a href="javascript: mw_admin_add_order_popup();" class="btn btn-primary mw-ui-btn-outline" id="homepage-add-new-order-btn"><?php _e('Add new order'); ?></a>
        <?php $new_orders_count = mw()->order_manager->get_count_of_new_orders(); ?>
        <a href="<?php echo route('admin.order.index'); ?>" class="btn btn-primary">
            <?php if ($new_orders_count): ?>
                <strong><?php print $new_orders_count; ?></strong>
                <?php _e('New orders'); ?>
            <?php else: ?>
                <?php _e('Go to orders'); ?>
            <?php endif; ?>
        </a>
    </div>
    <div class="dr-list">
        <div class="orders-holder" id="shop-orders">
            <module type="shop/orders/admin" limit="5" hide-controls="true" intersect-new-orders="true"/>
        </div>
    </div>
</div>
