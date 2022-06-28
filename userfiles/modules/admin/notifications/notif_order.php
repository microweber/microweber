<?php
$order = false;
$order_products = false;
$order_first_product = false;

if (isset($item['rel_id']) AND !isset($is_order)) {
    $item_id = $item['rel_id'];
} elseif (isset($item['id']) AND isset($is_order)) {
    $item_id = $item['id'];
}

$order_products_qty = 0;
$order = get_order_by_id($item_id);
$order_products = mw()->shop_manager->order_items($item_id);
if ($order_products) {
    foreach ($order_products as $order_product) {
        $order_products_qty = $order_products_qty + $order_product['qty'];
    }
    $order_first_product = $order_products[0];
}

$created_by = false;
if (isset($item['created_by'])) {
    $created_by = get_user_by_id($item['created_by']);
    if (isset($created_by['username'])) {
        $created_by_username = $created_by['username'];
    } else {
        $created_by_username = false;
    }
}

$is_new = false;
if(($order and isset($order['order_status']) and $order['order_status'] == 'new') or (isset($params['new']) AND $params['new'] == true) OR isset($item['is_read']) AND $item['is_read'] == 0){
    $is_new = true;

}
?>

<script>
    $( document ).ready(function() {
        $('.collapse', '.js-order-entry-<?php print $item_id ?>').on('shown.bs.collapse', function () {
            $('.js-order-entry-<?php print $item_id ?>').prop('disabled',true).removeAttr('data-bs-toggle');
        });
    });

</script>



<div class="js-order-entry-<?php print $item_id ?> card mb-3 not-collapsed-border collapsed <?php if (!isset($is_order)): ?>card-bubble<?php endif; ?> card-order-holder <?php if ($is_new): ?>active card-success<?php else: ?>bg-silver<?php endif; ?>" data-bs-toggle="collapse" data-bs-target="#notif-order-item-<?php print $item_id; ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body py-2">
        <div class="row" data-bs-toggle="collapse" data-bs-target="#notif-order-item-<?php print $item_id; ?>">
            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col item-image">
                        <?php if ($order_products_qty > 1): ?>
                            <button type="button" class="btn btn-primary btn-rounded position-absolute btn-sm" style="width: 30px; right: 0; z-index: 9;">
                                <?php echo $order_products_qty; ?>
                            </button>
                        <?php endif; ?>
                        <div class="img-circle-holder img-absolute">
                            <?php if ($order_first_product AND isset($order_first_product['item_image'])): ?>
                                <img src="<?php echo thumbnail($order_first_product['item_image'], 160, 160); ?>"/>
                            <?php else: ?>
                                <img src="<?php echo thumbnail(''); ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col item-id"><span class="text-primary">#<?php echo $order['id']; ?></span></div>

                    <div class="col item-title" style="min-width: 210px;">
                        <?php if ($order_first_product AND isset($order_first_product['title'])): ?>
                            <span class="text-primary text-break-line-2"><?php echo $order_first_product['title']; ?></span>
                        <?php endif; ?>

                        <?php if (isset($created_by_username)): ?>
                            <small class="text-muted"><?php _e("Ordered by"); ?>: <?php echo $created_by_username; ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="row align-items-center h-100">
                    <div class="col-6 col-sm-4 col-md item-amount">
                        <?php if (isset($order['amount'])): ?><?php echo currency_format($order['amount'], $order['currency']) ; ?><br/><?php endif; ?>
                        <?php if (isset($order['is_paid']) and intval($order['is_paid']) == 1): ?>

                           <?php if (isset($item['payment_status']) && $item['payment_status']): ?>
                            <small class="text-success"><?php _e($item['payment_status']); ?></small>
                            <?php else: ?>
                             <small class="text-success"><?php _e('Paid'); ?></small>
                            <?php endif; ?>


                        <?php else: ?>

                            <?php if (isset($item['payment_status']) && $item['payment_status']): ?>
                                <small class="text-muted"><?php _e($item['payment_status']); ?></small>
                            <?php else: ?>
                                <small class="text-muted"><?php _e('Unpaid'); ?></small>
                            <?php endif; ?>




                        <?php endif; ?>
                    </div>

                    <div class="col-6 col-sm-4 col-md item-date" data-bs-toggle="tooltip" title="<?php print mw('format')->ago($item['created_at']); ?>">
                        <?php print date('M d, Y', strtotime($item['created_at'])); ?><br/>
                        <small class="text-muted"><?php print date('H:s', strtotime($item['created_at'])); ?>  </small>
                    </div>

                    <div class="col-12 col-sm-4 col-md item-status">
                        <?php if (isset($item['is_read']) && $item['is_read'] == '0'): ?>
                            <span class="text-success"><?php _e("New"); ?></span><br/>
                        <?php endif; ?>
                        <small class="text-muted">&nbsp;</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center text-sm-left js-change-button-styles">
                <a href="<?php echo route('admin.order.show', $order['id']); ?>" onclick="event.stopPropagation()" class="btn btn-outline-primary btn-sm btn-rounded"><?php _e("View order"); ?></a>
            </div>
        </div>

        <div class="collapse" id="notif-order-item-<?php print $item_id; ?>">


            <hr class="thin"/>

            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h6><strong><?php _e("Customer Information"); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e("Client name"); ?>:</small>
                        <p>
                            <?php if (isset($order['first_name']) OR isset($order['last_name'])): ?>
                                <?php if (isset($order['first_name'])): ?><?php echo $order['first_name'] . ' '; ?><?php endif; ?>
                                <?php if (isset($order['last_name'])): ?><?php echo $order['last_name']; ?><?php endif; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e("E-mail"); ?>:</small>
                        <p>
                            <?php if (isset($order['email'])): ?>
                                <?php echo $order['email']; ?>
                            <?php else: ?>
                                    N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e("Phone"); ?>:</small>
                        <p>
                            <?php if (isset($order['phone'])): ?>
                                <?php echo $order['phone']; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong><?php _e("Payment Information"); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e("Amount"); ?>:</small>
                        <p>
                            <?php if (isset($order['amount'])): ?>
                                <?php echo currency_format($order['amount'], $order['currency']) ; ?>
                            <?php else: ?>
                                    N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e("Payment Amount"); ?>:</small>
                        <p>
                            <?php if (isset($order['payment_amount'])): ?>
                                <?php echo currency_format($order['payment_amount'], $order['payment_currency']) ; ?>
                            <?php else: ?>
                                    N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e("Payment method"); ?></small>
                        <p>
                            <?php   $paymentGatewayModuleInfo = module_info($order['payment_gw']); ?>
                            <?php if($paymentGatewayModuleInfo):  ?>
                                <?php if (isset($paymentGatewayModuleInfo['settings']['icon_class'])): ?>
                                    <i class="<?php echo $paymentGatewayModuleInfo['settings']['icon_class'];?>" style="font-size:23px"></i>
                                <?php else: ?>
                                    <?php if (isset($paymentGatewayModuleInfo['icon'])): ?>
                                        <img src="<?php echo $paymentGatewayModuleInfo['icon'];?>" style="width:23px" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php echo $paymentGatewayModuleInfo['name'];?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong><?php _e("Shipping Information"); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e("Shipping method"); ?>:</small>
                        <p>
                            <?php if (isset($order['shipping_service'])): ?>
                                <?php if ($order['shipping_service'] == 'shop/shipping/gateways/country'): ?>
                                    <?php _e("Shipping to country"); ?>
                                <?php else: ?>
                                    <?php echo $order['shipping_service']; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                    N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e("Address"); ?>:</small>
                        <p>
                            <?php
                            $zip = '';
                            if (isset($order['zip'])) {
                                $zip = $order['zip'];
                            }
                            ?>
                            <?php if (isset($order['country'])): ?><?php echo $order['country'] . ', '; ?><?php endif; ?>
                            <?php if (isset($order['state'])): ?><?php echo $order['state'] . ', '; ?><?php endif; ?>
                            <?php if (isset($order['city'])): ?><?php echo $order['city'] . ' ' . $zip . ', '; ?><?php endif; ?>
                            <?php if (isset($order['address'])): ?><?php echo $order['address']; ?><?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
