<div id="subheader">
  <ul>
    <li><a class="<?php if( $className == 'orders' and $functionName == 'index')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/orders/index')  ?>">Manage all orders</a></li>
    <li><a class="<?php if( $className == 'orders' and $functionName == 'promo_codes')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/orders/promo_codes')  ?>">Promo codes</a></li>
    <li><a class="<?php if( $className == 'orders' and $functionName == 'shipping_cost')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/orders/shipping_cost')  ?>">Shipping cost</a></li>
     <li><a class="<?php if( $className == 'orders' and $functionName == 'currencies')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/orders/currencies')  ?>">Currency rates</a></li>
    
    
  </ul>
</div>
<script type="text/javascript">
  var orders_edit_icon = '<?php print_the_static_files_url() ; ?>icons/cart_edit.png'
  var orders_save_icon = '<?php print_the_static_files_url() ; ?>icons/disk_black.png'
  var orders_delete_icon = '<?php print_the_static_files_url() ; ?>icons/cart_delete.png'
  
  var promo_code_edit_icon = '<?php print_the_static_files_url() ; ?>icons/star__pencil.png'
  var promo_code_save_icon = '<?php print_the_static_files_url() ; ?>icons/disk_black.png'
  var promo_code_delete_icon = '<?php print_the_static_files_url() ; ?>icons/star__minus.png'
  var promo_code_add_icon = '<?php print_the_static_files_url() ; ?>icons/star__plus.png'
  
  
  
  var shipping_costs_edit_icon = '<?php print_the_static_files_url() ; ?>icons/pencil_small.png'
  var shipping_costs_save_icon = '<?php print_the_static_files_url() ; ?>icons/disk_black.png'
  var shipping_costs_delete_icon = '<?php print_the_static_files_url() ; ?>icons/lorry_delete.png'
  var shipping_costs_add_icon = '<?php print_the_static_files_url() ; ?>icons/lorry_add.png'
  
  
  var currency_code_edit_icon = '<?php print_the_static_files_url() ; ?>icons/pencil_small.png'
  var currency_code_save_icon = '<?php print_the_static_files_url() ; ?>icons/disk_black.png'
  var currency_code_delete_icon = '<?php print_the_static_files_url() ; ?>icons/money_delete.png'
  var currency_code_add_icon = '<?php print_the_static_files_url() ; ?>icons/money_add.png'
  
  
  
  
  
</script>
