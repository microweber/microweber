<?php

/*

type: layout

name: distributors layout

description: distributors site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
 
 
?>

<div id="middle">
  <div class="right_col right">
    <editable  rel="page" field="content_body"> <? print $page['the_content_body']; ?> </editable>
    <br />
    <br />
    
    
    
    
    
    
    
    
    
    
    <microweber module="users/login" />
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="clener h10"></div>
  </div>
  <? include   TEMPLATE_DIR.  "sidebar.php"; ?>
  <div class="clener"></div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
