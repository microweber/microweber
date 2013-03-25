<? //  require_once($config['path_to_module'].'country_api.php'); ?>
<?  $rand = uniqid();

  
 $data = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=y");
 
 
 $countries_used = array();
 
 if( $data == false){
	 $data = array(); 
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
  <select name="country" class="mw-ui-simple-dropdown">
    <? foreach($data  as $item): ?>
    <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
    <? endforeach ; ?>
  </select>
  <label>
    <?php _e("City"); ?>
  </label>
  <input name="city"  type="text" value="" />
  <label>
    <?php _e("State"); ?>
  </label>
  <input name="state"  type="text" value="" />
  <label>
    <?php _e("Zip/Postal Code"); ?>
  </label>
  <input name="zip"  type="text" value="" />
  <label>
    <?php _e("Address"); ?>
  </label>
  <input name="address"  type="text" value="" />
</div>
<? endif; ?>
