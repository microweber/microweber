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
                <div class="row checkout-modal-product-list-item align-items-center pb-sm-4 pb-2 ps-lg-4">
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



                       <div class=" col-8 d-flex justify-content-end align-items-center flex-wrap">
                           <div class="col-sm col-12">
                               <h6 class="mb-sm-0"><?php print currency_format($item['price']); ?></h6>
                           </div>
                           <div class="col-6 mw-qty-field">
                               <div class="quantity-field">
                                   <button type="button" class="quantity-control decrement" data-itemid="<?php print $item['id'] ?>" onclick="decrementQuantity(this)">
                                       <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M200-440v-80h560v80H200Z"/></svg>
                                   </button>
                                   <input id="qty-<?php print $item['id'] ?>" value="<?php print $item['qty'] ?>" name="qty" type="text" class="quantity-input" oninput="check_qty(this)" onchange=" mw.cart.qty('<?php print $item['id'] ?>', this.value)">
                                   <button type="button" class="quantity-control increment" data-itemid="<?php print $item['id'] ?>" onclick="incrementQuantity(this)">
                                       <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                                   </button>
                               </div>
                           </div>

                           <div class="col checkout-v2-remove-icon text-center">
                               <a data-bs-toggle="tooltip" title="<?php _e("Remove"); ?>" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');">
                                   <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
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

<script>
    function updateQuantity(itemId, newQty) {
        // Use AJAX or other methods to update the quantity on the server
        // Here, we'll just update the input value for demonstration purposes
        const inputElement = document.getElementById('qty-' + itemId);
        inputElement.value = newQty;
        mw.cart.qty(itemId, newQty);
    }

    function incrementQuantity(button) {
        const itemId = button.getAttribute('data-itemid');
        const inputElement = document.getElementById('qty-' + itemId);
        const newQty = parseInt(inputElement.value) + 1;
        updateQuantity(itemId, newQty);
    }

    function decrementQuantity(button) {
        const itemId = button.getAttribute('data-itemid');
        const inputElement = document.getElementById('qty-' + itemId);
        const currentQty = parseInt(inputElement.value);
        if (currentQty > 1) {
            const newQty = currentQty - 1;
            updateQuantity(itemId, newQty);
        }
    }


</script>
