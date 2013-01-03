<?php

/*

type: layout
content_type: dynamic
name: Homepage layout

description: Home layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

 <div id="rotator">
        <div id="rotator-wrapper">

          <module
            type="pictures"
            id="home-rotator"
          />


        </div><!-- /#rotator -->

      </div><!-- /#rotator-wrapper -->

      <script type="text/javascript">
        $(function(){


            Rotator = mw.iRotor('#home-rotator');

            if(!Rotator) return false;

            Rotator.controlls({
                paging:true,
                next:true,
                prev:true
            })

            Rotator.autoRotate(3000);


        });
      </script>

      <div id="main-content">
          <br /><br /><br />

            <div class="edit"  id="showcase" rel="page"></div>

      </div><!-- /#main-content -->
      
      
      
      



<? include TEMPLATE_DIR. "footer.php"; ?>
