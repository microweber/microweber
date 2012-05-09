<?php

/*

type: layout

name: Home layout

description: Home site layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

 

<div id="content">
  <div class="inner">
    <div class="container">
      <div class="row">
    
        <div class="span12 landing-screenshot">
        <h1>Please fill up the application form and we will contact you</h1>
          <? 
		  $large_form = true;
		  include TEMPLATE_DIR. "layouts/apply/form.php"; ?>
        </div>
      </div>
      <!-- /row --> 
      
    </div>
    <!-- /container --> 
    
  </div>
  <!-- /inner --> 
  
</div>
<!-- /content -->

 

<? include   TEMPLATE_DIR.  "footer.php"; ?>
