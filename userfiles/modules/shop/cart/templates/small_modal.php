<?php
/*

  type: layout

  name: Small Modal

  description: Small Modal

 */
?>


<?php $total = cart_sum(); ?>

<?php if (is_array($data)) : ?>
    <div class="products ">
        <?php foreach ($data as $item) : ?>
            <div class="row product-item align-items-center mx-1 my-2">
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
                    <div class="row m-1">
                        <div class="col-12 d-flex item-title m-1">
                            <a class="" title="" href="<?php print $item['url'] ?>"><?php print $item['title'] ?></a>
                        </div>
                        <div class="col-12 d-flex item-price m-1">
                            <span class="text-small py-1"><?php print $item['qty'] ?> <span class="px-1">x</span></span>
                            <span class="p-1"><?php print currency_format($item['price']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-2 d-flex item-action justify-content-end">
                    <a data-toggle="tooltip" title="<?php _e("Remove"); ?>" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons text-danger">delete_forever</i></a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="products-amount m-3">
    <div class="row align-items-center">
        <?php if (is_array($data)): ?>
            <div class="col-12 col-sm-6 total">
                <h6><strong><?php _e("Total Amount: "); ?> <br class="d-none d-sm-block"> <?php print currency_format($total); ?></strong></h6>
            </div>
            <div class="col-12 col-sm-6">
                <button type="button" class="btn btn-primary btn-md w-100 justify-content-center float-end" data-bs-toggle="modal" data-bs-target="#shoppingCartModal"><?php _e("Checkout"); ?></button>
            </div>
        <?php else: ?>
            <div class="col-12">
                <p class="mb-0 text-center"><?php _e("Your cart is empty."); ?></p>
            </div>
        <?php endif; ?>
    </div>

</div>

