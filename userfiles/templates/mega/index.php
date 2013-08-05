<?php

/*

  type: layout
  content_type: static
  name: Home
  description: Home layout

*/

?>


<?php include "header.php"; ?>



    <div class="container">
        <div id="home-rotator">
          <div id="hrotator">
            <div class="RotatorItem" id="home-rotator-1">
                <img src="<?php print TEMPLATE_URL; ?>peter.png" id="home-rotator-1-peter" class="hidden-phone" alt="" />
                  <div id="home-rotator-1-right">
                      <h1>EVERYTHING IN ONE PLACE <img src="<?php print TEMPLATE_URL; ?>site/free.png" class="mw-free"></h1>
                      <h2><strong>WEBSITE, BLOG  & ONLINE SHOP</strong></h2>
                      <div class="text-center">
                          <a style="width:225px;"
                             href="<?php print mw_site_url(); ?>get-started"
                             title="Get Started"
                             class="fbtn fitem-orange">Get Started</a>
                      </div>
                    <div class="person-title">
                       <h3>Peter Ivanov </h3>
                       <span>CEO & Developer of MW</span>
                    </div>
                    <div id="home-rotator-1-social">
                        <h3>Join us on</h3>
                        <div class="s-social">
                          <a href="http://facebook.com/Microweber" class="box-social" target="_blank"><i class="icon-facebook"></i></a>
                          <a href="http://twitter.com/Microweber" class="box-social" target="_blank"><i class="icon-twitter"></i></a>
                          <a href="javascript:;" class="box-social" target="_blank"><i class="icon-google-plus"></i></a>
                        </div>
                    </div>
                  </div>
            </div>
            <div class="RotatorItem"  style="background-color: #3F9DCC">2</div>
            <div class="RotatorItem"  style="background-color: #7CCCBC">2</div>

          </div>

          <span class="rotator-left" onclick="mw.elementRotator.prev(document.getElementById('hrotator'))"></span>
          <span class="rotator-right" onclick="mw.elementRotator.next(document.getElementById('hrotator'))"></span>

        </div>

    </div>
    <hr style="margin-top: 0;">
    <div class="dots-holder" id="home-rotator-dots"></div>
    <div class="container">
        <div class="row" id="home-features">
            <a href="javascript:;" class="span3 hf-drag-drop" data-index="0"><span></span>Feel Drag &amp; Drop</a>
            <a href="javascript:;" class="span3 hf-create-content" data-index="1"><span></span>Create Content</a>
            <a href="javascript:;" class="span3 hf-sell-more" data-index="2"><span></span>Sell More</a>
            <a href="javascript:;" class="span3 hf-be-mobile" data-index="3"><span></span>Be Mobile</a>
        </div>

        <div id="home-features-rotator">
            <div class="RotatorItem active">
                <div class="row">
                    <div class="span6"><img src="<?php print TEMPLATE_URL; ?>site/home-shop1.jpg" alt="" /> </div>
                    <div class="span6">

                    <h4 class="orange">Sell More</h4>

                    <p>Fackt you probably don’t know is that Monday is the best seller day on Internet. Each begining of the week is like the Black Friday in United States with more then Bilion of dollars sels profit.</p>

                    <p>It is time to join your Business with Microweber and start selling more that you are selling right now.</p>

                    <p>Get started with the default shop theme of MW and add your products. Very soon you will be able to change the styli of your shop with many differen layouts, product page and checkouts.</p>

                      <a href="javascript:;" class="orange pull-right">Continue Reading</a>

                    </div>
                </div>
            </div>
            <div class="RotatorItem">
                2
            </div>
            <div class="RotatorItem">
                3
            </div>
            <div class="RotatorItem">
                4
            </div>
        </div>
</div>

<div id="home-premium-support-info">
<div class="container">

    <h4 align="center">
        We know how important is to have a website. <strong class="weight-400">If you don't know how to do it</strong> <br>
        we will help you to create it with Microweber fast & easy!
    </h4>
    <div class="text-center">

        <div class="tabs tabs-blue" id="tabs-premium-support">
          <a href="javascript:;" class="active" data-index="0">Premium Support</a>
          <a href="javascript:;" data-index="1">CUSTOM Design</a>
          <a href="javascript:;" data-index="2">Custom Development</a>
          <a href="javascript:;" data-index="3">ASK THE Community</a>
        </div>

    </div>


    <div id="premium-support-rotator">


    <script>

      ps_circle_tomorph_activated = false;

      $(document).ready(function(){

           // mw.$(".RotatorItem > *").css("position", "absolute").draggable();

           $(window).bind("scroll", function(e){
              if(!ps_circle_tomorph_activated){
                if(($(window).scrollTop() + $(window).height() - 80) > mw.$("#ps-circle-tomorph").offset().top){
                  ps_circle_tomorph_activated = true;
                  mw.$("#ps-circle-tomorph, #ps-segment").addClass("activated");
                }
              }
           });

           var rot = mwd.getElementById('hrotator');
           mw.elementRotator.init(rot, 'cssFade');
           var dots = mw.elementRotator.dots(rot, "#home-rotator-dots");

           var home_features = mwd.getElementById('home-features-rotator');
           mw.elementRotator.init(home_features, 'cssFade');

           mw.$("#home-features a").bind("mouseenter", function(){
                mw.elementRotator.goto(home_features, parseFloat($(this).dataset("index")));
           });

           var home_tabs = mwd.getElementById('premium-support-rotator');
           mw.elementRotator.init(home_tabs, 'cssSlide');

           home_tabs.navigation =  mw.$("#tabs-premium-support a");

           mw.elementRotator.dots(home_tabs, "#home-bottom-dots");

           mw.$("#tabs-premium-support a").bind("click", function(){
              mw.elementRotator.goto(home_tabs, parseFloat($(this).dataset("index")));
           });



      });
  </script>

        <div class="RotatorItem active">

            <div class="row">
                <div class="span6">
                  <img src="<?php print TEMPLATE_URL; ?>site/alex.png" alt="" class="relative hidden-phone" style="left: 15%" />
                </div>
                <div class="span6">
                    <div class="person-title" style="position: absolute; left: 37%; top: 65px;">
                      <h3>Alexander Raykov</h3>
                      <span>CTO of MW</span>
                    </div>
                    <div id="premium-support-circle">
                        <div class="ps-circle"></div>
                        <div id="ps-segment"></div>
                        <div class="ps-circle" id="ps-circle-tomorph"><div></div><span>$</span> <big>149</big> <small>/ mo</small> only from </div>
                    </div>

                    <div class="home-premium-support-info">

                        <h1 class="text-center blue">Get Premium Support</h1>
                        <p class="text-center edit" rel="content" field="content">
                            Our team of proffesionals will help you online, to<br>
                            create your unique website, blog or online shop.<br>
                            In this plan are included 5 hours of phone and chat <br>
                            support custom design and development.
                        </p>
                        <p class="text-center">
                            <a href="javascript:;" class="fbtn v2 fitem-blue">Get Premium Support</a>
                        </p>
                        <p class="text-center"><a href="javascript:;" class="blue">Ask the community</a></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="RotatorItem">
            <img src="<?php print TEMPLATE_URL; ?>site/home-shop1.jpg" alt="" />
        </div>
        <div class="RotatorItem">
            <img src="<?php print TEMPLATE_URL; ?>site/home-shop1.jpg" alt="" />
        </div>
        <div class="RotatorItem">
            <img src="<?php print TEMPLATE_URL; ?>site/home-shop1.jpg" alt="" />
        </div>
    </div>
  </div>

    <div id="home-footer-get-started">
        <div class="container">
            <div class="dots-holder" id="home-bottom-dots"></div>

            <h2>OK, lets create your first website</h2>
            <a href="javascript:;" class="fbtn fitem-orange">START NOW</a>
        </div>
    </div>
    </div>





<?php include "footer.php"; ?>