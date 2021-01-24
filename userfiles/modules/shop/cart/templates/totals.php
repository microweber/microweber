<?php

/*

type: layout

name: Show cart totals

description:  Show cart totals

*/
?>

 <?php $cart_totals = mw()->cart_manager->totals(); ?>
<?php if ($cart_totals): ?>

    <?php foreach ($cart_totals as $cart_total_key => $cart_total): ?>
        <?php if ($cart_total_key != 'total' and $cart_total and is_array($cart_total) and !empty($cart_total) and isset($cart_total['value']) and $cart_total['value']): ?>
            <div class="row">
                <div class="col-xs-6 checkout-modal-total-label"><?php _e($cart_total['label']); ?>:</div>
                <div class="col-xs-6 right checkout-modal-total-price">
                    <?php print currency_format($cart_total['value']); ?>
                </div>
            </div>
        <?php endif; ?>
     <?php endforeach; ?>
    <br>
<?php endif; ?>

 <?php if ($cart_totals): ?>
    <div class="row">
        <?php $print_total = cart_total(); ?>
        <div class="col-xs-6 checkout-modal-total-label"><?php _e("Total"); ?>:</div>
        <div class="col-xs-6 right checkout-modal-total-price">
            <?php print currency_format($print_total); ?>
        </div>
    </div>

<?php endif; ?>