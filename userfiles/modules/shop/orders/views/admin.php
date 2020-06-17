<?php
use Microweber\View;

$hide_ctrls = false;
if (isset($params['hide-controlls']) and $params['hide-controlls']) {
    $hide_ctrls = $params['hide-controlls'];
}
?>

<div id="mw-order-table-holder">
    <?php if ($orders_type == 'completed' and isset($orders) and is_array($orders)) : ?>
        <div class="orders-holder" id="shop-orders">
            <?php
            if ($has_new and !$current_page) {
                $view_file = __DIR__ . '/partials/orders-list.php';

                $view = new View($view_file);
                $view->assign('params', $params);

                $view->assign('orders', $new_orders);
                print $view->display();
            }
            ?>

            <?php if (!$has_new and !$current_page and !$hide_ctrls): ?>
                <div class="icon-title">
                    <i class="mdi mdi-shopping"></i> <h5><?php _e('You don\'t have any orders yet.'); ?></h5>
                </div>
            <?php endif; ?>

            <?php
            if ($orders) {
                $view_file = __DIR__ . '/partials/orders-list.php';

                $view = new View($view_file);
                $view->assign('params', $params);

                $view->assign('orders', $orders);
                print $view->display();
            }
            ?>

            <?php if (!$hide_ctrls): ?>
                <div class="m-a m-t-30 center">
                    <?php print paging('limit=10&no_wrap=true&class=mw-paging mw-paging-medium inline-block&num=' . $orders_page_count . '&current_page=' . $current_page) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mw-clear"></div><br/>

        <?php if (!$hide_ctrls): ?>
            <div class="export-label">
                <span><?php _e("Export data"); ?>:</span>
                <a class="mw-ui-btn mw-ui-btn-small" href="<?php echo api_url('shop/export_orders'); ?>"><?php _e("Excel"); ?></a>
            </div>
        <?php endif; ?>
    <?php elseif ($orders_type == 'carts' and isset($orders) and is_array($orders)) : ?>
        <label class="mw-ui-label"><?php _e("Abandoned Carts Section helps you analyze why some customers aren't checking out."); ?></label>
        <div class="mw-ui-box-content">
            <div id="orders_stat" style="height: 250px;"></div>
        </div>
    <?php else: ?>
        <?php if (!$hide_ctrls) { ?>
            <div class="icon-title">
                <i class="mdi mdi-shopping"></i> <h5><?php _e('You don\'t have any orders yet.'); ?></h5>
            </div>
        <?php } ?>
    <?php endif; ?>
</div>