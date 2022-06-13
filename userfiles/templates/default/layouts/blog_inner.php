<?php include THIS_TEMPLATE_DIR. "header.php"; ?>
<div class="container" id="blog-container">
	<div  id="blog-content-<?php print CONTENT_ID; ?>">
		<div class="row">
			<div class="col-sm-8" id="blog-main-inner">
				<h3 class="edit" field="title" rel="module"><?php _e('Page Title'); ?></h3>
				<div class="edit post-content" field="content" rel="content">
                    <module data-type="pictures" data-template="slider"  rel="module"  />

                    <div class="edit"  field="content_body" rel="content">
					<div class="element" style="width:95%">

						<p align="justify"><?php _e('This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.'); ?></p>


					</div>
                    </div>

				</div>
                <div class="edit" rel="module" field="comments"><module data-type="comments" data-template="default" data-content-id="<?php print CONTENT_ID; ?>"  /></div>
			</div>
			<div class="col-sm-3 col-sm-offset-1" id="blog-sidebar">
				<?php
                      if(is_file(THIS_TEMPLATE_DIR. "layouts/blog_sidebar.php")){
                        include THIS_TEMPLATE_DIR. "layouts/blog_sidebar.php";
                      }
                      else if(is_file(DEFAULT_TEMPLATE_DIR. "layouts/blog_sidebar.php")){
                      	include DEFAULT_TEMPLATE_DIR. "layouts/blog_sidebar.php";
                      }
                ?>
			</div>
		</div>
	</div>
</div>
<?php include   TEMPLATE_DIR.  "footer.php"; ?>
