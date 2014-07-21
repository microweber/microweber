<script>
    mw.quick_price_save_edit = function (el) {
     var price_field = $(el);
	 var data = {};
	 data.id = price_field.attr('data-custom-field-id');
	 data.value = price_field.val();   
	 mw.custom_fields.edit(data);
	
		
 
		
		
    }
</script>
<?php if (isset($data) and !empty($data)): ?>
<span id="product-price-field-label"><?php print mw('shop')->currency_symbol(); ?></span>

<?php foreach ($data as $item): ?>
<?php if (isset($item['id']) and isset($item['type']) and $item['type'] == 'price'): ?>
<?php
            $val = false;
            if ($item['value'] != false) {
                $val = floatval($item['value']);

            }
			 
?>
<input type="text" data-custom-field-id="<?php print $item['id'] ?>"
                   data-tip="<?php print addslashes($item['name']) ?>"
                   class="mw-ui-invisible-field mw-ui-field-big admin-imp-field tip"  
                   placeholder="0.00" value="<?php print $val; ?>"  onchange="mw.quick_price_save_edit(this)"  />
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
