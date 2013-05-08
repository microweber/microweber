<?php

/*

type: layout
content_type: dynamic
name: Blog

description: Blog

*/


?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="container">
    <div class="row">
      <div class="span8">
        <div class="edit"  field="content" rel="page">
          <h2>Blog Page</h2>
          <p class="p0 element">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.</p>
          <module data-type="posts" data-template="blog" data-page-id="<? print PAGE_ID ?>"  />
        </div>
      </div>
      <div class="span3 offset1">
        <? include_once TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</div>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
