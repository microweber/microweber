<script>
    mw.quick_price_save_edit = function (el) {

     var price_field = $(el);
	 var data = {};
	 data.id = price_field.attr('data-custom-field-id');
	 data.value = price_field.val();
	 
 
	 
	 mw.custom_fields.edit(data, function(){

     mw.reload_module_parent('custom_fields/list');

	 mw.reload_module_parent('custom_fields');
	 mw.reload_module('custom_fields/list');


     });

    }
</script>
<?php if (isset($data) and !empty($data)): ?>



<?php

    $prices = array();
    foreach ($data as $item){
        if (isset($item['id']) and isset($item['type']) and $item['type'] == 'price'){
           $prices[] = $item;
        }
    }

 ?>



<?php

$hasmultiple = count($prices) > 1;

if($hasmultiple):
  $firstprice = $prices[0];

      $val = false;
      if ($firstprice['value'] != false) {
          $val = floatval($firstprice['value']);
      }

?>
<div class="mw-ui-dropdown" id="pricedropdown">


<span class="mw-ui-btn mw-ui-btn-big mw-dropdown-button">

<span><?php print mw()->shop_manager->currency_symbol(); ?></span> <?php print $val; ?>

</span>


<div class="mw-ui-dropdown-content">




<?php

  $count = 0;

  foreach ($prices as $item):

  $count++;

?>



<?php
      $val = false;
      if ($item['value'] != false) {
          $val = floatval($item['value']);
      }
?>

<div class="mw-ui-row-nodrop valign">
    <div class="mw-ui-col" style="width: 100px;">
      <span class="pricedropdown-currency"><?php print mw()->shop_manager->currency_symbol(); ?></span>
      <input type="text"
             data-custom-field-id="<?php print $item['id'] ?>"
             class="mw-ui-field mw-ui-field-big tip"
             data-tip="<?php print addslashes($item['name']) ?>"
             placeholder="0.00"
             value="<?php print $val; ?>"
             onfocus="mw.$('#pricedropdown').addClass('active');"
             onblur="mw.$('#pricedropdown').removeClass('active');"
             onchange="mw.quick_price_save_edit(this);<?php if($count==1){ print "mw.reload_module('#".$params['id']."')"; } ?>"  />
    </div>
</div>


<?php endforeach; ?>

</div>
</div>

<?php elseif(isset($prices[0])):

    $item = $prices[0];

 ?>


<?php if (isset($item['id']) and isset($item['type']) and $item['type'] == 'price'): ?>
<?php
      $val = false;
      if ($item['value'] != false) {
          $val = floatval($item['value']);
      }

?>

<span id="product-price-field-label"><?php print mw()->shop_manager->currency_symbol(); ?></span>

<input type="text" data-custom-field-id="<?php print $item['id'] ?>"
                   data-tip="<?php print addslashes($item['name']) ?>"
                   class="mw-ui-invisible-field mw-ui-field-big admin-imp-field tip"
                   placeholder="0.00" value="<?php print $val; ?>" onchange="mw.quick_price_save_edit(this)" style="width:90px;height:54px;"  />
<?php endif; ?>



<?php endif; endif; ?>
