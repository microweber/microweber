<?php


 $data = api('shop/shipping/gateways/standard/shipping_standard/get', "is_active=1");
 $data_disabled = api('shop/shipping/gateways/standard/shipping_standard/get', "is_active=0");

 $countries_used = array();
  $countries_all = array();
 if( $data == false){
	 $data = array();
 }
  if(is_array($data)){
	foreach($data as $key => $item){
			if(trim(strtolower($item['shipping_country']))  == 'worldwide' ){
				 $countries_all = mw('Microweber\Forms')->countries_list();
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

 $class = '';
 if(isset($params['class']) and $params['class'] != '' and $params['class']){
	 $class = $params['class'];

 }

 $sel = mw()->user_manager->session_get('shipping_country');

  ?>


  <select name="country" class="<?php print $class  ?>">
  <?php foreach($data  as $item): ?>
  <option value="<?php print $item['shipping_country'] ?>"  <?php if($sel == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
  <?php endforeach ; ?>
</select>