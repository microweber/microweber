
    <? $v = ( url_param('view', true) );?>
      
 <? 
 
 
 if($v) {
	 
	 include("mercury/".$v.'.php'); 
	 
	 
 }?>

