
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

//$shipping_modules = get_modules_from_db('debug=1&ui=any&module=shop\shipping\gateways\*');
 // d($shipping_modules);
 
// d($shipping_modules);
?>
<div class="mw-set-shipping-options mw-admin-wrap">
  <div class="mw-o-box" style="background: #F8F8F8;">

    <? if(isarr($shipping_modules )): ?>
    <? foreach($shipping_modules  as $shipping_module): ?>
 <? if(is_module_installed( $shipping_module['module'] )): ?>

    <div class="mw-o-box-header">
        <span class="ico itruck"></span><span><? print $shipping_module['name'] ?></span>


        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable right <? if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?>mw-switcher-off<?php endif; ?>">
          <span class="mw-switch-handle"></span>
          <label>Enabled<input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="y" type="radio" <? if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <? endif; ?> /></label>
          <label>Disabled<input name="shipping_gw_<? print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="n" type="radio" <? if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <? endif; ?> /></label>
      </div>
      <label class="mw-ui-label right" style="margin-right: 12px;">Enabled: </label>

  </div>




    <div style="padding: 15px;">
      <div class="mw-set-shipping-gw-options" >
        <module type="<? print $shipping_module['module'] ?>" view="admin" />
      </div>
    </div>
<? endif; ?>
    <? endforeach ; ?>
    <? endif; ?>


    </div>
</div>
