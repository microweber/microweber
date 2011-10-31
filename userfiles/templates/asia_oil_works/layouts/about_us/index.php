<?php

/*

type: layout

name: search layout

description: search layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="content" class="TheContent">
  <div class="TheContent1 searchEngine">
    <div class="pad2 ishr" id="search_header">
      <div class="right">
        <h2><? print $page['content_title'] ?></h2>
      </div>
    </div>
    <!-- /#search_header -->
    <div id="side_in">
      <ul class="cats">
        <microweber module="content/pages_tree" />
      </ul>
    </div>
    <div id="cont_in">
      <div class="pad2"> <? print $page['content_body'] ?>
        <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
      </div>
    </div>
    <!-- /#cont_in -->
    <div class="c">&nbsp;</div>
    <div id="footer">
      <address>
      <a href="#">Conditions of Use</a> | <a href="#">Privacy Notice</a> &copy; 1999-2011, <a href="http://asiaoilworks.com">AsiaOilWorks.com</a>, or its affiliates | Powered by <a href="http://microweber.com" title="Microweber">Microweber</a> | Design by <a href="http://ooyes.net" title="Web Design">OoYes.net</a>
      </address>
    </div>
  </div>
  <div class="TheContentTop">&nbsp;</div>
  <div class="TheContentBottom">&nbsp;</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
