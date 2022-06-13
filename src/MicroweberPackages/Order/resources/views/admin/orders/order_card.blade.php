<?php

if (!$order) {
    return;
}

$orderUser = $order->user()->first();
if ($order->customer_id > 0) {
    $orderUser = \MicroweberPackages\Customer\Models\Customer::where('id', $order->customer_id)->first();
}

$carts = $order->cart()->with('products')->get();
$firstProduct = [];
foreach ($carts as $cart) {
    if (isset($cart->products[0])) {
        $firstProduct = $cart->products[0];
        break;
    }
}

$item = $order->toArray();
?>

<script>
    $( document ).ready(function() {
        $('.collapse', '.js-order-card-<?php print $order['id'] ?>').on('shown.bs.collapse', function () {
            $('.js-order-card-<?php print $order['id'] ?>').prop('disabled',true).removeAttr('data-toggle');
        });
    });

</script>

<div class="js-order-card-<?php print $order['id'] ?> card <?php if($order['order_status']=='new'):?>card-success<?php endif;?> mb-3 not-collapsed-border collapsed card-order-holder" data-bs-toggle="collapse" data-bs-target="#notif-order-item-<?php echo $order['id'];?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body py-2">
        <div class="row">
            <div class="col-12 col-md-6" data-bs-toggle="collapse" data-bs-target="#notif-order-item-<?php print $order['id'] ?>">
                <div class="row align-items-center">

                    <div class="col item-image">
                        <?php if (count($carts) > 1): ?>
                        <button type="button" class="btn btn-primary btn-rounded position-absolute btn-sm"
                                style="width: 30px; right: 0; z-index: 9;">
                            <?php echo count($carts); ?>
                        </button>
                        <?php endif; ?>
                        <div class="img-circle-holder img-absolute">
                            <?php
                            $firstProductImage = '';
                            if (is_object($firstProduct) and is_object($firstProduct->media()->first())) {
                                $firstProductImage = $firstProduct->media()->first()->filename;
                            }
                            ?>
                            <?php if (!empty($firstProductImage)): ?>
                            <img src="<?php echo thumbnail($firstProductImage, 160, 160); ?>"/>
                            <?php else: ?>
                            <img src="<?php echo thumbnail(''); ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col item-id"><span class="text-primary">#<?php echo $order['id']; ?></span></div>

                    <div class="col item-title" style="min-width: 210px;">
                        <?php if (!empty($firstProduct['title'])): ?>
                        <span class="text-primary text-break-line-2"><?php echo $firstProduct['title']; ?></span>
                        <?php endif; ?>

                        <?php if ($orderUser): ?>
                        <small class="text-muted"><?php _e("Created by"); ?>:
                            <?php
                            if ($orderUser->first_name) {
                                echo $orderUser->first_name;
                                if ($orderUser->last_name) {
                                    echo " " . $orderUser->last_name;
                                }
                            } else if ($orderUser) {
                                echo $orderUser->username;
                            }
                            ?>
                        </small>
                        <?php endif; ?>
                            <small class="text-muted  text-break-line-2"><?php print mw('format')->ago($order['created_at']); ?>  </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="row align-items-center h-100">
                    <div class="col-6 col-sm-4 col-md item-amount">
                        <?php if (isset($order['amount'])): ?><?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?>
                        <br/><?php endif; ?>
                        <?php if (isset($order['is_paid']) and intval($order['is_paid']) == 1): ?>

                        <?php if (isset($order['payment_status']) && $order['payment_status']): ?>
                        <small class="text-success"><?php _e($order['payment_status']); ?></small>
                        <?php else: ?>
                        <small class="text-success"><?php _e('Paid'); ?></small>
                        <?php endif; ?>

                        <?php else: ?>

                        <?php if (isset($order['payment_status']) && $order['payment_status']): ?>
                        <small class="text-muted"><?php _e($order['payment_status']); ?></small>
                        <?php else: ?>
                        <small class="text-muted"><?php _e('Unpaid'); ?></small>
                        <?php endif; ?>

                        <?php endif; ?>
                    </div>

                    <div class="col-6 col-sm-4 col-md item-date" data-bs-toggle="tooltip"
                         title="<?php print mw('format')->ago($order['created_at']); ?>">
                        <?php print date('M d, Y', strtotime($order['created_at'])); ?><br/>
                        <small class="text-muted"><?php print date('H:s', strtotime($order['created_at'])); ?><span
                                class="text-success"> </span><br/></small>
                    </div>

                    <div class="col-12 col-sm-4 col-md item-status">
                        <?php if (isset($order['is_read']) && $order['is_read'] == '0'): ?>
                        <span class="text-success"><?php _e("New"); ?></span><br/>
                        <?php endif; ?>
                        <small class="text-muted">&nbsp;</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center text-sm-left js-change-button-styles">
                <a href="<?php echo route('admin.order.show', $order['id']); ?>"
                   class="btn btn-outline-primary btn-sm btn-rounded"><?php _e("View order"); ?></a>
            </div>
        </div>

        <div class="collapse" id="notif-order-item-<?php echo $order['id']; ?>">

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
                                <?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?>
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
