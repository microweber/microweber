<div>
  <h2><?php _e('Other shop settings'); ?></h2>
  <hr>
  <h4>
    <?php _e("Users must agree to Terms and Conditions"); ?>
  </h4>
  <label class="mw-ui-check" style="margin-right: 15px;">
    <input name="shop_require_terms" class="mw_option_field"     data-option-group="website"  value="0"  type="radio"  <?php if(get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span>
    <?php _e("No"); ?>
    </span></label>
  <label class="mw-ui-check">
    <input name="shop_require_terms" class="mw_option_field"    data-option-group="website"  value="1"  type="radio"  <?php if(get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
  <hr>
  <h4>
    <?php _e("Purchasing requires registration"); ?>
  </h4>
  <label class="mw-ui-check" style="margin-right: 15px;">
    <input name="shop_require_registration" class="mw_option_field"     data-option-group="website"  value="0"  type="radio"  <?php if(get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span>
    <?php _e("No"); ?>
    </span></label>
  <label class="mw-ui-check">
    <input name="shop_require_registration" class="mw_option_field"    data-option-group="website"  value="1"  type="radio"  <?php if(get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
  <hr>
  <h4>
    <?php _e("Disable online shop"); ?>
  </h4>
  <label class="mw-ui-check" style="margin-right: 15px;">
    <input name="shop_disabled" class="mw_option_field"     data-option-group="website"  value="n"  type="radio"  <?php if(get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
    <span></span><span>
    <?php _e("No"); ?>
    </span> </label>
  <label class="mw-ui-check">
    <input name="shop_disabled" class="mw_option_field"    data-option-group="website"  value="y"  type="radio"  <?php if(get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
    <span></span> <span>
    <?php _e("Yes"); ?>
    </span> </label>
  <br />
  <hr>
  <a  class="mw-ui-btn mw-ui-btn-small"
            href="javascript:$('.mw_adm_shop_advanced_settings').toggle();void(0);"><?php _e('Advanced'); ?><span
                class="mw-ui-arr mw-ui-arr-down" style="opacity:0.3"></span> </a>
  <div class="mw_adm_shop_advanced_settings mw-ui-box mw-ui-box-content" style="display:none;margin-top: 12px;">
    <h2>
      <?php _e("Checkout URL"); ?>
    </h2>
    <?php ?>
    <?php $checkout_url = get_option('checkout_url', 'shop');  ?>
    <input name="checkout_url"  class="mw_option_field mw-ui-field"   type="text" option-group="shop"   value="<?php print get_option('checkout_url','shop'); ?>" placeholder="Use default"  />
    <h2>
      <?php _e("Custom order id"); ?>
    </h2>
    <?php ?>
    <input name="custom_order_id"  class="mw_option_field mw-ui-field"   type="text" option-group="shop"   value="<?php print get_option('custom_order_id','shop'); ?>" placeholder="ORD-{id}"  />
  </div>
  
 
  
  <hr>
  <module type="shop/shipping/set_units" id="mw_set_shipping_units" />
  
  
</div>
