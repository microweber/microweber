<?php
$order = false;
$order_products = false;
$order_first_product = false;

$item_id = $id;

$item = $order = get_order_by_id($item_id);
$order_products = mw()->shop_manager->order_items($item_id);
if ($order_products) {
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

if(!$order){
    return;
}

?>

<div class="card mb-3 not-collapsed-border collapsed card-order-holder card-bubble <?php if ($is_read): ?>bg-silver<?php else: ?>card-success<?php endif; ?>"
     data-bs-toggle="collapse" data-bs-target="#notif-order-item-<?php print $item_id; ?>" aria-expanded="false"
     aria-controls="collapseExample">
    <div class="card-body py-2">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col item-image mt-3">
                        <?php if (is_array($order_products) && count($order_products) > 1): ?>
                        <button type="button" class="btn btn-primary btn-rounded position-absolute btn-sm"
                                style="width: 30px; right: 0; z-index: 9;"><?php echo count($order_products); ?></button>
                        <?php endif; ?>
                        <div class="img-circle-holder img-absolute">
                            <?php if ($order_first_product AND isset($order_first_product['item_image'])): ?>
                            <img src="<?php echo thumbnail($order_first_product['item_image'], 160, 160); ?>"/>
                            <?php else: ?>
                            <img src="<?php echo thumbnail(''); ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col item-id"></div>
                    <span class="text-primary">#<?php echo $order['id']; ?></span>
                    <div class="col item-title" style="min-width: 210px;">
                        <?php if ($order_first_product AND isset($order_first_product['title'])): ?>
                        <span class="text-primary text-break-line-2"><?php echo $order_first_product['title']; ?></span>
                        <?php endif; ?>

                        <?php if (isset($created_by_username)): ?>
                        <small class="text-muted"><?php _e('Ordered by'); ?>: <?php echo $created_by_username; ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="row align-items-center h-100">
                    <div class="col-6 col-sm-4 col-md item-amount">
                        <?php if (isset($order['amount'])): ?><?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?>
                        <br/><?php endif; ?>
                        <?php if (isset($order['is_paid']) and intval($order['is_paid']) == 1): ?>
                        <small class="text-success"><?php _e('Paid'); ?></small>
                        <?php else: ?>
                        <small class="text-muted"><?php _e('Unpaid'); ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 col-sm-4 col-md item-date" data-bs-toggle="tooltip"
                         title="<?php _e(mw('format')->ago($item['created_at'])); ?>">
                        <?php _e(date('M d, Y', strtotime($item['created_at']))); ?><br/>
                        <small class="text-muted"><?php _e(date('h:s', strtotime($item['created_at']))); ?>h
                        </small>
                    </div>

                    <div class="col-12 col-sm-4 col-md item-status">
                        <?php if (isset($item['is_read']) && $item['is_read'] == '0'): ?>
                        <span class="text-success"><?php _e('New'); ?></span><br/>
                        <?php endif; ?>
                        <small class="text-muted">&nbsp;</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center text-sm-left js-change-button-styles">
                <a href="<?php echo route('admin.order.show', $order['id']); ?>"
                   class="btn btn-outline-primary btn-sm btn-rounded"><?php _e('View order'); ?></a>
            </div>
        </div>

        <div class="collapse" id="notif-order-item-<?php print $item_id; ?>">


            <hr class="thin"/>

            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h6><strong><?php _e('Customer Information'); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e('Client name'); ?>:</small>
                        <p>
                            <?php if (isset($order['first_name']) OR isset($order['last_name'])): ?>
                                <?php if (isset($order['first_name'])): ?><?php echo $order['first_name'] . ' '; ?><?php endif; ?>
                                <?php if (isset($order['last_name'])): ?><?php echo $order['last_name']; ?><?php endif; ?>
                            <?php else: ?>
                            <?php _e('N/A'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e('E-mail'); ?>:</small>
                        <p>
                            <?php if (isset($order['email'])): ?>
                                <?php echo $order['email']; ?>
                            <?php else: ?>
                            <?php _e('N/A'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e('Phone'); ?>:</small>
                        <p>
                            <?php if (isset($order['phone'])): ?>
                                <?php echo $order['phone']; ?>
                            <?php else: ?>
                            <?php _e('N/A'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong><?php _e('Payment Information'); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e('Amount'); ?>:</small>
                        <p>
                            <?php if (isset($order['amount'])): ?>
                                <?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?>
                            <?php else: ?>
                            <?php _e('N/A'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e('Payment method'); ?>:</small>
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
                    <h6><strong><?php _e('Shipping Information'); ?></strong></h6>

                    <div>
                        <small class="text-muted"><?php _e('Shipping method'); ?>:</small>
                        <p>
                            <?php if (isset($order['shipping_service'])): ?>
                            <?php if ($order['shipping_service'] == 'shop/shipping/gateways/country'): ?>
                            <?php _e('Shipping to country'); ?>
                            <?php else: ?>
                                    <?php echo $order['shipping_service']; ?>
                                <?php endif; ?>
                            <?php else: ?>
                            <?php _e('N/A'); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted"><?php _e('Address'); ?>:</small>
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


