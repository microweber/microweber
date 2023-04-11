<script>
    $(document).ready(function () {
        $('body .main > main').addClass('page-settings');
    });
</script>


<?php
$show_inner = false;
$show_inner_trigger = false;

if (isset($_GET['group']) and $_GET['group']) {
    $group = $_GET['group'];

    if ($group == 'general') {
        $show_inner = 'shop/payments/currency';
    } elseif ($group == 'coupons') {
        $show_inner = 'shop/coupons/admin';
    } elseif ($group == 'taxes') {
        $show_inner = 'shop/taxes/admin';
    } elseif ($group == 'payments') {
        $show_inner = 'shop/payments/admin';
    } elseif ($group == 'invoices') {
        $show_inner = 'shop/orders/settings/invoice_settings';
    } elseif ($group == 'shipping') {
        $show_inner = 'shop/shipping/admin';
    } elseif ($group == 'mail') {
        $show_inner = 'shop/orders/settings/setup_emails_on_order';
    } elseif ($group == 'other') {
        $show_inner = 'shop/orders/settings/other';
    } else {
        $show_inner = 'trigger';
        $show_inner_trigger = $group;
    }
}


?>

<?php event_trigger('mw.admin.shop.settings', $params); ?>

<?php if ($show_inner): ?>
    <?php if ($show_inner != 'trigger'): ?>
        <module type="<?php print $show_inner ?>"/>
    <?php else: ?>
        <?php event_trigger('mw.admin.shop.settings.' . $show_inner_trigger, $params); ?>
    <?php endif; ?>

    <?php return; ?>
<?php endif ?>

<div class="card my-3">
    <div class="card-header">
        <h5 class="card-title"><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("Shop settings"); ?></strong></h5>
        <div>

        </div>
    </div>


    <div class="row card-body">
        <div class="card-header col-12 col-sm-6 col-lg-4 settings-holder-wrapper">
            <a href="?group=shop/payments/currency" class="d-flex my-3">
                <div class="icon-holder"><i class="mdi mdi-cart-outline fs-1 me-2"></i></div>
                <div class="card-title info-holder">
                    <div class="settings-info-holder-title"><?php _e("General"); ?></div>
                    <small class="text-muted"><?php _e("Basic store settings"); ?></small>
                </div>
            </a>
        </div>

        <?php event_trigger('mw.admin.shop.settings.menu', $params); ?>

        <div class="card-header col-12 col-sm-6 col-lg-4 settings-holder-wrapper">
            <a href="?group=shop/orders/settings/invoice_settings" class="d-flex my-3">
                <div class="icon-holder"><i class="mdi mdi-cash-register fs-1 me-2"></i></div>
                <div class="card-title info-holder">
                    <div class="settings-info-holder-title"><?php _e('Invoices'); ?></div>
                    <small class="text-muted"><?php _e("Invoice lists and accounting"); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-sm-6 col-lg-4 settings-holder-wrapper">
            <a href="?group=shop/orders/settings/setup_emails_on_order" class="d-flex my-3">
                <div class="icon-holder"><i class="mdi mdi-email-edit-outline fs-1 me-2"></i></div>
                <div class="card-title info-holder">
                    <div class="settings-info-holder-title"><?php _e("Auto respond mail"); ?></div>
                    <small class="text-muted"><?php _e("Email and message settings"); ?></small>
                </div>
            </a>
        </div>

        <div class="card-header col-12 col-sm-6 col-lg-4 settings-holder-wrapper">
            <a href="?group=shop/orders/settings/other" class="d-flex my-3">
                <div class="icon-holder"><i class="mdi mdi-cog-outline fs-1 me-2"></i></div>
                <div class="card-title info-holder">
                    <div class="settings-info-holder-title"><?php _e('Other settings'); ?></div>
                    <small class="text-muted"><?php _e("Other settings"); ?></small>
                </div>
            </a>
        </div>
    </div>
</div>
