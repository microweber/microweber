<?php 
  
 $shipping_options =  mw('shop\shipping\shipping_api')->get_active();

 
  ?>
<?php if(isset($shipping_options[0])): ?> 
<module type="<?php print $shipping_options[0]['module_base'] ?>" template="cost"  />
<?php endif; ?>