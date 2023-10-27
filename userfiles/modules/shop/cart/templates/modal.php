<?php

/*

type: layout

name: Small Modal

description: Small Modal

*/
?>

<?php if (is_ajax()) : ?>

    <script>
        function check_qty(el) {

            if(el.value <= 0) {
                el.value = 1;
            }
        }

        $(document).ready(function () {

            //   cartModalBindButtons();

        });
    </script>
<?php endif; ?>

<?php
$total = cart_total();
?>
<div class="checkout-modal-products-wrapper">
    <div class="products">
        <?php if (is_array($data) and $data) : ?>
            <?php foreach ($data as $item) :?>
                <div class="row checkout-modal-product-list-item align-items-center py-lg-4 ps-lg-4 mb-3">
                    <div class="col-12">
                        <h4 class="mb-2"><?php _e($item['title']) ?></h4>
                        <small class="text-muted mw-order-custom-fields">
                            <?php if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
                                <?php print $item['custom_fields'] ?>
                            <?php endif ?>
                        </small>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="col-lg col">
                            <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                <?php $p = $item['item_image']; ?>
                            <?php else: ?>
                                <?php $p = get_picture($item['rel_id']); ?>
                            <?php endif; ?>
                            <?php if ($p != false): ?>
                                <img style="min-height: 70px; min-width: 70px;" src="<?php print thumbnail($p, 150, 150, true); ?>" alt=""/>
                            <?php endif; ?>
                        </div>



                       <div class="d-flex justify-content-end align-items-center">
                           <div class="col-lg col">
                               <p class="mb-0"><?php print currency_format($item['price']); ?></p>
                           </div>
                           <div class="col-3 mw-qty-field">
                               <input min=1 type="number" class="form-control input-sm" name="qty" value="<?php print $item['qty'] ?>"  oninput="check_qty(this)" onchange=" mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                           </div>


                           <div class="col checkout-v2-remove-icon text-center">
                               <a data-bs-toggle="tooltip" title="<?php _e("Remove"); ?>" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');">

                                   <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                               </a>
                           </div>
                       </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h5><?php _e("Your cart is empty."); ?></h5>
        <?php endif; ?>
    </div>

    <?php if (is_array($data) and $data) : ?>
        <div class="checkout-modal-amount-holder row mt-4">

            <div class="col-sm-6 checkout-modal-promocode-holder">

                <?php if (get_option('enable_coupons', 'shop') == 1): ?>
                    <?php
                    $discountData = app()->cart_manager->totals('discount');
                    ?>
                    <module type="shop/coupons" template="modal" class="no-settings" />
                <?php endif; ?>

                <module type="shop/shipping" template="quick_setup" class="no-settings" />

            </div>
            <div class="col-sm-6 checkout-modal-total-holder my-3">
                <module type="shop/cart" template="totals" class="no-settings" />
            </div>

            <div class="w-100 mt-md-3 justify-content-center text-start text-left">
                <a href="#" class="mw-checkout-modal-buttons btn btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"><?php _e('Continue shopping'); ?></a>

                <a href="<?php echo route('checkout.contact_information'); ?>" class="mw-checkout-modal-buttons btn btn-primary float-end ms-2"><?php _e('Proceed to Checkout'); ?></a>
            </div>
        </div>
    <?php endif; ?>
</div>
