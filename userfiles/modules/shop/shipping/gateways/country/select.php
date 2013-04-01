<?   

  
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
 
 $class = '';
 if(isset($params['class']) and $params['class'] != '' and $params['class']){
	 $class = $params['class'];
	 
 }
 
 
 
  ?>
  
  
  <select name="country" class="<? print $class  ?>">
  <? foreach($data  as $item): ?>
  <option value="<? print $item['shiping_country'] ?>"  <? if(isset($_SESSION['shiping_country']) and $_SESSION['shiping_country'] == $item['shiping_country']): ?> selected="selected" <? endif; ?>><? print $item['shiping_country'] ?></option>
  <? endforeach ; ?>
</select>