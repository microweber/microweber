<?php
/*
type: layout
name: Default layout
description: default site layout




*/

?>
<?php $include_ictures_in_the_heeader = false; ?>
<?php include "header.php" ?>
				
                 
<?php if($page['id'] == 31) : ?>   


<?php include(ACTIVE_TEMPLATE_DIR.'collections_main.php') ;  ?>        
            
                
         <?php else: ?>
         <?php include(ACTIVE_TEMPLATE_DIR.'collections_inner.php') ;  ?>    
         <?php endif; ?>       
                
               
                
                
                
                
                
                
			<?php include "footer.php" ?>