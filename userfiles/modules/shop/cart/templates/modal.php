<?php

/*

type: layout

name: Small Modal

description: Small Modal

*/
?>

<?php if (is_ajax()) : ?>

    <script>
        $(document).ready(function () {


            
         //   cartModalBindButtons();

        });
    </script>

<?php endif; ?>




<?php $total = cart_sum(); ?>
<div class="checkout-modal-products-wrapper">
    <?php if (is_array($data) and $data) : ?>
        <?php foreach ($data as $item) : ?>
            <div class="row checkout-modal-product-list-item">
                <div class="col-lg-2">
                    <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                        <?php $p = $item['item_image']; ?>
                    <?php else: ?>
                        <?php $p = get_picture($item['rel_id']); ?>
                    <?php endif; ?>

                    <?php if ($p != false): ?>
                        <img src="<?php print thumbnail($p, 70, 70, true); ?>" alt=""/>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 checkout-modal-product-list-item-title">
                    <span><?php print $item['title'] ?></span>
                </div>
                <div class="col-lg-2 checkout-modal-product-list-item-qty">
                    <div class="mw-qty-field">
                        <input type="number" class="form-control" name="qty" value="<?php print $item['qty'] ?>"  onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                    </div>
                </div>
                <div class="col-lg-3 checkout-modal-product-list-item-price">
                    <span><?php print currency_format($item['price'] * $item['qty']); ?></span>
                </div>
                <div class="col-lg-1 checkout-modal-product-list-item-action">
                    <a data-toggle="tooltip" title="Remove" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons">close</i></a>
                </div>
            </div>
        <?php endforeach; ?>



    <?php else: ?>

    <h5><?php _e("Your cart is empty."); ?> <?php _e("Please add some products in the cart."); ?></h5>

    <?php endif; ?>

</div>

<?php if (is_array($data) and $data) : ?>
<div class="checkout-modal-amount-holder row">
    <div class="col-sm-6 checkout-modal-promocode-holder">
    <?php if (get_option('enable_coupons', 'shop') == 1): ?>

        <module type="shop/coupons" template="modal" />
    <?php endif; ?>
    </div>
    <div class="col-sm-6 checkout-modal-total-holder">
        <p><strong><?php _e('Tax Amount:'); ?> <?php print currency_format(cart_get_tax()); ?></strong></p>
        <p><strong><?php _e('Total Amount:'); ?> <?php print currency_format($total); ?></strong></p>
        <a href="#" class="btn btn-default btn-block btn-lg js-show-step"   data-step="delivery-address"><?php _e('Checkout'); ?></a>
    </div>
</div>
<?php endif; ?>