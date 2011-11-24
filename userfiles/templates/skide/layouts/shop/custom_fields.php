<? 

$custom_fields = array();
$custom_fields['page'] = array();


$custom_fields['post'] = array();
$cf =  array();

$cf['name'] = "Item price";
$cf['help'] = "Item price in USD";
$cf['type'] = "text";
$cf['default'] = "0";
$cf['param'] = "price2"; 
$cf['group'] = "pricing"; 


$custom_fields['post'] [] = $cf;


$custom_fields['category'] = array();
$cf =  array();

$cf['name'] = "category price";
$cf['help'] = "category in USD";
$cf['type'] = "text";
$cf['default'] = "0";
$cf['param'] = "categoryprice2"; 
$cf['group'] = "pricing"; 


$custom_fields['category'] [] = $cf;