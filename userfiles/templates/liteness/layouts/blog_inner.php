<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="container" id="blog-container">
  <div class="box-container" data-mw="main">
    <div id="blog-content-<?php print CONTENT_ID; ?>">
      <div class="row">
        <div class="col-lg-9" id="blog-main-inner">
          <h3 class="page-title edit" field="title" rel="content">Page Title</h3>
          <div class="edit post-content" field="content" rel="content">
            <module data-type="pictures" data-template="slider"  rel="content"  />
            <div class="edit" field="content_body" rel="content">
              <div class="element">
                <p align="justify">This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.</p>
              </div>
            </div>
          </div>
          <hr />
          <div class="edit" field="post-social-bar" rel="content">
            <div class="blog-socials-bar">
              <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                  <div class="mw-ui-col-container">
                    <module type="facebook_like" show-faces="false" layout="box_count">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="edit" rel="content" field="comments">
            <module data-type="comments" data-template="default" data-content-id="<?php print CONTENT_ID; ?>"  />
          </div>
        </div>
        <div class="col-lg-3" id="blog-sidebar">
          <?php
        if(is_file(TEMPLATE_DIR. "layouts/blog_sidebar.php")){
        	include TEMPLATE_DIR. "layouts/blog_sidebar.php";
        }
        else if(is_file(DEFAULT_TEMPLATE_DIR. "layouts/blog_sidebar.php")){
        	include DEFAULT_TEMPLATE_DIR. "layouts/blog_sidebar.php";
        }
        ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include   TEMPLATE_DIR.  "footer.php"; ?>
