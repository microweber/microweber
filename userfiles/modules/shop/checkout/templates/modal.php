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

   <div class="clear"></div>

   <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post">
       <div class="modal-content">
           <div class="checkout-modal-header">
<!--               <label class="control-label font-weight-bold">--><?php //_e("Your cart"); ?><!--</label>-->
               <?php if(!isset($params['no-close-btn'])) { ?>
                   <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"><span
                               aria-hidden="true">&times;</span></button>

               <?php } ?>

           </div>
           <div class="modal-body">
               <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
               <?php if ($cart_show_enanbled != 'n'): ?>
                   <module type="shop/cart" template="modal" class="no-settings" data-checkout-link-enabled="n" id="cart_checkout_<?php print $params['id'] ?>"/>
               <?php endif; ?>
           </div>
   </form>

</div>
<script>
    $(document).ready(function () {
        mw.cart.modal.init('#checkout_modal_<?php print $params['id'] ?>')
    });
</script>
