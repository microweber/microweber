<? 
  
 $shipping_options =  api('shop/shipping/shipping_api/get_active');

 
  ?>
<module type="<? print $shipping_options[0]['module_base'] ?>" template="select"  />
