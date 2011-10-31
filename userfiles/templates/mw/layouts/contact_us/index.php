<?php

/*

type: layout

name: contact us layout

description: contact us site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="content_wide_holder_white content_center_about_us">
  <div class="content_center">
  
  
 
    <div class="col" style="width:220px; margin-right:30px; float:left"><img  src="<? print TEMPLATE_URL ?>img/mw1.png" /> </div >
    <div class="col" style="float:right;width:720px;"> <img  src="<? print TEMPLATE_URL ?>img/nice_to_meet_you.png" />
      <p class="text_14 line_spacing"> You are welcome to share with us any ideas or questions you have in mind </p>
      
      <br />
      <br />
      <microweber module="forms/mail_form" module_id="contact_us<? print PAGE_ID ?>" />
    </div >
    
    
    
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
