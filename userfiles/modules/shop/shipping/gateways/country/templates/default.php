<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="<?php print $config['module_class'] ?>">
  <?php if(!$disable_default_shipping_fields) :?>
      <div id="<?php print $rand; ?>">
        <label>
          <?php _e("Choose country:"); ?>
        </label>

        <?php  $selected_country = mw()->user_manager->session_get('shipping_country'); ?>
        <select name="country" class="field-full form-control">
         <option value=""><?php _e("Choose country"); ?></option>
          <?php foreach($data  as $item): ?>
          <option value="<?php print $item['shipping_country'] ?>"  <?php if(isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
          <?php endforeach ; ?>
        </select>
      </div>
      <module type="custom_fields" id="shipping-info-address-<?php print $params['id'] ?>" data-for="module"  default-fields="address" input-class="field-full form-control" data-skip-fields="country"   />
  <?php endif;?>

    <?php if($enable_custom_shipping_fields) :?>
        <module type="custom_fields" for-id="shipping"  for-id="shipping"  default-fields="message"   />
    <?php endif;?>
</div>

