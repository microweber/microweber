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
          <p class="p0 element">Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
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
