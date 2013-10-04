<?php 
  
 $shipping_options =  api('shop/shipping/shipping_api/get_active');

 
  ?>
 
<module type="<?php print $shipping_options[0]['module_base'] ?>" template="select"  />
