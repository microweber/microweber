<?php  $rand = 'shipping_country_'.crc32($params['module']);


 $data = mw('shop\shipping\gateways\country\shipping_to_country')->get("is_active=1");
  
 
// $data = api('shop/shipping/gateways/country/shipping_to_country/get', "is_active=1");
 
 
 
 $data_disabled = mw('shop\shipping\gateways\country\shipping_to_country')->get("is_active=0");
 $shipping_cost = mw('shop\shipping\gateways\country\shipping_to_country')->get_cost(); 
 $shipping_cost = floatval($shipping_cost);
 $countries_used = array();
  $countries_all = array();
 if( $data == false){
	 $data = array();
 }
 
 

 
 
  if(is_array($data)){
	foreach($data as $key => $item){
			if(trim(strtolower($item['shipping_country']))  == 'worldwide' ){
				 $countries_all = mw()->forms_manager->countries_list();
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

  function mw_shipping_<?php print $rand; ?>(country){
	  
	  
	  var data = {};
	  data.country = country;

	  
	  
	  $.post( "<?php print $config['module_api']; ?>/shipping_to_country/set", data)
		  .done(function( msg ) {
			
			 if(msg.shipping_country != undefined){
				 		 mw.$("[name='country']").val(this.shipping_country)
			 }
			 mw.reload_module('shop/cart');
 			 mw.reload_module('shop/shipping');
			 mw.reload_module('<?php print $config['module']; ?>');
			
		  });
  }



$(document).ready(function(){
	mw.$("#<?php print $rand; ?> [name='country']").change(function() {
	mw_shipping_<?php print $rand; ?>($(this).val());
	});
});



</script>
<?php

$module_template = get_option('data-template',$params['id']);


if($module_template == false and isset($params['template'])){
	$module_template = $params['template'];
} elseif($module_template == false ){
	$module_template = 'default';
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

 