<?php

/*

type: layout

name: Big 1

description: Full width cart template

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
<div class="mw-shopping-cart-big-layout-1">
    <div class="mw-cart mw-cart-big mw-cart-<?php print $params['id'] ?> <?php print  $template_css_prefix; ?>">
        <div class="mw-cart-title mw-cart-<?php print $params['id'] ?>">
            <h4 class="edit" rel="<?php print $params['id'] ?>" field="cart_title">
                <?php _e('My cart'); ?>
            </h4>
        </div>
        <?php if (is_array($data)) : ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2" class="mw-cart-table-product"><?php _e("Product"); ?></th>
                        <th class="right"><?php _e("Price"); ?></th>
                        <th class="center" style="min-width:130px;"><?php _e("Quantity"); ?></th>
                        <th class="right"><?php _e("Total"); ?></th>
                        <th class="right"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total = cart_sum();;
                    foreach ($data as $item) : ?>
                        <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
                            <td style="width: 100px;">
                                <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                    <?php $p = $item['item_image']; ?>
                                <?php else: ?>
                                    <?php $p = get_picture($item['rel_id']); ?>
                                <?php endif; ?>
                                <?php if ($p != false): ?>
                                    <img class="img-responsive mw-order-item-image mw-order-item-image-<?php print $item['id']; ?>" src="<?php print thumbnail($p, 100, 100); ?>"
                                         style="max-width: 100px; max-height: 100px;"/>
                                <?php endif; ?>
                            </td>
                            <td class="mw-cart-table-product left">
                                <?php print $item['title'] ?>
                                <?php if (isset($item['custom_fields'])): ?>
                                    <?php print $item['custom_fields'] ?>
                                <?php endif ?>
                            </td>
                            <td class="mw-cart-table-price right"><?php print currency_format($item['price']); ?></td>
                            <td class="center" style="min-width:130px;">
                                <div class="mw-qty-field">
                                    <button class="cartDecreaseProductsNumber" type="button"><i class="material-icons">expand_less</i></button>
                                    <input type="text" name="qty" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                                    <button class="cartIncreaseProductsNumber" type="button"><i class="material-icons">expand_more</i></button>
                                </div>
                            </td>
                            <td class="mw-cart-table-price right"><?php print currency_format($item['price'] * $item['qty']); ?></td>
                            <td class="right"><a title="<?php _e("Remove"); ?>" class="remove tip" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');" data-tip="<?php _e("Remove"); ?>" data-tipposition="top-center"><i class="material-icons">clear</i></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="shipping-info">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <module type="shop/discounts"/>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-3">
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Shipping information</h4>
                                <hr/>
                            </div>
                        </div>
                        <?php if (is_array($shipping_options)) : ?>
                            <div <?php if (!$show_shipping_stuff) : ?> style="display:none" <?php endif; ?>>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php _e("Shipping to"); ?>:
                                    </div>
                                    <div class="col-xs-6 right">
                                        <module type="shop/shipping" view="select"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6"><?php _e("Shipping price"); ?>:</div>
                                <div class="col-xs-6 right">
                                    <module type="shop/shipping" view="cost"/>
                                </div>
                            </div>

                            <?php if (function_exists('cart_get_tax') and get_option('enable_taxes', 'shop') == 1) : ?>
                                <div class="row">
                                    <div class="col-xs-6"><?php _e("Tax"); ?>:</div>
                                    <div class="col-xs-6 right">
                                        <?php print currency_format(cart_get_tax()); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <hr/>
                                </div>

                                <div class="col-xs-6 total-lable"><?php _e("Total Price"); ?>:</div>
                                <div class="col-xs-6 right total-price">
                                    <?php print currency_format($print_total); ?>
                                </div>
                            </div>
                        <?php endif; ?>
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