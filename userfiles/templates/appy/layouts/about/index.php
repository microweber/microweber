<?php

/*

type: layout

name: about layout

description: about site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="container_12" id="mainContent">
  <div class="grid_12">
    <div class="clear" style="height:30px;"></div>
    <!-- Text boxes -->
    <div id="boxes">
     <editable  rel="page" field="content_body"> <? print $page['the_content_body']; ?> </editable>
    </div>
    <!-- end:Text boxes -->
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
