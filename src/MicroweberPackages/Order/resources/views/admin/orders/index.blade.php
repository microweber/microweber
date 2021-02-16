<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php print _e('Add new order'); ?></button>
            </h5>

            <div class="js-hide-when-no-items">
                <div class="js-search-by d-inline-block">
                    <div class="js-search-by-keywords">
                        <form method="get">
                        <div class="form-inline">
                            <div class="input-group mb-0 prepend-transparent mx-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                                </div>
                                <input type="hidden" name="searchInFields" value="id" />
                                <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control form-control-sm" style="width: 220px;" placeholder="<?php _e("Search"); ?>"/>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-magnify"></i></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-3 pb-0">
            <form method="get">
            <div class="manage-toobar d-flex justify-content-between align-items-center">
                <?php if (count($orders) != 0) { ?>
                <div id="cartsnav">
                    <a href="?orderstype=completed" class="btn btn-link btn-sm px-0 text-dark active"><?php _e("Completed orders"); ?></a>
                    <a href="?orderstype=carts" class="btn btn-link btn-sm text-muted"><?php _e("Abandoned carts"); ?></a>
                </div>

                <div class="js-table-sorting text-right my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
                    <small><?php _e("Sort By"); ?>: &nbsp;</small>

                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="created_at">
                            <?php _e("Date"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="order_status">
                            <?php _e("Status"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="amount">
                            <?php _e("Amount"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                </div>
                <?php } ?>
            </div>
            </form>

            <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>

            <?php
            $orderUser = $order->user()->first();
            $orderProducts = $order->carts()->get();
            $firstProduct = $orderProducts[0];
            ?>

            <div class="card mb-3 not-collapsed-border collapsed card-order-holder active bg-silver" data-toggle="collapse" data-target="#notif-order-item-<?php echo $order['id'];?>" aria-expanded="false" aria-controls="collapseExample">
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row align-items-center">
                                
                                <div class="col item-image">
                                    <?php if (count($orderProducts) > 1): ?>
                                    <button type="button" class="btn btn-primary btn-rounded position-absolute btn-sm" style="width: 30px; right: 0; z-index: 9;">
                                        <?php echo count($orderProducts); ?>
                                    </button>
                                    <?php endif; ?>
                                    <div class="img-circle-holder img-absolute">
                                        <?php if (!empty($firstProduct['item_image'])): ?>
                                        <img src="<?php echo thumbnail($firstProduct['item_image'], 160, 160); ?>"/>
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
                                    <small class="text-muted"><?php _e("Ordered by"); ?>: <?php echo $orderUser->username; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="row align-items-center h-100">
                                <div class="col-6 col-sm-4 col-md item-amount">
                                    <?php if (isset($order['amount'])): ?><?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?><br/><?php endif; ?>
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

                                <div class="col-6 col-sm-4 col-md item-date" data-toggle="tooltip" title="<?php print mw('format')->ago($order['created_at']); ?>">
                                    <?php print date('M d, Y', strtotime($order['created_at'])); ?><br/>
                                    <small class="text-muted"><?php print date('h:s', strtotime($order['created_at'])); ?><span class="text-success"><?php _e("h"); ?></span><br/></small>
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
                            <a href="<?php print admin_url('view:shop/action:orders#vieworder=' . $order['id']); ?>" class="btn btn-outline-primary btn-sm btn-rounded"><?php _e("View order"); ?></a>
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
                                        <?php if (isset($order['payment_type'])): ?>
                                <?php echo $order['payment_type']; ?>
                            <?php else: ?>
                                        N/A
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

            <?php endforeach;?>
             <?php endif; ?>


            <?php if (($filteringResults == true) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_results.png'); ">
                            <h4><?php _e("No orders found for this query filtering"); ?></h4>
                            <p><?php _e("Try with other filters"); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
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
            </div>
            <?php endif; ?>

            <div class="d-flex">
                <div class="mx-auto">
                    <?php echo $orders->links("pagination::bootstrap-4"); ?>
                </div>
            </div>

        </div>
    </div>
</div>