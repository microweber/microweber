<?php //  require_once($config['path_to_module'].'country_api.php'); ?>
<?php  $rand = uniqid();


 $data = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=y");
 $data_disabled = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=n");

 $countries_used = array();
  $countries_all = array();
 if( $data == false){
	 $data = array();
 }
  if(is_array($data)){
	foreach($data as $key => $item){
			if(trim(strtolower($item['shipping_country']))  == 'worldwide' ){
				 $countries_all = mw('forms')->countries_list();
				 unset($data[$key]);
				  if(is_array($countries_all)){

					  foreach($countries_all as  $countries_new){
						  $data[] = array('shipping_country' =>  $countries_new);
					  }

 					}
			}
	}




}



 if(is_array($data)){
	foreach($data as $key =>$item){
		$skip = false;
		if(is_array($data_disabled)){
			foreach($data_disabled as $item_disabled){
				if($item['shipping_country']  == $item_disabled['shipping_country'] ){
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

  function mw_shipping_<?php print $rand; ?>(){
    mw.form.post( '#<?php print $rand; ?>', '<?php print $config['module_api']; ?>/shipping_to_country/set',function() {
	 mw.reload_module('shop/cart');

	 if(this.shipping_country != undefined){
		//d(this.shipping_country);
		mw.$("[name='country']").val(this.shipping_country)
	 }




	});
  }



$(document).ready(function(){
	//mw_shipping_<?php print $rand; ?>();
	mw.$('#<?php print $rand; ?>').change(function() {
	 mw_shipping_<?php print $rand; ?>();
	});

});



</script>
<?php
$module_template = 'default';
if(isset($params['template'])){
	$module_template = $params['template'];
}
if($module_template != false){
	$template_file = module_templates( $config['module'], $module_template);

} else {
	$template_file = module_templates( $config['module'], 'default');

}
 
 
if(isset($template_file) and ($template_file) != false and is_file($template_file) != false){
	include($template_file);
} else {
	$template_file = module_templates( $config['module'], 'default');
	if(($template_file) != false and is_file($template_file) != false){
		include($template_file);
	} else {
		$complete_fallback = dirname(__FILE__).DS.'templates'.DS.'default.php';
		 if(is_file($complete_fallback) != false){
			include($complete_fallback);
		}
		 
	}
	//print 'No default template for '.  $config['module'] .' is found';
}

 