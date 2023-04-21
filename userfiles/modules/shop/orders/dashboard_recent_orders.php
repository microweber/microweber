<?php
if (!user_can_access('module.shop.orders.index')) {
    return;
}
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


$orders = get_orders('count=1');
$shop_disabled = get_option('shop_disabled', 'website') == 'y';
?>
<?php if (!$shop_disabled): ?>
    <div class="card mb-4 dashboard-admin-cards">
        <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="dashboard-icons-wrapper wrapper-icon-orders">
                    <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/admin-dashboard-orders.png" alt="messages">
                </div>

                <div class="row">
                    <p> <?php _e("Recent Orders") ?></p>

                    <h5 class="dashboard-numbers">
                        <?php  print $orders; ?>
                    </h5>

                </div>
            </div>


            <div>
                <a href="<?php print admin_url('order'); ?>" class="btn btn-link text-dark"><?php _e('View'); ?></a>
            </div>

        </div>
    </div>
<?php endif; ?>
