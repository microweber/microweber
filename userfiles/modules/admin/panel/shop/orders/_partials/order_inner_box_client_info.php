
<table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic mw-ui-table-order-info">
  <col width="50%"/>
  <tr>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title">
        <?php _e("Customer Name"); ?>
      </h3></td>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title-black"><?php print $ord['first_name'] . ' ' . $ord['last_name']; ?></h3></td>
  </tr>
  <tr>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title">
        <?php _e("Email"); ?>
      </h3></td>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title-black"><a href="mailto:<?php print $ord['email'] ?>"><?php print $ord['email'] ?></a></h3></td>
  </tr>
  <tr>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title">
        <?php _e("Phone Number"); ?>
      </h3></td>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title-black"><?php print $ord['phone']; ?></h3></td>
  </tr>
  <tr>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title">
        <?php _e("User IP"); ?>
      </h3></td>
    <td><h3 class="mw-admin-main-section-inner-panel-sub-title-black"><?php print $ord['user_ip']; ?>
        <?php if (function_exists('ip2country')): ?>
        <?php print ip2country($ord['user_ip']); ?>
        <?php endif; ?>
      </h3></td>
  </tr>
</table>
