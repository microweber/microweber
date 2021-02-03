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



<?php
$total = cart_total();
?>
<div class="container">
    <h5 class="mb-3"><?php _lang("List of Products", "templates/big"); ?></h5>
    <div class="checkout-modal-products-wrapper row">
        <?php if (is_array($data) and $data) : ?>
            <table class="table table-responsive w-100 d-block d-md-table">
                <thead>
                <tr class="font-weight-bold">
                    <th scope="col" class="text-muted">Image</th>
                    <th scope="col" class="text-muted">Product</th>
                    <th scope="col" class="text-muted">Price</th>
                    <th scope="col" class="text-muted">Qty</th>
                    <th scope="col" class="text-muted">Total</th>
                    <th scope="col" class="text-muted">Actions</th>
                </tr>
                </thead>
                <?php foreach ($data as $item) : ?>
                    <tbody>
                    <tr>
                        <td>
                            <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                <?php $p = $item['item_image']; ?>
                            <?php else: ?>
                                <?php $p = get_picture($item['rel_id']); ?>
                            <?php endif; ?>
                            <?php if ($p != false): ?>
                                <img src="<?php print thumbnail($p, 70, 70, true); ?>" alt=""/>
                            <?php endif; ?>
                        </td>
                        <td class="td-title"><?php print _lang($item['title'], "template/big") ?></td>
                        <td><?php print currency_format($item['price']); ?></td>
                        <td> <div class="mw-qty-field">
                                <input min=0 type="number" class="form-control m-0" name="qty" value="<?php print $item['qty'] ?>"  onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)" style="width: 70px;"/>
                            </div></td>
                        <td><?php print currency_format($item['price'] * $item['qty']); ?></td>
                        <td> <a data-toggle="tooltip" title="<?php _lang("Remove", "templates/big"); ?>" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="material-icons text-danger d-flex justify-content-center">delete_forever</i></a></td>
                    </tr>
                    </tbody>

                <?php endforeach; ?>
                <thead>
                <tr class="font-weight-bold">
                    <th scope="col" class="text-muted">Image</th>
                    <th scope="col" class="text-muted">Product</th>
                    <th scope="col" class="text-muted">Price</th>
                    <th scope="col" class="text-muted">Qty</th>
                    <th scope="col" class="text-muted">Total</th>
                    <th scope="col" class="text-muted">Actions</th>
                </tr>
                </thead>
            </table>

        <?php else: ?>

            <h5><?php _lang("Your cart is empty. Please add some products in the cart.", "templates/big"); ?></h5>

        <?php endif; ?>

    </div>

    <?php if (is_array($data) and $data) : ?>
        <div class="checkout-modal-amount-holder row mt-4">
            <div class="col-sm-6 checkout-modal-promocode-holder ml-auto">
                <?php if (get_option('enable_coupons', 'shop') == 1): ?>
                    <?php
                    $discountData = app()->cart_manager->totals('discount');
                    ?>
                    <module type="shop/coupons" template="modal" />
                <?php endif; ?>
            </div>
            <div class="col-sm-6 checkout-modal-total-holder ">
                <div class="d-flex justify-content-center align-items-center">
                    <module type="shop/cart" template="totals" />
                </div>
                <?php


                /*<p><strong><?php _lang('Tax Amount:', "templates/big"); ?> <?php print currency_format(cart_get_tax()); ?></strong></p>
                <p><strong><?php _lang('Total Amount:', "templates/big"); ?> <?php print currency_format($total); ?></strong></p>
                <?php if(!empty($discountData)) :?>
                    <p><strong><?php _lang('Coupon Name:', "templates/big"); ?> <?php print $discountData['label']; ?></strong></p>
                    <p><strong><?php _lang('Discount Amount:', "templates/big"); ?> <?php print $discountData['amount']; ?></strong></p>
                <?php endif?>*/


                ?>
                <a href="#" class="btn btn-primary d-flex justify-content-center btn-lg rounded mt-1 js-show-step" data-step="delivery-address"><?php _lang('Checkout', "templates/big"); ?></a>
            </div>

        </div>
    <?php endif; ?>
</div>