<?php

/*

type: layout
content_type: static
name: Login

description: Login layout

*/


?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>
<div id="content">
     <div class="container edit"  field="content" rel="content">
    
        <div class="span12">
            <div class="well small-layout headed-box">
                <module="users/login" />
             </div>
         </div>
     </div>
 </div>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
