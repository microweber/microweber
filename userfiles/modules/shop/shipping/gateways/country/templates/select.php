<?php

/*

type: layout

name: Select

description: Select country

*/
?>
<div class="<?php print $config['module_class'] ?>" id="<?php print $rand; ?>">
  <select name="country" class="form-control shipping-country-select">
   <option value=""><?php _e("Choose country"); ?></option>
    <?php foreach($data  as $item): ?>
    <option value="<?php print $item['shipping_country'] ?>"  <?php if(isset($_SESSION['shipping_country']) and $_SESSION['shipping_country'] == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
    <?php endforeach ; ?>
  </select>
</div>