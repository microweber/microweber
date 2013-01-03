<?  require_once($config['path_to_module'].'shipping_api.php');  ?>



<? 
$shipping_api = new shipping_api();

 $shipping_options =  $shipping_api->get_active(); 
 
 
  ?>
<? if(isarr($shipping_options)) :?>
<script  type="text/javascript">
$(document).ready(function(){
 
 
 $('.mw-shipping-gateway-<? print $params['id']; ?>').unbind('change');
  $('.mw-shipping-gateway-<? print $params['id']; ?>').bind('change',function() {

	 $v = $(this).val();

	 
	 mw.$('.mw-shipping-gateway-selected-<? print $params['id']; ?> .module:first').attr('data-selected-gw',$v);




	 mw.load_module('shop/shipping/gateways/'+$v,'#mw-shipping-gateway-selected-<? print $params['id']; ?>');




		 
	 });
 
 
});
</script>
 
<h3>Shipping information</h3>
<select name="shipping_gw" class="mw-shipping-gateway mw-shipping-gateway-<? print $params['id']; ?> <? if(count($shipping_options) == 1): ?> semi_hidden <? endif; ?>" >
  <? foreach ($shipping_options as $item) : ?>
  <option value="<? print  $item['module_base']; ?>"><? print  $item['name']; ?></option>
  <? endforeach; ?>
</select>
<div id="mw-shipping-gateway-selected-<? print $params['id']; ?>">
  <module type="shop/shipping/gateways/<? print $shipping_options[0]['module_base'] ?>"  />
</div>
<? endif;?>
