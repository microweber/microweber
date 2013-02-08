<?php

/*

type: layout
content_type: dynamic
name: Homepage layout

description: Home layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>
<section id="content">
  <div class="container">
    <!-- welcome text -->
    <div class="row">
      <!-------------- row 1 -------------->
      <div class="span8">
        <div class="edit"  field="content" rel="content">



          <div class="element">
            <h2>get</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">Link</a></p>
          </div>




        </div>
      </div>



      <!------------ Sidebar -------------->
      <div class="span4">
        <div class="edit"  field="right_sidebar" rel="inherit">
          <h2 class="indent-2 p3">Sidebar</h2>
        </div>
      </div>
    </div>
  </div>
</section>


<? include TEMPLATE_DIR. "footer.php"; ?>
