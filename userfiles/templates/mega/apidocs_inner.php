<?php

/*

  type: layout
  content_type: static
  name: Api Inner Docs
  description: Api Inner Docs layout

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
                      <module type="categories" template="docs" rel_id="42" />
                  </div>
                  <div class="span9">
                    <div class="bbox">
                      <div class="bbox-content">
                         <h2 class="blue" field="title" rel="post"><?php print $content['title']; ?></h2>
                         <hr>
                        <div class="edit" rel="content" field="content"></div>

                      </div>
                    </div>
                  </div>
              </div>
        </div>




    </div>
 <?php include "footer.php"; ?>
