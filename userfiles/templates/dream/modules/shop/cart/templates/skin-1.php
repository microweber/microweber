<?php

/*

type: layout

name: Shopping cart - Checkout page

description: Full width cart template

*/

?>

<div class="">
    <div class="mw-cart mw-cart-big mw-cart-<?php print $params['id'] ?> <?php print  $template_css_prefix; ?>">
        <div class="mw-cart-title mw-cart-<?php print $params['id'] ?>">
            <h4 class="edit" rel="<?php print $params['id'] ?>" field="cart_title">
                <?php _e('My cart'); ?>
            </h4>
        </div>

        <?php if (is_array($data)) : ?>
            <div class="row">
                <?php
                $total = cart_sum();
                foreach ($data as $item) : ?>
                    <div class="col-md-3 col-sm-4 mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
                        <div class="card card-10">
                            <div class="card__image">
                                <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                    <?php $p = $item['item_image']; ?>
                                <?php else: ?>
                                    <?php $p = get_picture($item['rel_id']); ?>
                                <?php endif; ?>
                                <?php if ($p != false): ?>
                                    <img class="mw-order-item-image mw-order-item-image-<?php print $item['id']; ?>" src="<?php print thumbnail($p, 800, 800); ?>"/>
                                <?php endif; ?>
                            </div>

                            <div class="card__body boxed  bg--white text-center">
                                <div class="card__title">
                                    <h5><?php print $item['title'] ?></h5>
                                </div>
                                <div class="card__price">
                                    <span><?php print currency_format($item['price']); ?></span>
                                </div>
                                <div>
                                    <?php if (isset($item['custom_fields'])): ?>
                                        <?php print $item['custom_fields'] ?>
                                    <?php endif ?>
                                </div>

                                <span class="h6">QTY:</span>
                                <input type="text" name="qty" class="text-center" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                                <div class="clearfix"></div>
                                <a title="<?php _e("Remove"); ?>" style="display: block; margin-top: 20px;" class="remove tip"
                                   href="javascript:mw.cart.remove('<?php print $item['id'] ?>');">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="shipping-info">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <module type="shop/discounts"/>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <h4 class="alert alert-warning">
                <?php _e("Your cart is empty."); ?>
            </h4>
        <?php endif; ?>
    </div>
</div>