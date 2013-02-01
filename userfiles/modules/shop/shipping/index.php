<?  // require_once($config['path_to_module'].'shipping_api.php');  ?>



<? 
  
 $shipping_options =  api('shop/shipping/shipping_api/get_active');

 
  ?>
<? if(isarr($shipping_options)) :?>
<script  type="text/javascript">
  _gateway = function(el){
  	 var val = $(el).val();
     mwd.querySelector('.mw-shipping-gateway-selected-<? print $params['id']; ?> .module').setAttribute('data-selected-gw',val);
  	 mw.load_module(val,'#mw-shipping-gateway-selected-<? print $params['id']; ?>');
  }
</script>


<div class="span4">
<div class="well">

<div style="display: none">
<select onchange="_gateway(this);" name="shipping_gw" class="mw-ui-simple-dropdown mw-shipping-gateway mw-shipping-gateway-<? print $params['id']; ?> <? if(count($shipping_options) == 1): ?> semi_hidden <? endif; ?>" >
  <? foreach ($shipping_options as $item) : ?>
  <option value="<? print  $item['module_base']; ?>"><? print  $item['name']; ?></option>
  <? endforeach; ?>
</select>
</div>

 <h2 style="margin-top:0 ">Shipping Information</h2>
  <hr />

<div id="mw-shipping-gateway-selected-<? print $params['id']; ?>">
  <module type="<? print $shipping_options[0]['module_base'] ?>"  />
</div>
<? endif;?>

</div>
</div>
