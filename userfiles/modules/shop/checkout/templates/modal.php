<?php

/*

type: layout

name: Checkout

description: Checkout

*/


?>
<script type="text/javascript">
    mw.require("<?php print modules_url(); ?>shop/checkout/styles.css", true);
</script>

<div class="checkout-modal" id="checkout_modal_<?php print $params['id'] ?>">

        <?php if ($requires_registration and is_logged() == false): ?>
            <script>
                $(document).ready(function () {

                    if (!!$.fn.selectpicker) {
                        $('#loginModal').modal();
                    }
                })
            </script>
        <?php else: ?>

       <div class="clear"></div>
       <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post">
           <div class="modal-content">
               <div class="modal-header">
                   <?php if(!isset($params['no-close-btn'])) { ?>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                   aria-hidden="true">&times;</span></button>
                   <?php } ?>

                   <?php _e('Your shopping cart'); ?>
               </div>

               <div class="modal-body">
                   <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                   <?php if ($cart_show_enanbled != 'n'): ?>
                       <module type="shop/cart" template="modal" data-checkout-link-enabled="n" id="cart_checkout_<?php print $params['id'] ?>"/>
                   <?php endif; ?>
               </div>
       </form>

        <?php endif; ?>

</div>
<script>
    $(document).ready(function () {
        mw.cart.modal.init('#checkout_modal_<?php print $params['id'] ?>')
    });
</script>
