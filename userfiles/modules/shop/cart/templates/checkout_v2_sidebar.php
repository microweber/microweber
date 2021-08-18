<?php

/*

type: layout

name: Checkout V2

description: Checkout V2

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
                       <div class="form-row checkout-modal-product-list-item align-items-center py-4">
                           <div class="col-xl-2 col-4">
                               <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                   <?php $p = $item['item_image']; ?>
                               <?php else: ?>
                                   <?php $p = get_picture($item['rel_id']); ?>
                               <?php endif; ?>
                               <?php if ($p != false): ?>
                                   <img style="max-width:70px; max-height:70px;" src="<?php print thumbnail($p, 70, 70, true); ?>" alt=""/>
                               <?php endif; ?>
                           </div>

                           <div class="col-xl-5 col-8">
                               <h6 class="mb-1"><?php _e($item['title']) ?></h6>
                               <small class="text-muted mw-order-custom-fields">
                                   <?php if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
                                       <?php print $item['custom_fields'] ?>
                                   <?php endif ?>
                               </small>
                           </div>

                           <div class="col-xl-2 col-3">
                               <h6><?php print currency_format($item['price']); ?></h6>
                           </div>
                           <div class="col-xl-2 col-3 mw-qty-field">
                               <input min=1 type="number" class="form-control input-sm" name="qty" value="<?php print $item['qty'] ?>"  oninput="check_qty(this)" onchange=" mw.cart.qty('<?php print $item['id'] ?>', this.value)"/>
                           </div>

                           <div class="col-xl-1 col-4 checkout-v2-remove-icon d-flex justify-content-end">
                               <a data-toggle="tooltip" title="<?php _e("Remove"); ?>" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="checkout-v2-remove-icon mdi mdi-delete-outline text-secondary mr-2"></i></a>
                           </div>
                       </div>
                   <?php endforeach; ?>
               <?php else: ?>
                   <h5><?php _e("Your cart is empty."); ?></h5>
               <?php endif; ?>
           </div>

       </div>


       <div class="mt-3">
           <?php if (get_option('enable_coupons', 'shop') == 1): ?>
               <?php
               $discountData = app()->cart_manager->totals('discount');
               ?>
               <module type="shop/coupons" template="modal" class="no-settings" />
               <hr />
           <?php endif; ?>
       </div>


        <module type="shop/cart" template="totals" class="no-settings" />
