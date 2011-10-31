<? 

$custom_fields = array();
$custom_fields['page'] = array();


$custom_fields['post'] = array();
$cf =  array();

$cf['name'] = "Is featured?";
$cf['help'] = "Applies the 'featured' stripe";
$cf['type'] = "dropdown";
$cf['default'] = "n";
$cf['param'] = "is_featured"; 
$cf['values'] = "n,y"; 
$cf['group'] = "post"; 


$custom_fields['post'] [] = $cf;


 