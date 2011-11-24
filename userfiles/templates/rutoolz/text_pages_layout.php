<?php
/*
type: layout
name: text_pages_layout.php
description: text_pages_layout.php




*/

?>
<?php include "header.php" ?>



 
 
<div id="RU-content" class="text-pages">
	<div class="pad2"></div>
	<div class="preloader">
    	<div class="preload"><img src="<? print TEMPLATE_URL; ?>images/loader.gif" alt="Preloader" /></div>
    </div>
    

	<!--BOX WELCOME-->
	<div class="box-holder">    
    	<div class="box-top"></div>        
    	<div class="box-inside">
        
        
        
        {content}
                  
          <?php include "page_menu.php" ?>      
        
        <div class="clr"></div>
        </div>
        <div class="box-bottom"></div>
        <!--END BOX WELCOME-->
    </div>

	
  
<div class="pad2"></div>
 <!--END CONTENT-->
</div> 













<?php include "footer.php" ?>