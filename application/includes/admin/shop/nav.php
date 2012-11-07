
<? if(is_admin() == false) {error('Must be admin');}  ?>
<ul id="mw_tabs_shop">
  <li <?php if($active_action == false): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop" class="mw-ui-btn">Products</a></li>
  <li <?php if($active_action == 'orders'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:orders" class="mw-ui-btn">Orders</a></li>
  <li <?php if($active_action == 'clients'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:clients" class="mw-ui-btn">Clients</a></li>
  <li <?php if($active_action == 'shipping'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:shipping" class="mw-ui-btn">Shipping</a></li>
  <li <?php if($active_action == 'promo_codes'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:promo_codes" class="mw-ui-btn">Promo codes</a></li>
  <li <?php if($active_action == 'options'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:options" class="mw-ui-btn">Options</a></li>
</ul>
