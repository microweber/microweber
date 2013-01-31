<?php

/*

type: layout
content_type: dynamic
name: Blog layout

description: Blog layout

*/


?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<section id="content">
  <div class="container">
    <div class="row"> 
      <!-------------- Blog post -------------->
      <div class="span8">
        <div class="row">
          <div class="edit"  field="content" rel="page">
            <div class="page-header1">
              <h2>Blog Page</h2>
            </div>
            <p class="p0 element">Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
          </div>
        </div>
        <div class="edit"  field="sub_content" rel="page">
          <module type="posts" template="blog"   />
        </div>
      </div>
      <!------------ Sidebar -------------->
      <div class="span4">
        <? include_once TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
    </div>
  </div>
</section>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
