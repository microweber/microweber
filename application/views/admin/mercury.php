<?  //  include('header_scripts.php'); ?>
 <link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/modules.css" type="text/css"   />

    <? $v = ( url_param('view', true) );?>
      
 <? 
 
 
 if($v) {
	 
	 include("mercury/".$v.'.php'); 
	 
	 
 }?>

