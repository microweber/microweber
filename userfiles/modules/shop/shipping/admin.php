
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
<div class="mw-set-shipping-options mw-admin-wrap">
  <div class="mw-o-box" style="background: #F8F8F8;">

    <? if(isarr($shipping_modules )): ?>
    <? foreach($shipping_modules  as $shipping_module): ?>


    <div class="mw-o-box-header">
        <span class="ico itruck"></span><span><? print $shipping_module['name'] ?></span>


        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable right">
          <span class="mw-switch-handle"></span>
          <label>Enabled<input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="y" type="radio" <? if(option_get('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <? endif; ?> /></label>
          <label>Disabled<input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="n" type="radio" <? if(option_get('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <? endif; ?> /></label>
      </div>
      <label class="mw-ui-label right" style="margin-right: 12px;">Enabled: </label>

  </div>




    <div style="padding: 15px;">
      <div class="mw-set-shipping-gw-options" >
        <module type="shop/shipping/gateways/<? print $shipping_module['module'] ?>" view="admin" />
      </div>
    </div>

    <? endforeach ; ?>
    <? endif; ?>


    </div>
</div>
