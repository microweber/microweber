<?php

/*

type: layout

name: Default

description: Default template

*/

?>

<script>
    $(document).ready(function () {
        $(".mw-shopping-cart-big-layout-1 .mw-qty-field .cartDecreaseProductsNumber").click(function () {
            var thisQty = $(this).parent().find('input');
            var inputVal = thisQty.val();

            if (inputVal < 2) {
                inputVal = 1;
                thisQty.val(inputVal);
            } else {
                inputVal = inputVal - 1;
                thisQty.val(inputVal);
            }
            thisQty.trigger('change');
        });

        $(".mw-shopping-cart-big-layout-1 .mw-qty-field .cartIncreaseProductsNumber").click(function () {
            var thisQty = $(this).parent().find('input');
            var inputVal = thisQty.val();
            inputVal = parseInt(inputVal) + (1);
            thisQty.val(inputVal);
            thisQty.trigger('change');
        });
    });
</script>

<div class="">
    <div class="mw-cart-<?php print $params['id'] ?> <?php print  $template_css_prefix; ?>">
        <?php if (is_array($data)) : ?>
            <div class="cartContent clearfix">
                <div id="cartContent">
                    <div class="item head clearfix">
                        <span class="cart_img"></span>
                        <span class="product_name size-13 bold"><?php _e("PRODUCT NAME"); ?></span>
                        <span class="remove_item size-13 bold"></span>
                        <span class="total_price size-13 bold"><?php _e("TOTAL"); ?></span>
                        <span class="qty size-13 bold"><?php _e("QUANTITY"); ?></span>
                    </div>

                    <?php
                    $total = cart_sum();
                    foreach ($data as $item) : ?>
                        <div class="item mw-cart-item-<?php print $item['id'] ?>">
                            <div class="cart_img pull-left width-100 padding-10 text-left">
                                <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                    <?php $p = $item['item_image']; ?>
                                <?php else: ?>
                                    <?php $p = get_picture($item['rel_id']); ?>
                                <?php endif; ?>
                                <?php if ($p != false): ?>
                                    <img class="img-responsive mw-order-item-image mw-order-item-image-<?php print $item['id']; ?>" src="<?php print thumbnail($p, 100, 100); ?>"
                                         style="max-width: 80px; max-height: 80px;"/>
                                <?php endif; ?>
                            </div>
                            <a href="<?php print $item['url'] ?>" class="product_name">
                                <span> <?php print $item['title'] ?></span>
                                <small>
                                    <?php if (isset($item['custom_fields'])): ?>
                                        <?php print $item['custom_fields'] ?>
                                    <?php endif ?>
                                </small>
                            </a>
                            <a href="javascript:mw.cart.remove('<?php print $item['id'] ?>');" class="remove_item remove tip" title="<?php _e("Remove"); ?>" data-tip="<?php _e("Remove"); ?>"
                               data-tipposition="top-center"><i class="fa fa-times"></i></a>
                            <div class="total_price">
                                <span><?php print currency_format($item['price'] * $item['qty']); ?></span>
                            </div>
                            <div class="qty">
                                <input type="number" name="qty" maxlength="3" max="999" min="1" value="<?php print $item['qty'] ?>"
                                       onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                                x <?php print currency_format($item['price']); ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <strong><?php _e("Shopping cart is empty!"); ?></strong><br/>
                    <?php _e("You have no items in your shopping cart."); ?><br/>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>