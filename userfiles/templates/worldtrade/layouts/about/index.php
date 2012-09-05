<?php

/*

type: layout

name: about layout

description: about site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
 
 
?>

<div id="middle">
  <div class="right_col right">
 
  <editable  rel="page" field="content_body">
   <? print $page['the_content_body']; ?>
  </editable>
 
    <div class="clener h10"></div>
  </div>
  <? include   TEMPLATE_DIR.  "sidebar.php"; ?>
  <div class="clener"></div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
