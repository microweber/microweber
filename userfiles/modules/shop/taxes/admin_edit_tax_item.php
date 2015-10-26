<script>

function mw_admin_edit_tax_item_submit_form(form) {
	 var data = $(form ).serialize()
	 var url = mw.settings.api_url+'shop/save_tax_item';
	 $.post( url, data)
	  .done(function( data ) {
		$(window ).trigger( "mw.admin.shop.tax.edit.item.saved" );
	  });
	 
	 return false;
}

</script>
<?php
$default_item = array('id'=>0,'tax_name'=>'','tax_modifier'=>'','amount'=>'');
$item = array();
if(isset($params['tax_item_id'])){
	$get = mw()->shop_manager->get_taxes('single=true&id='.$params['tax_item_id']);	
	if($get){
	$item = $get;	
}
}

$values = array_merge($default_item,$item);
 
?>

<form id="mw_edit_tax_item" onsubmit="return mw_admin_edit_tax_item_submit_form(this)">
  <input type="hidden" name="id" value="<?php print $values['id']; ?>" />
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Tax name</label>
    <input name="tax_name" type="text" class="mw-ui-field" required="required" value="<?php print $values['tax_name']; ?>">
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Tax modifier</label>
    <select name="tax_modifier" class="mw-ui-field mw-ui-field-medium">
      <option value="fixed" <?php if($values['tax_modifier'] == 'fixed') : ?> selected="selected" <?php endif; ?>>Fixed</option>
      <option value="percent" <?php if($values['tax_modifier'] == 'percent') : ?> selected="selected" <?php endif; ?>>Percent</option>
    </select>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Tax amount</label>
    <input name="amount" type="text" class="mw-ui-field" required="required" value="<?php print $values['amount']; ?>">
  </div>
  <br />
  <br />
  <input class="mw-ui-btn mw-ui-btn-invert" type="submit" name="submit" value="Save" />
</form>
