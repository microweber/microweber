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


            Rotator = mw.slider('#home-rotator')

            .controlls({
                paging:true,
                next:true,
                prev:true
            })

            .autoRotate(3000);


        });
      </script>

      <div id="main-content">
          <br /><br /><br />

            <div class="edit"  id="showcase" rel="page"></div>

      </div><!-- /#main-content -->
      
      
      
      



<? include TEMPLATE_DIR. "footer.php"; ?>
