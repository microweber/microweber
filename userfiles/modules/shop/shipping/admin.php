
<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">

$(document).ready(function(){
    mw.options.form('.mw-set-shipping-options-swticher');
});




</script>
<?php
$here = dirname(__FILE__).DS.'gateways'.DS;
 $shipping_modules = modules_list("cache_group=modules/global&dir_name={$here}");

//$shipping_modules = get_modules_from_db('debug=1&ui=any&module=shop\shipping\gateways\*');
 // d($shipping_modules);

// d($shipping_modules);
?>
<div class="mw-set-shipping-options mw-admin-wrap">
  <div class="mw-o-box" style="background: #F8F8F8;">

    <?php if(isarr($shipping_modules )): ?>
    <?php foreach($shipping_modules  as $shipping_module): ?>
 <?php if(is_module_installed( $shipping_module['module'] )): ?>

    <div class="mw-o-box-header mw-set-shipping-options-swticher">
        <span class="ico itruck"></span><span><?php print $shipping_module['name'] ?></span>


        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher mw-switcher-green unselectable right <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?>mw-switcher-off<?php else: ?>mw-switcher-on<?php endif; ?>">
          <span class="mw-switch-handle"></span>
          <label>Yes<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="y" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <?php endif; ?> /></label>
          <label>No<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="n" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <?php endif; ?> /></label>
      </div>
      <label class="mw-ui-label right" style="margin-right: 12px;">Enabled: </label>

  </div>




    <div style="padding: 15px;">
      <div class="mw-set-shipping-gw-options" >
        <module type="<?php print $shipping_module['module'] ?>" view="admin" />
      </div>
    </div>
<?php endif; ?>
    <?php endforeach ; ?>
    <?php endif; ?>


    </div>
</div>
