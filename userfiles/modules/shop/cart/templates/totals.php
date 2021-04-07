<?php

/*

type: layout

name: Show cart totals

description:  Show cart totals

*/
?>

<?php $cart_totals = mw()->cart_manager->totals(); ?>
   <div class="justify-content-lg-end">
       <?php if ($cart_totals): ?>
           <table class="table table-responsive">
               <div class="form-row">
                   <h5><?php _lang("Total amount" ); ?></h5>

                   <?php foreach ($cart_totals as $cart_total_key => $cart_total): ?>
                       <?php if ($cart_total_key != 'total' and $cart_total and is_array($cart_total) and !empty($cart_total) and isset($cart_total['value']) and $cart_total['value']): ?>
                           <thead>
                           <tr>
                               <th class="pl-0"><?php _lang($cart_total['label']); ?>: <?php print currency_format($cart_total['value']); ?></th>
                           </tr>
                           </thead>
                       <?php endif; ?>
                   <?php endforeach; ?>
                   <br>
               </div>
           </table>
       <?php endif; ?>

       <?php if ($cart_totals): ?>
           <div class="form-row">
               <?php $print_total = cart_total(); ?>
               <h5 class="col-xs-6 checkout-modal-total-label mr-1 font-weight-bold"><?php _lang("Total"); ?>:</h5>
               <h5 class="col-xs-6 checkout-modal-total-price pl-0">
                   <?php print currency_format($print_total); ?>
               </h5>
           </div>
       <?php endif; ?>
   </div>



