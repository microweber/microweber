<?php

/*

type: layout
content_type: dynamic
name: Blog

description: Blog

*/


?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="container">


   <h2 class="main-title">Blog</h2>

  <div class="mw-ui-row content-row">
      <div class="mw-ui-col" style="width: 75%">
        <div class="edit"  field="content" rel="page">
          <module data-type="posts" data-template="lite" data-page-id="<?php print PAGE_ID ?>"  />
        </div>
      </div>
      <div class="mw-ui-col" style="width: 25%">
        <?php include_once TEMPLATE_DIR. 'layouts' . DS."blog_sidebar.php"; ?>
      </div>
    </div>





  </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
