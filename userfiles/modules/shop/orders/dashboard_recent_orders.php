<?php
only_has_access();


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

<?php
$orders = get_orders('limit=5');
$shop_disabled = get_option('shop_disabled', 'website') == 'y';
?>
<?php if(!$shop_disabled): ?>
<div class="card style-1 mb-3 dashboard-recent">
    <div class="card-header">
        <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("Recent Orders") ?></strong></h5>
        <div>
            <?php $new_orders_count = mw()->order_manager->get_count_of_new_orders(); ?>
            <a href="<?php print admin_url('view:shop/action:orders'); ?>" class="btn btn-primary btn-sm">
                <?php if ($new_orders_count): ?>
                <span class="badge badge-success badge-pill mr-2 absolute-left"><?php print $new_orders_count; ?></span> <?php print _e('New orders'); ?>
                <?php else: ?>
                    <?php print _e('Go to orders'); ?>
                <?php endif; ?>
            </a>
            <a href="javascript: mw_admin_add_order_popup();"  class="btn btn-outline-secondary btn-sm">Add new order</a>
        </div>
    </div>

    <div class="card-body">
        <div class="orders-holder" id="shop-orders">
            <module type="shop/orders/admin" limit="5" hide-controlls="true" intersect-new-orders="true"/>
        </div>
    </div>
</div>
<?php endif; ?>
