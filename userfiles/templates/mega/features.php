<?php

/*

  type: layout
  content_type: static
  name: Features
  description: Features layout

*/

?>
<?php include "header.php"; ?>
    <div class="container">
        <div id="features-nav">
            <span><a href="javascript:;" class="f-overview"><span></span>Overview</a></span>
            <span><a href="javascript:;" class="f-dragndrop"><span></span>Drag & Drop</a></span>
            <span><a href="javascript:;" class="f-content"><span></span>Content</a></span>
            <span><a href="javascript:;" class="f-shop"><span></span>Shop</a></span>
            <span><a href="javascript:;" class="f-modules"><span></span>Modules</a></span>
            <span><a href="javascript:;" class="f-layouts"><span></span>Layouts</a></span>
            <span><a href="javascript:;" class="f-mobile"><span></span>Mobile</a></span>
            <span><a href="javascript:;" class="f-design"><span></span>Design</a></span>
        </div>
    </div>
    <div class="gray-container" style="padding: 90px 0;">
      <div class="container">
        <div style="height: 440px;background: #307EB1 url(<?php print( TEMPLATE_URL); ?>img/logo.png) no-repeat center;">
              <style type="text/css">
                 #wistia_abcde12345 div, #wistia_abcde12345 {
                   width: auto !important;
                 }
              </style>
              <div id="wistia_abcde12345" data-autoresize="none" style="height:440px;width:100%" data-video-width="100%" data-video-height="440"> </div>
              <script src="http://fast.wistia.net/static/E-v1.js"></script>
              <script src="http://fast.wistia.net/static/concat/E-v1-gridify,socialbar-v1.js"></script>
              <script>
                  wistiaEmbed = Wistia.embed("1be6jcecsf", {
                      playerColor: "2B6F9C",
                      fullscreenButton: true,
                      container: "wistia_abcde12345",
                      playButton:false,
                      platformPreference:'html5',
                      controlsVisibleOnLoad:true
                  });
                  $(window).load(function(){
                     var poster = document.getElementById('wistia_1_poster');
                     if(poster !== null ){
                       poster.style.display = 'none';
                     }
                  });
              </script>
            </div>
            <br><br>
            <p>
                It is not needed to have experience as a developer or design to have professional website, online shop or blog and to sell online.
                The best way to edit your website is by using drag & drop functionality.
            </p>
            <p>Write text directly as you see it. Much more, you can reorder all of the elements and modules of your website. You can edit the elements or change the skin of your modules like gallery or contact form. All depends on you.</p>
      </div>
    </div>

    <div class="container" style="padding:60px 0 20px; ">
        <div class="row">
            <div class="span6">
                <h2 class="orange"><i class="ico-content"></i> Create New Content </h2>
                <img src="<?php print TEMPLATE_URL; ?>features/create_content.jpg" alt="" />
            </div>
            <div class="span6" style="padding-top: 65px;">
                <p> It is not needed to have experience as a developer or design to have professional website, online shop or blog and to sell online. <br>
                The best way to edit your website is by using drag & drop functionality.</p>

                <p>Write text directly as you see it.</p>

                <p>Much more, you can reorder all of the elements and modules of your website. You can edit the elements or change the skin of your modules like gallery or contact form. All depends on you. </p>

                <div class="text-center"><a href="javascript:;" class="fbtn fitem-orange fbtn-small" style="width: 110px;">Read More</a></div>

            </div>
        </div>
    </div>
    <hr>
    <div class="container">




    <div class="row">
            <div class="span6">
                <h2 class="orange">Online Shop</h2>
                <img src="<?php print TEMPLATE_URL; ?>features/shop.jpg" alt="" />
            </div>
            <div class="span6" style="padding-top: 60px;">
                <h3>The Clients Makes Orders </h3>
                <p>Fackt you probably don’t know is that each Monday is the best seller day on Internet. Each begining of the week is like the Black Friday in United States with more then Bilion of dollars sels profit.</p>
                <p>It is time to join your Business with Microweber and start selling more that you are selling right now.</p>
                <p>Get started with the default shop theme of MW and add your products. Very soon you will be able to change the styli of your shop with many differen layouts, product page and checkouts.</p>
            </div>
        </div>



    </div>




    <br><br><br><br><br><br><br><br><br><br><br><br>



<?php include "footer.php"; ?>