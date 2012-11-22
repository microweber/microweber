<?  require_once($config['path_to_module'].'shipping_api.php');  ?>



<? 
 $shipping_api = new shipping_api();
 $shipping_options =  $shipping_api->get_active();


  ?>
<? if(isarr($shipping_options)) :?>
<script  type="text/javascript">
  _gateway = function(el){
  	 var val = $(el).val();
     mwd.querySelector('.mw-shipping-gateway-selected-<? print $params['id']; ?> .module').setAttribute('data-selected-gw',val);
  	 mw.load_module('shop/shipping/gateways/'+val,'#mw-shipping-gateway-selected-<? print $params['id']; ?>');
  }
</script>


<select onchange="_gateway(this);" name="shipping_gw" class="mw-ui-simple-dropdown mw-shipping-gateway mw-shipping-gateway-<? print $params['id']; ?> <? if(count($shipping_options) == 1): ?> semi_hidden <? endif; ?>" >
  <? foreach ($shipping_options as $item) : ?>
  <option value="<? print  $item['module_base']; ?>"><? print  $item['name']; ?></option>
  <? endforeach; ?>
</select>
<div id="mw-shipping-gateway-selected-<? print $params['id']; ?>">
  <module type="shop/shipping/gateways/<? print $shipping_options[0]['module_base'] ?>"  />
</div>
<? endif;?>
