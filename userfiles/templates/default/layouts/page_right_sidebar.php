<?php

/*

type: layout
content_type: static
name: Right sidebar

description: Page with right sidebar

*/
 

?>


<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="container">
    <div class="row-fluid">
      <div class="span7">
        <div class="edit"  field="content" rel="page">
          <h2>New page</h2>
          <p>You can edit this text</p>
        </div>
      </div>
      <div class="span3 offset1">
        <div class="edit"  field="sidebar" rel="inherit">
          <div class="well">
            <h3>Pages</h3>
            <module type="pages" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
