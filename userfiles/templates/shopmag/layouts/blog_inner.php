<?php include TEMPLATE_DIR. "header.php"; ?>

    <div class="mw-wrapper content-holder">
           <div class="mw-ui-row">
               <div class="mw-ui-col">
                 <div class="mw-ui-col-container">
                   <div class="item-box pad">
                        <h3 class="page-title edit" field="title" rel="content">Page Title</h3>
                        <div class="edit post-content richtext" field="content" rel="content">
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
                 </div>
               </div>
               <div class="mw-ui-col" style="width: 30%">
                 <div class="mw-ui-col-container blog-sidebar">
                   <div class="item-box pad2">
                       <?php include "sidebar_blog.php"; ?>
                   </div>
                 </div>
               </div>
           </div>

   </div>

<?php include   TEMPLATE_DIR.  "footer.php"; ?>
