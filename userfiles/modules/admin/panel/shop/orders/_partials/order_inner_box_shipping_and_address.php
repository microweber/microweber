<table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic mw-ui-table-order-info" style="margin-top: 0;">
  <col width="50%"/>
  <tr>
    <td valign="top"><?php
                $map_click_str = false;
                $map_click = array();?>
      <h3 class="mw-admin-main-section-inner-panel-sub-title">Shipping address</h3>
      <div class="mw-admin-small-spacer"></div>
      <ul class="order-table-info-list">
        <?php if (isset($ord['country']) and $ord['country'] != ''): ?>
        <li><?php print $ord['country'] ?></li>
        <?php $map_click[] = $ord['country']; ?>
        <?php endif; ?>
        <?php if (isset($ord['city']) and $ord['city'] != ''): ?>
        <li><?php print $ord['city'] ?></li>
        <?php $map_click[] = $ord['city']; ?>
        <?php endif; ?>
        <?php if (isset($ord['state']) and $ord['state'] != ''): ?>
        <li><?php print $ord['state'] ?></li>
        <?php $map_click[] = $ord['city']; ?>
        <?php endif; ?>
        <?php if (isset($ord['zip']) and $ord['zip'] != ''): ?>
        <li><?php print $ord['zip'] ?></li>
        <?php endif; ?>
        <?php if (isset($ord['address']) and $ord['address'] != ''): ?>
        <li><?php print $ord['address'] ?></li>
        <?php $map_click[] = $ord['address']; ?>
        <?php endif; ?>
        <?php if (isset($ord['address2']) and $ord['address2'] != ''): ?>
        <li><?php print $ord['address2'] ?></li>
        <?php endif; ?>
        <?php if (isset($ord['phone']) and $ord['phone'] != ''): ?>
        <li>
          <?php _e("Phone"); ?>
          <?php print $ord['phone'] ?> </li>
        <?php endif; ?>
      </ul>
      <?php if (isset($ord['custom_fields']) and $ord['custom_fields'] != ''): ?>
      <table class="mw-ui-table" cellspacing="0" cellpadding="0">
        <col width="50%"/>
        <tr>
          <td valign="top"><span class="order-detail-title">
            <?php _e("Additional Details"); ?>
            </span> <?php print $ord['custom_fields'] ?></td>
        </tr>
      </table>
      <?php endif; ?></td>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title mw-text-upper">Shipping address</h3>
      <?php
                if (!empty($map_click)) {
                    $map_click = array_unique($map_click);
                    $map_click_str = implode(', ', $map_click);
                }

                ?>
      <a target="_blank"
                   href="https://maps.google.com/maps?q=<?php print urlencode($map_click_str) ?>&safe=off"> <img class="map-shipping-address"
                        src="http://maps.googleapis.com/maps/api/staticmap?size=200x200&zoom=17&markers=icon:http://microweber.com/order.png|<?php print urlencode($map_click_str) ?>&sensor=true&center=<?php print urlencode($map_click_str) ?>" style="max-width:100%; float:left;"/> </a></td>
  </tr>
</table>
<?php if($ord['payment_country'] or $ord['payment_address'] or $ord['payment_city'] or $ord['payment_state']) { ?>
<table  cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic mw-ui-table-order-info" style="margin-top:0">
  <col width="50%"/>
  <tr>
    <td valign="top"><h3 class="mw-admin-main-section-inner-panel-sub-title">Billing address</h3>
      <ul class="order-table-info-list">
        <li><?php print $ord['payment_name'] ?></li>
        <li><?php print $ord['payment_country'] ?></li>
        <li><?php print $ord['payment_email'] ?></li>
        <li><?php print $ord['payment_city'] ?></li>
        <li><?php print $ord['payment_state'] ?></li>
        <li><?php print $ord['payment_zip'] ?></li>
        <li><?php print $ord['payment_address'] ?></li>
      </ul></td>
    <td valign="top"><a target="_blank"
                       href="https://maps.google.com/maps?q=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&safe=off"> <img style="max-width:100%; float:left;" class="map-shipping-address"
                    src="https://maps.googleapis.com/maps/api/staticmap?size=200x200&zoom=17&markers=icon:https://microweber.com/user.png|<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&sensor=true&center=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>"/> </a></td>
  </tr>
</table>
<?php }  ?>
