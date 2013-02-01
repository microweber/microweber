<? //  require_once($config['path_to_module'].'country_api.php'); ?>
<? //$rand = md5(serialize($params));

  
 $data = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=y");
 
 
 $countries_used = array();
 
 if( $data == false){
	 $data = array(); 
 }
 
  ?>
<script  type="text/javascript">
  mw.require('forms.js');
</script>
<script type="text/javascript">

  function mw_shipping_{rand}(){
    mw.form.post( '#{rand}', '<? print $config['module_api']; ?>/shipping_to_country/set');
  }
  
  

$(document).ready(function(){
	mw_shipping_{rand}();
mw.$('#{rand}').change(function() {
 mw_shipping_{rand}();
});

}); 



</script>

<div class="<? print $config['module_class'] ?>" id="{rand}">


    <label><?php _e("Choose country:"); ?></label>
  <select name="country" class="mw-ui-simple-dropdown">
    <? foreach($data  as $item): ?>
    <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
    <? endforeach ; ?>
  </select>

  <label><?php _e("City"); ?></label>
  <input name="city"  type="text" value="" />

  <label><?php _e("State"); ?></label>
  <input name="state"  type="text" value="" />

  <label><?php _e("Zip/Postal Code"); ?></label>
  <input name="zip"  type="text" value="" />
  <label><?php _e("Address"); ?></label>
  <input name="address"  type="text" value="" />



</div>
