<div class="mw-admin-main-section-inline-bar-controlls">
  <ul>
    <li> <span>
      <?php _e("Payment Method"); ?>:</span>
      <?php $gw = str_replace('shop/payments/gateways/','',$ord['payment_gw']); ?>
      <strong><?php print $gw; ?></strong></li>
    <li> <span>
      <?php _e("Is Paid"); ?>
      :</span>
      <select name="is_paid" class="mw-ui-field mw-ui-field-medium mw-order-is-paid-change">
        <option value="1" <?php if (isset($ord['is_paid']) and $ord['is_paid'] == 1): ?> selected="selected" <?php endif; ?>>
        <?php _e("Yes"); ?>
        </option>
        <option value="0" <?php if (isset($ord['is_paid']) and $ord['is_paid'] != 1): ?> selected="selected" <?php endif; ?>>
        <?php _e("No"); ?>
        </option>
      </select>
    </li>
    <?php if (isset($ord['transaction_id']) and $ord['transaction_id'] != ''): ?>
    <li>
      <?php _e("Transaction ID"); ?>
      : <?php print $ord['transaction_id']; ?></li>
    <?php endif; ?>
    <?php if (isset($ord['payment_amount']) and $ord['payment_amount'] != ''): ?>
    <li> <span>
      <?php _e("Payment Amount"); ?>:</span> <?php print $ord['payment_amount']; ?> <?php print $ord['payment_currency']; ?>
      <?php
		 
				 
				 
				  ?>
      </li>
    <?php endif; ?>
    <?php if (isset($ord['payer_id']) and $ord['payer_id'] != ''): ?>
    <li>
     <span> <?php _e("Payer ID"); ?>
      : </span><?php print $ord['payer_id']; ?></li>
    <?php endif; ?>
    <?php if (isset($ord['payment_status']) and $ord['payment_status'] != ''): ?>
    <li><span>
      <?php _e("Payment Status"); ?>
      :</span> <?php print $ord['payment_status']; ?></li>
    <?php endif; ?>
  </ul>
</div>
