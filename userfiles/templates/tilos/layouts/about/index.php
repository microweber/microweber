<?php

/*

type: layout

name: Media layout

description: Media site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? include dirname(__FILE__)."/sidebar.php"; ?>

<div id="main">
  <h2 class="title"><? print $page['content_title']; ?></h2>
  <editable  rel="page" field="content_body"><? print $page['the_content_body']; ?></editable>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
