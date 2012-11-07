
<? if(is_admin() == false) {error('Must be admin');}  ?>
<ul id="mw_tabs_shop">
  <li <?php if($active_action == false): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop">Products</a></li>
  <li <?php if($active_action == 'orders'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:orders">Orders</a></li>
  <li <?php if($active_action == 'clients'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:clients">Clients</a></li>
  <li <?php if($active_action == 'shipping'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:shipping">Shipping</a></li>
  <li <?php if($active_action == 'promo_codes'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:promo_codes">Promo codes</a></li>
  <li <?php if($active_action == 'options'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop/action:options">Options</a></li>
</ul>
