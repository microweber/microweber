<?php  // require_once($config['path_to_module'].'shipping_api.php');  ?>
<?php 
  
 $shipping_options =  api('shop/shipping/shipping_api/get_active');

 
  ?>
<?php if(isarr($shipping_options)) :?>
<script  type="text/javascript">
  _gateway = function(el){
  	 var val = $(el).val();
     mwd.querySelector('.mw-shipping-gateway-selected-<?php print $params['id']; ?> .module').setAttribute('data-selected-gw',val);
  	 mw.load_module(val,'#mw-shipping-gateway-selected-<?php print $params['id']; ?>');
  }
</script>

  <div class="well">
    <div style="display: none">
      <select onchange="_gateway(this);" name="shipping_gw" class="field-full mw-shipping-gateway mw-shipping-gateway-<?php print $params['id']; ?> <?php if(count($shipping_options) == 1): ?> semi_hidden <?php endif; ?>" >
        <?php foreach ($shipping_options as $item) : ?>
        <option value="<?php print  $item['module_base']; ?>"><?php print  $item['name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <h2 style="margin-top:0 ">Shipping Information</h2>
    <hr />
    <div id="mw-shipping-gateway-selected-<?php print $params['id']; ?>">
      <module type="<?php print $shipping_options[0]['module_base'] ?>"  />
    </div>
    <?php endif;?>
  </div>
