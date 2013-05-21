<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="<?php print $config['module_class'] ?>">
  <div id="<?php print $rand; ?>">
    <label>
      <?php _e("Choose country:"); ?>
    </label>
    <select name="country" class="field-full">
      <option value=""><?php _e("Choose country"); ?></option>
      <?php foreach($data  as $item): ?>
      <option value="<?php print $item['shiping_country'] ?>"  <?php if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shiping_country'] ?></option>
      <?php endforeach ; ?>
    </select>
  </div>
  <label>
    <?php _e("City"); ?>
  </label>
  <input name="city" class="field-full"  type="text" value="" />
  <label>
    <?php _e("State"); ?>
  </label>
  <input name="state" class="field-full"  type="text" value="" />
  <label>
    <?php _e("Zip/Postal Code"); ?>
  </label>
  <input name="zip" class="field-full" type="text" value="" />
  <label>
    <?php _e("Address"); ?>
  </label>
  <input name="address" class="field-full" type="text" value="" />
</div>
