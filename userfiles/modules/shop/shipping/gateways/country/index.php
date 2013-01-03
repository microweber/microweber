<? require_once($config['path_to_module'].'country_api.php'); ?>
<?  if (!defined('MW_API_CALL')) : ?>
<? $rand = md5(serialize($params));
 
  $shipping_to_country = new shipping_to_country(); 
 
 
 $data = $shipping_to_country->get('is_active=y');
 $countries_used = array();
 
 if( $data == false){
	 $data = array(); 
 }
 
  ?>
<script  type="text/javascript">
  mw.require('forms.js');
 </script>
<script type="text/javascript">
  
  function mw_shipping_<? print $rand ?>(){
 mw.form.post( '#<? print $rand ?>', '<? print $config['module_api']; ?>/shipping_to_country/set');  
 
}
  
  
  
$(document).ready(function(){
	mw_shipping_<? print $rand ?>();
mw.$('#<? print $rand ?>').change(function() {
	mw_shipping_<? print $rand ?>();
}); 

}); 


 
</script>
 
<div class="<? print $config['module_class'] ?>" id="<? print $rand ?>"> Choose country:
  <select name="country" >
    <? foreach($data  as $item): ?>
    <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
    <? endforeach ; ?>
  </select>
  <? endif; ?>
  <br />
  city
  <input name="city"  type="text" value="" />
  <br />
  state
  <input name="state"  type="text" value="" />
  <br />
  zip
  <input name="zip"  type="text" value="" />
  <br />
  address
  <input name="address"  type="text" value="" />
  <br />
  phone
  <input name="phone"  type="text" value="" />
  <br />
</div>
