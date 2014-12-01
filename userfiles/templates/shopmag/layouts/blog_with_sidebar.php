<?php

/*

  type: layout
  content_type: dynamic
  name: Blog with sidebar
  position: 5
  description: Blog with sidebar
  tag: blog

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="mw-wrapper content-holder">
    <div class="mw-ui-row">
         <div class="mw-ui-col">
             <div class="mw-ui-col-container">
                  <div class="edit" rel="page" field="content">
                      <module content-id="<?php print PAGE_ID; ?>" type="posts" template="clean" />
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

<?php include TEMPLATE_DIR. "footer.php"; ?>

