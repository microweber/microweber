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
<div class="products">
    <?php if (is_array($data)) : ?>
        <?php foreach ($data as $item) : ?>
            <div class="row product">
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

                <div class="col-lg-4 title">
                    <span><?php print $item['title'] ?></span>
                </div>
                <div class="col-lg-2 qty">
                    <div class="mw-qty-field">
                        <input type="number" class="form-control" name="qty" value="<?php print $item['qty'] ?>"  onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                    </div>
                </div>
                <div class="col-lg-3 price">
                    <span><?php print currency_format($item['price'] * $item['qty']); ?></span>
                </div>
                <div class="col-lg-1 action">
                    <a data-toggle="tooltip" title="Remove" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons">close</i></a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<div class="amount row">
    <div class="col-sm-6 promocode">
        <module type="shop/coupons" />

    </div>
    <div class="col-sm-6 total">
        <p><strong>Total Amount: <?php print currency_format($total); ?></strong></p>
        <a href="#" class="btn btn-default btn-block btn-lg js-show-step"   data-step="delivery-address">Checkout</a>
    </div>
</div>