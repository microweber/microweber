<?php

/*

type: layout

name: Table

description: Table

*/
?>

<div class="<?php print $config['module_class'] ?>" id="<?php print $rand; ?>">
  <h3><?php _e("Continue Shopping or Complete Order"); ?></h3>
  <table cellspacing="0" cellpadding="0" class="table table-bordered table-striped mw-cart-table mw-cart-table-medium checkout-total-table">
    <tbody>
      <tr>
        <td colspan="3"></td>
        <td style="width: 260px;" colspan="2" class="cell-shipping-country"><label><?php _e("Shipping to"); ?>:</label>
          <select name="country" class="shipping-country-select">
            <option value=""><?php _e("Choose country"); ?></option>
            <?php foreach($data  as $item): ?>
            <option value="<?php print $item['shiping_country'] ?>"  <?php if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shiping_country'] ?></option>
            <?php endforeach ; ?>
          </select></td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td style="width: 260px;" colspan="2" class="cell-shipping-price"><label><?php _e("Shipping price"); ?>:</label>
          <?php print currency_format(session_get('shiping_cost')); ?></td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td style="width: 260px;" colspan="2" class="cell-shipping-total"><label><?php _e("Total Price"); ?>:</label>
          <?php print currency_format($total + intval(session_get('shiping_cost'))); ?></td>
      </tr>
    </tbody>
  </table>
</div>
