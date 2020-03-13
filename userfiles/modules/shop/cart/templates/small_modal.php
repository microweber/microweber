<?php
/*

  type: layout

  name: Small Modal

  description: Small Modal

 */
?>

<style>
    .dropdown-menu.shopping-cart {
        min-width: 25rem;
    }


</style>

<?php $total = cart_sum(); ?>

<?php if (is_array($data)) : ?>
    <div class="products">
        <?php foreach ($data as $item) : ?>
            <div class="form-row product-item align-items-center">
                <div class="col-3 d-flex item-img">
                    <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                        <?php $p = $item['item_image']; ?>
                    <?php else: ?>
                        <?php $p = get_picture($item['rel_id']); ?>
                    <?php endif; ?>

                    <?php if ($p != false): ?>
                        <img src="<?php print thumbnail($p, 70, 70, true); ?>" alt=""/>
                    <?php endif; ?>
                </div>
                <div class="col-7">
                    <div class="form-row">
                        <div class="col-12 d-flex item-title">
                            <a class="" title="" href="<?php print $item['url'] ?>"><?php print $item['title'] ?></a> <span class="text-small pl-1">x<?php print $item['qty'] ?></span>
                        </div>

                        <div class="col-12 d-flex item-price">
                            <span><?php print currency_format($item['price']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-2 d-flex item-action justify-content-end">
                    <a data-toggle="tooltip" title="Remove" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons">delete_forever</i></a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (is_ajax()) : ?>

    <script>
        $(document).ready(function () {
            //  cartModalBindButtons();

        });
    </script>

<?php endif; ?>

<div class="products-amount">
    <div class="form-row align-items-center">
        <?php if (is_array($data)): ?>
            <div class="col-12 col-sm-6 total">
                <p><strong><?php _e("Total Amount: "); ?> <br class="d-none d-sm-block"> <?php print currency_format($total); ?></strong></p>
            </div>
            <div class="col-12 col-sm-6">
                <button type="button" class="btn btn-primary btn-md btn-block" data-toggle="modal" data-target="#shoppingCartModal">Checkout</button>
            </div>
        <?php else: ?>
            <div class="col-12">
                <p><?php _e("Your cart is empty."); ?></p>
            </div>
        <?php endif; ?>
    </div>

</div>

