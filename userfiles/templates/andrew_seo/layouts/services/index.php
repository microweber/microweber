<?php

/*

type: layout

name: services

description: services site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="subheader_container">
  <div id="subheader">
    <h2><? print $page['content_title']; ?></h2>
  </div>
</div>
<div id="content_wrapper">
  <!-- sidebar -->
   <? include TEMPLATE_DIR. "sidebar_services.php"; ?>
  <!-- /sidebar -->
  <!-- content -->
  <div id="content">
    
   
   
   
   <? print $page['content_body']; ?>
  </div>
  <!-- /content -->
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
