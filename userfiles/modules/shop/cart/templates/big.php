<?php

/*

type: layout

name: Big

description: Full width cart template

*/

?>

<div class="mw-cart mw-cart-big mw-cart-<?php print $params['id'] ?> <?php print  $template_css_prefix; ?>">
    <div class="mw-cart-title mw-cart-<?php print $params['id'] ?>">
        <h4 class="edit" rel="<?php print $params['id'] ?>" field="cart_title">
            <?php _e('My cart'); ?>
        </h4>
    </div>
    <?php if (is_array($data)) : ?>
        <table class="table table-bordered table-striped mw-cart-table mw-cart-table-medium mw-cart-big-table">
            <colgroup>
                <col width="60">
                <col width="620">
                <col width="120">
                <col width="140">
                <col width="140">
            </colgroup>
            <thead>
            <tr>
                <th><?php _e("Image"); ?></th>
                <th class="mw-cart-table-product"><?php _e("Product Name"); ?></th>
                <th><?php _e("QTY"); ?></th>
                <th><?php _e("Price"); ?></th>
                <th><?php _e("Total"); ?></th>
                <th><?php _e("Delete"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = cart_sum();;
            foreach ($data as $item) :
                //$total += $item['price']* $item['qty'];
                ?>
                <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
                    <td><?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                            <?php $p = $item['item_image']; ?>
                        <?php else: ?>
                            <?php $p = get_picture($item['rel_id']); ?>
                        <?php endif; ?>
                        <?php if ($p != false): ?>
                            <img height="70" class="img-polaroid img-rounded mw-order-item-image mw-order-item-image-<?php print $item['id']; ?>" src="<?php print thumbnail($p, 70, 70); ?>"/>
                        <?php endif; ?></td>
                    <td class="mw-cart-table-product"><?php print $item['title'] ?>
                        <?php if (isset($item['custom_fields'])): ?>
                            <?php print $item['custom_fields'] ?>
                        <?php endif ?></td>
                    <td><input type="number" min="1" class="input-mini form-control input-sm" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                    </td>
                    <?php /* <td><?php print currency_format($item['price']); ?></td> */ ?>
                    <td class="mw-cart-table-price"><?php print currency_format($item['price']); ?></td>
                    <td class="mw-cart-table-price"><?php print currency_format($item['price'] * $item['qty']); ?></td>
                    <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $shipping_options = mw('shop\shipping\shipping_api')->get_active(); ?>
        <?php

        $show_shipping_info = get_option('show_shipping', $params['id']);

        if ($show_shipping_info === false or $show_shipping_info == 'y') {
            $show_shipping_stuff = true;
        } else {
            $show_shipping_stuff = false;
        }

        if (is_array($shipping_options)) :?>
            <div>
                <h3>
                    <?php _e("Order summary"); ?>
                </h3>
                <table cellspacing="0" cellpadding="0" class="table table-bordered table-striped mw-cart-table mw-cart-table-medium checkout-total-table" width="100%">
                    <style scoped="scoped">
                        td {
                            white-space: nowrap;
                        }

                        .checkout-total-table {
                            table-layout: fixed;
                        }

                        .checkout-total-table label {
                            display: block;
                            text-align: right;
                        }

                        .cell-shipping-total, .cell-shipping-price {
                            text-align: right;
                        }

                        .total_cost {
                            font-weight: normal;
                        }

                    </style>
                    <col width="60%">
                    <col width="">
                    <col width="">
                    <tbody>
                    <tr <?php if (!$show_shipping_stuff) : ?> style="display:none" <?php endif; ?>>
                        <td></td>
                        <td class="cell-shipping-country"><label>
                                <?php _e("Shipping to"); ?>
                                :</label></td>
                        <td class="cell-shipping-country">
                            <module type="shop/shipping" view="select"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><label>
                                <?php _e("Shipping price"); ?>
                                :</label></td>
                        <td class="cell-shipping-price">
                            <div class="mw-big-cart-shipping-price" style="display:inline-block">
                                <module type="shop/shipping" view="cost"/>
                            </div>
                        </td>
                    </tr>


                    <?php if (function_exists('cart_get_tax') and get_option('enable_taxes', 'shop') == 1) { ?>

                        <tr>
                            <td></td>
                            <td><label>
                                    <?php _e("Tax"); ?>
                                    :</label></td>
                            <td class="cell-shipping-price"><?php print currency_format(cart_get_tax()); ?></td>
                        </tr>

                    <?php } ?>


                    <tr>
                        <td></td>
                        <td><label>
                                <?php _e("Total Price"); ?>
                                :</label></td>
                        <td class="cell-shipping-total">
                            <?php
                            $print_total = cart_total();

                            ?>
                            <span class="total_cost"><?php print currency_format($print_total); ?></span>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php
        if (!isset($params['checkout-link-enabled'])) {
            $checkout_link_enanbled = get_option('data-checkout-link-enabled', $params['id']);
        } else {
            $checkout_link_enanbled = $params['checkout-link-enabled'];
        }
        ?>
        <?php if ($checkout_link_enanbled != 'n') : ?>
            <?php $checkout_page = get_option('data-checkout-page', $params['id']); ?>
            <?php if ($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0) {
                $checkout_page_link = content_link($checkout_page) . '/view:checkout';
            } else {
                $checkout_page_link = site_url('checkout');;
            }
            ?>
            <a class="btn  btn-warning pull-right" href="<?php print $checkout_page_link; ?>">
                <?php _e("Checkout"); ?>
            </a>
        <?php endif; ?>
    <?php else : ?>
        <h4 class="alert alert-warning">
            <?php _e("Your cart is empty."); ?>
        </h4>
    <?php endif; ?>
</div>
