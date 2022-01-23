<?php
use MicroweberPackages\View\View;

$hide_ctrls = false;
if (isset($params['hide-controls']) and $params['hide-controls']) {
    $hide_ctrls = $params['hide-controls'];
}


?>

<div id="mw-order-table-holder">
    <?php if ($orders_type == 'completed' and  (!empty($orders)) or $has_new) : ?>
        <div class="orders-holder" id="shop-orders">
            <?php
            if ($has_new and !$current_page): ?>
                <label class="control-label my-3 mt-3"><?php _e('New orders'); ?></label>
            <?php endif; ?>
            <?php
            if ($has_new and !$current_page) {
                $view_file = __DIR__ . '/partials/orders-list.php';
                $params['new'] = true;
                $view = new View($view_file);
                $view->assign('params', $params);

                $view->assign('orders', $new_orders);
                print $view->display();
            }
            ?>

            <?php if (!$has_new and !$current_page and !$orders and !$hide_ctrls): ?>
                <div class="icon-title">
                    <i class="mdi mdi-shopping"></i> <h5><?php _e('You don\'t have any orders yet.'); ?></h5>
                </div>
            <?php endif; ?>

            <?php if ($orders and (!empty($orders))) : ?>

            <label class="control-label my-3 mt-3"><?php _e('All orders'); ?></label>
            <?php endif; ?>
            <?php
            if ($orders) {
                $view_file = __DIR__ . '/partials/orders-list.php';
                $params['new'] = false;
                $view = new View($view_file);
                $view->assign('params', $params);

                $view->assign('orders', $orders);
                print $view->display();
            }
            ?>

            <?php if (!$hide_ctrls): ?>
                <ul class="pagination justify-content-center">
                    <?php print paging('limit=10&no_wrap=true&class=mw-paging mw-paging-medium inline-block&num=' . $orders_page_count . '&current_page=' . $current_page) ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (!$hide_ctrls): ?>
            <div class="my-3">
                <span><?php _e("Export data"); ?>:</span>
                <a class="btn btn-sm btn-secondary" href="<?php echo api_url('shop/export_orders'); ?>"><?php _e("Excel"); ?></a>
            </div>
        <?php endif; ?>
    <?php elseif ($orders_type == 'carts' and isset($orders) and is_array($orders) and !empty($orders)) : ?>
        <label class="mw-ui-label"><?php _e("Abandoned Carts Section helps you analyze why some customers aren't checking out."); ?></label>
        <div class="mw-ui-box-content">
            <div id="orders_stat" style="height: 250px;"></div>
        </div>
    <?php else: ?>
        <div class="no-items-found orders">
            <?php if (isset($params['data-parent-module']) AND $params['data-parent-module'] != 'shop/orders/dashboard_recent_orders'): ?>
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
            <?php else: ?>
                <script>
                    $(document).ready(function () {
                        $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
                        $('.manage-toobar').hide();
                        $('.top-search').hide();
                    });
                </script>

                <div class="icon-title">
                    <i class="mdi mdi-shopping"></i> <h5><?php _e('You don\'t have any orders yet.'); ?></h5>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
