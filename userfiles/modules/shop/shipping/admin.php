 shipping providers 
<script  type="text/javascript">
 mw.require('options.js');
 </script> 
<script  type="text/javascript">

$(document).ready(function(){
mw.options.form('.mw-set-shipping-options');
 
});
</script>
<?
$here = dirname(__FILE__).DS.'gateways'.DS;
$shipping_modules = modules_list("cache_group=modules/global&dir_name={$here}");
// d($shipping_modules);
?>
<div class="mw-set-shipping-options" >
  <? if(isarr($shipping_modules )): ?>
  <? foreach($shipping_modules  as $shipping_module): ?>
  <h2><? print $shipping_module['name'] ?></h2>
  Enabled:
  <label class="mw-ui-check">
    <input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field"    data-option-group="shipping"  value="y"  type="radio"  <? if(option_get('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>Yes</label>
  <label class="mw-ui-check">
    <input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field"     data-option-group="shipping"  value="n"  type="radio"  <? if(option_get('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <? endif; ?> >
    <span></span>No</label>
  <div class="mw-set-shipping-gw-options" >
  
    <module type="shop/shipping/gateways/<? print $shipping_module['module'] ?>" view="admin" />
  </div>
  <? endforeach ; ?>
  <? endif; ?>
</div>
