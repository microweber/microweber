<?php

/*

  type: layout
  content_type: static
  name: Api Docs
  description: Api Docs layout

*/

?>









 <?php include "header.php"; ?>
  <div class="container">
        <div class="main">
              <h5>Microweber API Documentation</h5>
              <hr>
              <div class="row">
                  <div class="span3">
                      <module type="search" template="autocomplete_mwcom">
                      <i class="vspace"></i>
                      <h6>Sections</h6>
                      <hr>
					  
                      <module type="categories" template="docs" rel_id="<?php print $page_id; ?>" />
                  </div>
                  <div class="span9">
                    <module type="posts" template="apidocs" data-page-id="<?php print $page_id; ?>">
                  </div>
              </div>
        </div>
  </div>


 <?php include "footer.php"; ?>
