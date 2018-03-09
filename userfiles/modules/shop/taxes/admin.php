<?php 
only_admin_access();

 
?>
<script>
mw_admin_edit_tax_item_popup_modal_opened = null
function mw_admin_edit_tax_item_popup(tax_item_id) {

    if(!!tax_item_id){
        var modalTitle = '<?php _e('Edit tax item'); ?>';
    }else{
        var modalTitle = '<?php _e('Add tax item'); ?>';
    }


	mw_admin_edit_tax_item_popup_modal_opened = mw.modal({
		content:   '<div id="mw_admin_edit_tax_item_module"></div>',
		title:     modalTitle,
		id:        'mw_admin_edit_tax_item_popup_modal'
	});
   
 	var params = {}
    params.tax_item_id = tax_item_id;
    mw.load_module('shop/taxes/admin_edit_tax_item','#mw_admin_edit_tax_item_module',null,params);   
}

function mw_admin_delete_tax_item_confirm(tax_item_id) {

	 var r = confirm("<?php _e('Are you sure you want to delete this tax?'); ?>");
		if (r == true) {
		var url = mw.settings.api_url+'shop/delete_tax_item';
		$.post( url, { id: tax_item_id })
		  .done(function( data ) {
			mw_admin_after_changed_tax_item();
		  });
		}
	 
	
}

function mw_admin_after_changed_tax_item() {
	mw.notification.success("<?php _e('Taxes are updated'); ?>");
	mw.reload_module('#mw_admin_shop_taxes_items_list');
}

$( document ).ready(function() {
    $(window).on( "mw.admin.shop.tax.edit.item.saved", function() {
		if(typeof('mw_admin_edit_tax_item_popup_modal_opened') != 'null'){
			mw_admin_edit_tax_item_popup_modal_opened.remove();
		}
	   mw_admin_after_changed_tax_item();
	});

});


</script>

<br>
<h4>
  <?php _e("Enable taxes support"); ?>
</h4>

<label class="mw-switch">
    <input
            type="checkbox"
            name="enable_taxes"
            class="mw_option_field"
            data-option-group="shop"
            data-value-checked="1"
            data-value-unchecked="0"
        <?php if(get_option('enable_taxes', 'shop') == 1): ?> checked="1" <?php endif; ?>>
    <span class="mw-switch-off">OFF</span>
    <span class="mw-switch-on">ON</span>
    <span class="mw-switcher"></span>
</label>



<hr>
<h4>
  <?php _e("Taxes list"); ?>
</h4>
<br>
<a class="mw-ui-btn mw-ui-btn-notification" href="javascript:mw_admin_edit_tax_item_popup(0)"><span class="mw-icon-cart-outline"></span><span> <?php _e('Add new tax'); ?> </span></a> <br>
<br>


        <module type="shop/taxes/admin_list_taxes" id="mw_admin_shop_taxes_items_list" />



