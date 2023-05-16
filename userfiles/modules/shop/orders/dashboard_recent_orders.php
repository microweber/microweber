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


$shop_disabled = get_option('shop_disabled', 'website') == 'y';


$currency = get_currency_code();

$filter = [];
$filters['from'] = date('Y-m-d', strtotime('-30 days'));
$filters['to'] = date('Y-m-d', strtotime('today'));
$filters['currency'] = $currency;

$ordersAmount = app()->order_repository->getOrdersCountForPeriod($filters);


$amountToDisplay = 0;

if ($ordersAmount) {

    $amountToDisplay = intval($ordersAmount);

}




?>
<?php if (!$shop_disabled): ?>
    <div class="card mb-4 dashboard-admin-cards">
        <a class="dashboard-admin-cards-a" href="<?php echo route('admin.order.index'); ?>">

            <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icons-wrapper wrapper-icon-orders">
                         <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 96 960 960" width="40"><path d="M226.666 976q-27 0-46.833-19.833T160 909.334V402.666q0-27 19.833-46.833T226.666 336h100.001v-6.667q0-64 44.666-108.666Q416 176 480 176t108.667 44.667q44.666 44.666 44.666 108.666V336h100.001q27 0 46.833 19.833T800 402.666v506.668q0 27-19.833 46.833T733.334 976H226.666Zm0-66.666h506.668V402.666H633.333v86.667q0 14.167-9.617 23.75t-23.833 9.583q-14.216 0-23.716-9.583-9.5-9.583-9.5-23.75v-86.667H393.333v86.667q0 14.167-9.617 23.75t-23.833 9.583q-14.216 0-23.716-9.583-9.5-9.583-9.5-23.75v-86.667H226.666v506.668ZM393.333 336h173.334v-6.667q0-36.333-25.167-61.5T480 242.666q-36.333 0-61.5 25.167t-25.167 61.5V336ZM226.666 909.334V402.666v506.668Z"/></svg>
                    </div>

                    <div class="row">
                        <p> <?php _e("Recent Orders") ?></p>

                        <h5 class="dashboard-numbers">
                            <?php  print $amountToDisplay; ?>
                        </h5>

                    </div>
                </div>


                <div>
                    <a href="<?php echo route('admin.order.index'); ?>" class="btn btn-link  "><?php _e('View'); ?></a>
                </div>

            </div>
        </a>
    </div>
<?php endif; ?>
