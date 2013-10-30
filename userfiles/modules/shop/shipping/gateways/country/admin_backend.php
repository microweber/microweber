<?php only_admin_access(); ?>
<?php $rand1 = 'shipping_to_country_holder'.uniqid(); ?>
 
	 
<span class="mw-ui-btn right" onclick="mw.tools.module_settings('shop/shipping/set_units');">Set shipping units</span>
<?php


  require_once($config['path_to_module'].'shipping_to_country.php');
 $shipping_to_country = new shipping_to_country();


 $data  =  $data_orig = $shipping_to_country->get();
 if( $data == false){
	 $data = array();
 }



 $countries_used = array();
  $data[] = array();

     $countries =    mw('forms')->countries_list();
	 
	 if(is_array($countries)){
asort($countries);

}
	 
	 if(!is_array($countries)){


		   $countries =    mw('forms')->countries_list(1);
	 } else {

	 array_unshift($countries, "Worldwide");
	 }







    // $countries[] = 'Worldwide';
    ?>
<script  type="text/javascript">
if(mw.shipping_country == undefined){
  mw.shipping_country = {};
}
  mw.require('forms.js');

  mw.require('<?php print $config['url_to_module'] ?>country.js');


if(typeof thismodal !== 'undefined'){
   thismodal.main.width(1000);
   $(thismodal.main[0].getElementsByTagName('iframe')).width(985);
}


 </script> 
<script  type="text/javascript">


mw.shipping_country.url = "<?php print $config['module_api']; ?>";

 $(document).ready(function(){

    mw.$(".<?php print $rand1 ?>").sortable({
       items: '.shipping-country-holder',
       axis:'y',
       cancel:".country-id-0",
       handle:'.shipping-handle-field',
       update:function(){
         var obj = {cforder:[]}
         $(this).find('form').each(function(){
            var id = this.attributes['data-field-id'].nodeValue;
            obj.cforder.push(id);
         });
         $.post("<?php print $config['module_api']; ?>/shipping_to_country/reorder", obj, function(){
		    mw.reload_module('[data-parent-module="shop/shipping"]');
			 if(window.parent != undefined && window.parent.mw != undefined){
				window.parent.mw.reload_module('shop/shipping/gateways/country');
			  }
		 });
       },
       start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
       },
       stop:function(){
           mw.$(".<?php print $rand1 ?>").height("auto");
       },
       scroll:false,
       placeholder: "custom-field-main-table-placeholder"
  });



  <?php if(empty( $data_orig )): ?>
mw.$('.country-id-0').show()
 <?php endif;?>


 mw.$(".shipping_type_dropdown").each(function(){
    var parent = mw.tools.firstParentWithTag(this, 'td');
    if($(this).val() == 'dimensions'){
      mw.$(".shipping_dimensions", parent).show()
	  mw.$(".shipping_per_item", parent).hide()
    } else if($(this).val() == 'per_item'){
	   mw.$(".shipping_dimensions", parent).hide()
	   mw.$(".shipping_per_item", parent).show()
		
	} else{
      mw.$(".shipping_dimensions", parent).hide()
	  mw.$(".shipping_per_item", parent).hide()

    }
 });

  mw.$(".shipping_type_dropdown").change(function(){
    var parent = mw.tools.firstParentWithTag(this, 'td');
    if($(this).val() == 'dimensions'){
      mw.$(".shipping_dimensions", parent).slideDown()
	  mw.$(".shipping_per_item", parent).hide()

    } else if($(this).val() == 'per_item'){
	   mw.$(".shipping_dimensions", parent).hide()
	   mw.$(".shipping_per_item", parent).show()
	} else{
      mw.$(".shipping_dimensions", parent).slideUp()
	   mw.$(".shipping_per_item", parent).hide()

    }
 });

 mw.$(".mw-onoff").mousedown(function(){
    var el = this;
    if(mw.tools.hasClass(el, 'active')){
       mw.tools.removeClass(el, 'active');
       el.querySelector('.is_active_n').checked = true;
    }
    else{
       mw.tools.addClass(el, 'active');
       el.querySelector('.is_active_y').checked = true;
    }
 });


});



</script>
<?php
 $data_active = array();
 $data_disabled = array();
 foreach($data  as $item): ?>
<?php

  if(isset($item['is_active']) and 'n' == trim($item['is_active'])){
	  $data_disabled[] = $item;
  } else {
	  $data_active[] = $item;
  }



   if(isset($item['shipping_country'])){
    	 $countries_used[] = ($item['shipping_country']);
    }
    ?>
<?php endforeach ; ?>
<?php

$datas['data_active'] = $data_active;
$datas['data_disabled'] = $data_disabled;

?>
<div class="vSpace"></div>
<div class="vSpace"></div>
<?php
 $data_active = array();
 $data_disabled = array();
 foreach($datas  as $data_key=> $data): ?>
<?php if(empty($data)): ?>
<?php endif; ?>
<?php
if($data_key == 'data_disabled'){
 $truck_class = 'red';
} else {
$truck_class = 'green';
}



 ?> 
<?php if(is_array($data ) and !empty($data)): ?>
<div class="mw-shipping-left-bar"> <span class="shipping-truck shipping-truck-<?php print $truck_class ?>"></span> <span class="mw-ui-btn" onclick="mw.$('.country-id-0').show().find('.mw-ui-simple-dropdown').focus();mw.tools.scrollTo('.country-id-0');mw.$('.country-id-0').effect('highlight', {}, 3000)">
	<?php _e("Add Country"); ?>
	
	</span> 
	<br /><br />
	 <?php if($truck_class == 'green'): ?>
	<small class="mw-ui-label-help">Shipping to those countires is enabled. <br /><br />
To disable it set <br />"<em>is active</em>" to "No".</small>
	<?php else: ?>
	
	<small class="mw-ui-label-help">Shipping to those countires is disabled. <br /><br />
To enable it set <br />"<em>is active</em>" to "Yes".</small>
	<?php endif ?>
	</div>
<div class="mw-shipping-items <?php print $rand1 ?>" id="<?php print $rand1 ?>"> 
	<script type="text/javascript">

SaveShipping = function(form, dataType){
    mw.form.post($(form) , '<?php print $config['module_api']; ?>/shipping_to_country/save', function(){



  if(dataType == 'new'){
        mw.reload_module('<?php print $config['the_module']; ?>', function(){
          mw.notification.success("<?php _e("Shipping changes are saved"); ?>");
        });
  }
  else{
        mw.reload_module(dataType, function(){
          mw.notification.success("<?php _e("Shipping changes are saved"); ?>");
        });
  }
  
  
  
  if(window.parent != undefined && window.parent.mw != undefined){
    window.parent.mw.reload_module('shop/shipping/gateways/country');
  }
  
  

    }




      );

}

</script>
	<?php foreach($data  as $item): ?>
	<?php
  
  
  
$new = false;
if(!isset($item['id'])){
	if($data_key == 'data_active'){
	$item['id']= 0;
	$item['is_active']= 'y';
	$item['shipping_country']= 'new';
	$new = true;
	}
}

if(!isset($item['shipping_cost'])){
$item['shipping_cost']= '0';
}
if(!isset($item['shipping_cost_max'])){
$item['shipping_cost_max']= '0';
}

if(!isset($item['shipping_cost_above'])){
$item['shipping_cost_above']= '0';
}
if(!isset($item['position'])){
$item['position']= '999';
}


if(!isset($item['shipping_type'])){
$item['shipping_type']= 'fixed';
}
if(!isset($item['shipping_price_per_size'])){
$item['shipping_price_per_size']= 0;
}

if(!isset($item['shipping_price_per_weight'])){
$item['shipping_price_per_weight']= 0;
}



if(!isset($item['shipping_type'])){
$item['shipping_type']= 'fixed';
}

if(!isset($item['shipping_price_per_size'])){
$item['shipping_price_per_size']= 0;
}
if(!isset($item['shipping_price_per_weight'])){
$item['shipping_price_per_weight']= 0;
}
if(!isset($item['shipping_price_per_item'])){
$item['shipping_price_per_item']= 0;
}

$size_units = get_option('shipping_size_units', 'orders');
$weight_units = get_option('shipping_weight_units', 'orders');
if($size_units == false){
$size_units= 'cm';
}
if($weight_units == false){
$weight_units= 'kg';
}
?>
	<?php //$rand = 'shipping_to_country_'.uniqid().$item['id']; ?>
	<div data-field-id="<?php print $item['id']; ?>" onmousedown="mw.tools.focus_on(this);" class="shipping-country-holder country-id-<?php print $item['id']; ?>">
		<form onsubmit="SaveShipping(this, '<?php if($new == false){ print $params['data-type'];} else{print 'new';} ?>');return false;" action="<?php print $config['module_api']; ?>/shipping_add_to_country"  data-field-id="<?php print $item['id']; ?>">
			<table class="admin-shipping-table">
				<tr class="shipping-country-row">
					<td class="shipping-country-label"><?php if($new == true): ?>
						<?php _e("Add new"); ?>
						<?php else : ?>
						<?php _e("Shipping to"); ?>
						<?php /* print ucfirst($item['shipping_country']); */ ?>
						<?php endif; ?></td>
					<td class="shipping-country-setting"><?php if($new == false): ?>
						<input type="hidden" name="id" value="<?php print $item['id']; ?>" >
						<?php endif; ?>
						<span class="mw-help-field left">
						<select name="shipping_country" class="mw-ui-simple-dropdown">
							<?php if($new == true): ?>
							<option value="none">
							<?php _e("Choose country"); ?>
							</option>
							<?php endif; ?>
							<?php foreach($countries  as $item1): ?>
							<?php
            		$disabled = '';
            		foreach($countries_used  as $item2):
                    if($item2 == $item1){
            			$disabled = 'disabled="disabled"';
            		}
                    endforeach ;
            	  ?>
							<option value="<?php print $item1 ?>"   <?php if(isset($item['shipping_country']) and $item1 == $item['shipping_country']): ?> selected="selected" <?php else : ?> <?php print $disabled ?> <?php endif; ?>  ><?php print $item1 ?></option>
							<?php endforeach ; ?>
						</select>
						<span class="mw-ui-label-help">
						<?php _e("Select country"); ?>
						</span> </span> <span class="shipping-arrow"></span>
						<div class="left" style=" margin-right: 10px;margin-top: 3px;">
							<?php _e("Is active?"); ?>
						</div>
						<div class="mw-onoff<?php if( 'y' == trim($item['is_active'])): ?> active<?php endif; ?>">
							<label>No
								<input name="is_active" type="radio" class="semi_hidden is_active_n"  value="n" <?php if(isset($item['is_active']) and '' == trim($item['is_active']) or 'n' == trim($item['is_active'])): ?>   checked="checked"  <?php endif; ?> />
							</label>
							<label>Yes
								<input name="is_active" type="radio" class="semi_hidden is_active_y"  value="y" <?php if(isset($item['is_active']) and 'y' == trim($item['is_active'])): ?>   checked="checked"  <?php endif; ?> />
							</label>
						</div></td>
				</tr>
				<tr class="shipping-country-row">
					<td class="shipping-country-label">Shipping type</td>
					<td class="shipping-country-setting"><span class="mw-help-field left">
						<select name="shipping_type" class="mw-ui-simple-dropdown shipping_type_dropdown">
							<option value="fixed"  <?php if(isset($item['shipping_type']) and 'fixed' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?> >Fixed</option>
							<option value="dimensions" <?php if(isset($item['shipping_type']) and 'dimensions' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?>>Dimensions or Weight</option>
							<option value="per_item" <?php if(isset($item['shipping_type']) and 'per_item' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?>>Per item</option>
						</select>
						</span> <span class="shipping-arrow"></span>
						<label>
							<?php _e("Shipping cost"); ?>
							&nbsp;<b><?php print mw('shop')->currency_symbol() ?></b></label>
						<span class="mw-help-field">
						<input class="mw-ui-field shipping-price-field" type="text" onkeyup="mw.form.typeNumber(this);"  onblur="mw.form.fixPrice(this);" name="shipping_cost" value="<?php print $item['shipping_cost']; ?>" onfocus="if(this.value==='0')this.value='';" />
						<span class="mw-ui-label-help">
						<?php _e("Price per order"); ?>
						</span> </span>
						<div class="shipping_dimensions" style="display: none">
							<div class="mw-ui-field-holder">
								<label class="mw-ui-label">Additional cost for <em>1 cubic <?php print $size_units ?></em> &nbsp;<b><?php print mw('shop')->currency_symbol() ?></b></label>
								<span class="mwsico-width"></span>
								<input type="text" name="shipping_price_per_size" value="<?php print  floatval($item['shipping_price_per_size']); ?>" class="mw-ui-field" />
							</div>
							<div class="mw-ui-field-holder">
								<label class="mw-ui-label">Additional cost for <em>1 <?php print $weight_units ?></em> &nbsp;<b><?php print mw('shop')->currency_symbol() ?></b></label>
								<span class="mwsico-usd"></span>
								<input type="text" name="shipping_price_per_weight" value="<?php print floatval($item['shipping_price_per_weight']); ?>" class="mw-ui-field" />
							</div>
						</div>
						
						<div class="shipping_per_item" style="display: none">
							<div class="mw-ui-field-holder">
								<label class="mw-ui-label">Cost for shipping <em>each item in the shopping cart</em> &nbsp;</label>
								<span><b><?php print mw('shop')->currency_symbol() ?></b></span>
								<input type="text" name="shipping_price_per_item" value="<?php print  floatval($item['shipping_price_per_item']); ?>" class="mw-ui-field" />
							</div>
							 
						</div>
						
						</td>
				</tr>
				<tr class="shipping-country-row">
					<td class="shipping-country-label"><?php _e("Shipping Discount"); ?></td>
					<td class="shipping-country-setting"><div class="same-as-country-selector"> <span class="mw-help-field left" style="margin-right:12px;">
							<label>
								<?php _e("For orders above:"); ?>
							</label>
							<span class="mw-ui-label-help">example <?php print mw('shop')->currency_format(100) ?></span> </span>
							<input class="mw-ui-field shipping-price-field right" type="text" onkeyup="mw.form.typeNumber(this);" onblur="mw.form.fixPrice(this);" name="shipping_cost_above" value="<?php print $item['shipping_cost_above']; ?>" onfocus="if(this.value=='0')this.value='';">
							<label class="right"></label>
						</div>
						<span class="shipping-arrow"></span>
						<label>
							<?php _e("Shipping cost"); ?>
							<b><?php print mw('shop')->currency_symbol() ?></b></label>
						<span class="mw-help-field">
						<input class="mw-ui-field shipping-price-field" type="text" onkeyup="mw.form.typeNumber(this);" onblur="mw.form.fixPrice(this);" name="shipping_cost_max" value="<?php print $item['shipping_cost_max']; ?>" onfocus="if(this.value=='0')this.value='';" />
						<span class="mw-ui-label-help">
						<?php _e("Price per order"); ?>
						</span> </span>
						<div class="mw_clear vSpace">&nbsp;</div></td>
				</tr>
			</table>
			<button class="mw-ui-btn save_shipping_btn" type="submit">
			<?php _e("Save"); ?>
			</button>
			<div class="vSpace"></div>
			<?php if($new == false): ?>
			<span title="Move" class="ico iMove shipping-handle-field"></span> <span onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');" class="mw-ui-delete-x" title="<?php _e("Delete"); ?>"></span>
			<?php endif; ?>
		</form>
	</div>
	<?php endforeach ; ?>
</div>
<div class="mw_clear"></div>
<?php endif; ?>
<?php endforeach ; ?>
