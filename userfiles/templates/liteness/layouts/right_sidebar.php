<?php

/*

  type: layout
  content_type: static
  name: Right sidebar page
  position: 15
  description: Right sidebar page
  tag:sidebar

*/

?>
<?php include TEMPLATE_DIR . "header.php"; ?>

<div class="container">
    <div class="box-container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-header">
                    <h2 class="edit page-title" field="title" rel="content">Page Title</h2>
                </div>
                <div class="edit" field="content" rel="content">
                    <p class="element">Page content</p>
                </div>
            </div>
            <div class="col-md-3" id="blog-sidebar">
                <div class="edit sidebar" field="page_right_sidebar" rel="inherit">
                    <h4 class="element sidebar-title">Pages</h4>
                    <div class="sidebar-box">
                        <module type="pages"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include TEMPLATE_DIR . "footer.php"; ?>
