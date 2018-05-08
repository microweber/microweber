<?php
only_admin_access();


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
        <a href="javascript:mw_admin_add_order_popup();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" id="homepage-add-new-order-btn"><?php print _e('Add new order'); ?></a>
        <a href="<?php print admin_url('view:shop/action:orders'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong>2</strong> <?php print _e('New orders'); ?></a>
    </div>
    <div class="dr-list">
        <div class="orders-holder" id="shop-orders">
             <module type="shop/orders/admin" limit="5" hide-controlls="true" />
        </div>
    </div>
</div>
