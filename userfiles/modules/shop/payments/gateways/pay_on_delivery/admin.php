<?php only_admin_access(); ?>

<ul class="mw-ui-inline-list">
  <li>
    <label class="mw-ui-label">
      <?php _e("Show message"); ?>?
      :</label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="pay_on_delivery_show_msg" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('pay_on_delivery_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
      <span></span><span>
      <?php _e("Yes"); ?>
      </span></label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="pay_on_delivery_show_msg" class="mw_option_field"    data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('pay_on_delivery_show_msg', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
      <span></span><span>
      <?php _e("No"); ?>
      </span></label>
  </li>
</ul>

            <label class="mw-ui-label"><?php _e('Message'); ?></label>
            <textarea  name="pay_on_delivery_msg" class="mw-ui-field mw_option_field" data-option-group="payments"><?php print get_option('pay_on_delivery_msg', 'payments') ?></textarea>
 