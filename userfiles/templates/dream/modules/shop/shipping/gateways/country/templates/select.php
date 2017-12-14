<?php

/*

type: layout

name: Select

description: Select country

*/
?>
<?php  $selected_country = mw()->user_manager->session_get('shipping_country'); ?>

<div class="<?php print $config['module_class'] ?>" id="<?php print $rand; ?>">
  <select name="country" class="form-control shipping-country-select">
   <option value=""><?php _e("Choose country"); ?></option>
    <?php foreach($data  as $item): ?>
    <option value="<?php print $item['shipping_country'] ?>"  <?php if(isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
    <?php endforeach ; ?>
  </select>
</div>