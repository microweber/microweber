<? //  require_once($config['path_to_module'].'country_api.php'); ?>
<?  $rand = uniqid();

  
 $data = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=y");
 $data_disabled = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=n");
 
 $countries_used = array();
  $countries_all = array();
 if( $data == false){
	 $data = array(); 
 }
  if(isarr($data)){
	foreach($data as $key => $item){
			if(trim(strtolower($item['shiping_country']))  == 'worldwide' ){
				 $countries_all = countries_list();
				 unset($data[$key]);
				  if(isarr($countries_all)){
					  
					  foreach($countries_all as  $countries_new){
						  $data[] = array('shiping_country' =>  $countries_new);
					  }
 	 
 					}
			}
	}
	
	
	
	
}


 
 if(isarr($data)){
	foreach($data as $key =>$item){
		$skip = false;
		if(isarr($data_disabled)){
			foreach($data_disabled as $item_disabled){
				if($item['shiping_country']  == $item_disabled['shiping_country'] ){
					$skip = 1;
					unset($data[$key]);
				}
			}
		}

	}
  }
 
 
 
 
 
 
  ?>
<script  type="text/javascript">
  mw.require('forms.js',true);
</script>
<script type="text/javascript">

  function mw_shipping_<? print $rand; ?>(){
    mw.form.post( '#<? print $rand; ?>', '<? print $config['module_api']; ?>/shipping_to_country/set');
  }
  
  

$(document).ready(function(){
	mw_shipping_<? print $rand; ?>();
	mw.$('#<? print $rand; ?>').change(function() {
	 mw_shipping_<? print $rand; ?>();
	});

}); 



</script>
<? if(isset($params['template']) and trim($params['template']) == 'select') : ?>

<select name="country" class="shipping-country-select">
  <? foreach($data  as $item): ?>
  <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
  <? endforeach ; ?>
</select>
<? else: ?>
<div class="<? print $config['module_class'] ?>" id="<? print $rand; ?>">



 


  <label>
    <?php _e("Choose country:"); ?>
  </label>
  <select name="country" class="field-full">
    <? foreach($data  as $item): ?>
    <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
    <? endforeach ; ?>
  </select>
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
<? endif; ?>
