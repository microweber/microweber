
<script>

    window.orderToggle = window.orderToggle || function (item) {
        var curr = $('.order-data-more', item);
        $('.order-data-more').not(curr).stop().slideUp();
        $('.order-holder').not(item).removeClass('active');
        $(curr).stop().slideToggle();
        $(item).toggleClass('active');
        $('#mw-order-table-holder').toggleClass('has-active');
    }
</script>
<?php if($orders): ?>
<?php foreach ($orders as $item) : ?>


    <div class="order-holder" id="order-n-<?php print $item['id'] ?>" onclick="orderToggle(this);">
        <div class="order-data">
            <div class="product-image">
                <?php $cart_item = get_cart('no_session_id=true&order_completed=any&order_id=' . $item['id'] . ''); ?>
                <?php if (isset($cart_item[0]) and isset($cart_item[0]['rel_id'])) { ?>
                    <?php $p = get_picture($cart_item[0]['rel_id'], $cart_item[0]['rel_type']); ?>
                    <?php if ($p == false and isset($cart_item[0]['item_image']) and $cart_item[0]['item_image'] != false): ?>
                        <?php $p = $cart_item[0]['item_image']; ?>
                    <?php endif; ?>

                    <?php if (isset($p) and $p != false): ?>
                        <span class="product-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($p, 120, 120); ?>)"></span>
                    <?php else: ?>
                        <span class="product-thumbnail-tooltip" style="background-image: url(<?php print thumbnail('', 120, 120); ?>)"></span>
                    <?php endif; ?>

                    <?php if (count($cart_item) > 1): ?>
                        <div class="cnt-products">+<?php echo count($cart_item) - 1; ?></div>
                    <?php endif; ?>
                <?php } else { ?>
                    <span class="product-thumbnail-tooltip" style="background-image: url(<?php print thumbnail('', 120, 120); ?>)"></span>

                <?php } ?>
            </div>

            <div class="order-number">
                <a class="mw-ord-id" href="#vieworder=<?php print ($item['id']) ?>">#<?php print $item['id'] ?></a>
            </div>

            <div class="product-name">
                <?php if (isset($cart_item[0]) and isset($cart_item[0]['rel_id'])): ?>
                    <?php print($cart_item[0]['title']); ?>
                <?php endif; ?>
            </div>

            <div>
                <?php if ($item['is_paid'] == 1): ?>
                    <span class="is_paid">
                                <?php print currency_format(floatval($item['amount']), $item['currency'])

                                // print currency_format(floatval($item['amount']) + floatval($item['shipping']),$item['currency']) ?>
                                </span>
                <?php else : ?>
                    <span class="not_paid">
                                <?php _e("Not paid"); ?>
                            </span>
                <?php endif; ?>

            </div>

            <div><?php print date('M d, Y', strtotime($item['created_at'])); ?></div>


            <div>
                <?php if ($item['order_status'] == false): ?>
                    <?php _e("New"); ?>
                <?php elseif ($item['order_status'] == 'completed'): ?>
                    <span class="mw-order-item-status-completed"><?php _e("Completed"); ?></span>
                <?php else : ?>
                    <span class="mw-order-item-status-pending"><?php _e("Pending"); ?> </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="order-data-more mw-accordion-content">
            <a class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info view-order-button" id="vieorder-<?php print $item['id']; ?>" href="#vieworder=<?php print ($item['id']) ?>">
                <?php _e("View order"); ?>
            </a>

            <hr/>
            <div class="pull-left">
                <p class="title"><?php print _e('Customer Information'); ?></p>

                <?php if (isset($item['first_name']) AND isset($item['last_name'])): ?>
                    <div class="box">
                        <p class="label"><?php print _e('Client Name:'); ?></p>
                        <p class="content"><a href="<?php print admin_url() ?>view:shop/action:clients#?clientorder=<?php print $item['id'] ?>"><?php print $item['first_name'] . ' ' . $item['last_name']; ?></a></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($item['phone'])): ?>
                    <div class="box">
                        <p class="label"><?php print _e('Phone:'); ?></p>
                        <p class="content"><?php print $item['phone']; ?></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($item['email'])): ?>
                    <div class="box">
                        <p class="label"><?php print _e('E-mail:'); ?></p>
                        <p class="content"><?php print $item['email'] ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pull-left">
                <p class="title"><?php print _e('Shipping Information'); ?></p>

                <div class="box">
                    <p class="label">Address:</p>
                    <p class="content">
                        <?php if (isset($item['country'])): ?>
                            <?php print $item['country']; ?>,
                        <?php endif; ?>
                        <?php if (isset($item['city'])): ?>
                            <?php print $item['city']; ?>
                        <?php endif; ?>
                        <?php if (isset($item['zip'])): ?>
                            <?php print $item['zip']; ?>,
                        <?php endif; ?>
                        <?php if (isset($item['address'])): ?>
                            <?php print $item['address']; ?>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (isset($item['comment'])): ?>
                    <div class="box">
                        <p class="label"><?php print _e('Comment:'); ?></p>
                        <p class="content"><?php print $item['comment'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <span class="mw-icon-close new-close tip" data-tip="<?php _e("Delete"); ?>" data-tipposition="top-center"
              onclick="mw_delete_shop_order('<?php print ($item['id']) ?>');"></span>
        <div class="clearfix"></div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
