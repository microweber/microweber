<? 

$custom_fields = array();
$custom_fields['page'] = array();


$custom_fields['post'] = array();
$cf =  array();

$cf['name'] = "Website";
$cf['help'] = "Dealer's website";
$cf['type'] = "text";
$cf['default'] = "";
$cf['param'] = "website"; 
$cf['group'] = "dealers"; 


$custom_fields['post'] [] = $cf;


$cf['name'] = "Address";
$cf['help'] = "Dealer's adress";
$cf['type'] = "text";
$cf['default'] = "";
$cf['param'] = "address"; 
$cf['group'] = "dealers"; 


$custom_fields['post'] [] = $cf;


$cf['name'] = "Phone";
$cf['help'] = "Dealer's phone";
$cf['type'] = "text";
$cf['default'] = "";
$cf['param'] = "phone"; 
$cf['group'] = "dealers"; 


$custom_fields['post'] [] = $cf;

/*$custom_fields['category'] = array();
$cf =  array();

$cf['name'] = "category price";
$cf['help'] = "category in USD";
$cf['type'] = "text";
$cf['default'] = "0";
$cf['param'] = "categoryprice2"; 
$cf['group'] = "pricing"; 


$custom_fields['category'] [] = $cf;*/