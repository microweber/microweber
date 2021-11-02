<?php

/*

type: layout

name: Show cart totals

description:  Show cart totals

*/
?>

<?php $cart_totals = mw()->cart_manager->totals(); ?>
   <div class="pl-2 m-xl-0 m-4">
       <?php if ($cart_totals): ?>
               <div class="row text-end text-right justify-content-end">
                   <label class="control-label font-weight-bold float-end text-end text-right"><?php _lang("Total amount" ); ?></label>
                   <?php foreach ($cart_totals as $cart_total_key => $cart_total): ?>
                            <p class="mw-paragraph-totals col-12 mb-1">
                                <?php if ($cart_total_key != 'total' and $cart_total and is_array($cart_total) and !empty($cart_total) and isset($cart_total['value']) and $cart_total['value']): ?>
                           </p>
                            <p class="mw-paragraph-totals col-12 mb-1">
                                <?php _lang($cart_total['label']); ?>: <?php print currency_format($cart_total['value']); ?>
                            </p>
                       <?php endif; ?>
                   <?php endforeach; ?>
                   <br>
               </div>

       <?php endif; ?>

       <?php if ($cart_totals): ?>
           <div class="d-flex float-end">
               <?php $print_total = cart_total(); ?>
               <h5 class="col-xs-6 checkout-modal-total-label mr-1 font-weight-bold"><?php _lang("Total"); ?>:</h5>
               <h5 class="col-xs-6 checkout-modal-total-price font-weight-bold pl-0">
                   <?php print currency_format($print_total); ?>
               </h5>
           </div>
       <?php endif; ?>
   </div>



