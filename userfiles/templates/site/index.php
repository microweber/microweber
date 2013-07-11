<?php

/*

  type: layout
  content_type: dynamic
  name: Home
  description: Home layout

*/

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>
<script>mwd.body.className += ' home';</script>

<div class="gray-container">
    <div class="container">

          <h2 class="w-title">Make Web by Drag &amp; Drop with Microweber</h2>


          <div id="home-video">

          </div>

          <h3 class="z-title">Start with your own <strong>website</strong>, <strong>blog</strong> or <strong>online shop</strong> for <strong>free</strong>.</h3>

          <div id="get-started">
          <div id="get-started-btns">

            <a href="javascript:;" class="obtn obtn-orange" style="overflow: hidden;" onclick="mw.site.show_subscribe();">Get Started Free<span id="cm">Comming Soon</span></a>

            <span class="or">or</span>

            <a href="javascript:;" class="obtn obtn-blue" id="download">Download Beta</a>

            <a href="<?php print site_url('download.php?webinstall=1') ?>" id="download-zip">Web Install<small> (8kb)</small></a>

            <div id="download_modal" class="hide">


                <div class="download_license">

                <?php include "license/license.php"; ?>



                </div>


           </div>



          </div>


          <div id="subscribe_form">
                <div class="subscribe clearfix">
                    <form target="_blank" class="visible-desktop visible-tablet main-subscribe" action="http://microweber.us5.list-manage.com/subscribe/post?u=7f8e2fe375e0b8dd295c13503&id=4ba9fc5952" method="post" name="mc-embedded-subscribe-form">
                      <input class="input-xlarge pull-left" type="email" name="EMAIL" required placeholder="Enter your email">
                      <input type="submit" class="btn btn-large pull-right" value="Subscribe" />
                    </form>

                    <form target="_blank" class="visible-phone form-search" action="http://microweber.us5.list-manage.com/subscribe/post?u=7f8e2fe375e0b8dd295c13503&id=4ba9fc5952" method="post" name="mc-embedded-subscribe-form">
                        <div class="input-append">
                        <input class="" type="email" name="EMAIL" required placeholder="Enter your email">
                        <input type="submit" class="btn " value="Subscribe" />
                      </div>
                    </form>
                </div>
            </div>

    </div>
</div>
</div>
 <div class="container">

<div id="like-box">
    <iframe
    src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMicroweber&amp;send=false&amp;layout=standard&amp;width=500&amp;show_faces=true&amp;font=tahoma&amp;colorscheme=light&amp;action=like&amp;height=61"
    scrolling="no"
    frameborder="0"
    style="border:none; overflow:hidden; width:500px; height:61px;max-width: 100%"
    allowTransparency="true">
</iframe></div>



<h2 class="z-title" style="padding-top: 0;" id="how-to-use">How to use?</h2>

<h3 class="w-title" style="padding-top: 0;padding-bottom: 30px;">Watch the videos below. We are comming very soon with many tutorials, lessons and documentation. </h3>


<div class="row">

    <div class="span4">
        <div class="h-video" onclick="mw.site.modal_video('eajCiD0ha2s', 'Quick Demo of Microweber')">
            <div class="h-video-shot">
              <img src="http://img.youtube.com/vi/eajCiD0ha2s/0.jpg" alt="" />
             
            </div>
            <div class="h-video-title">Quick Demo of Microweber</div>
        </div>
    </div>
    <div class="span4">
        <div class="h-video" onclick="mw.site.modal_video('iNRAh96YEwY', 'Drag &amp; Drop the Modules')">
            <div class="h-video-shot">
                 <img src="http://img.youtube.com/vi/iNRAh96YEwY/0.jpg" alt="" />
            </div>
            <div class="h-video-title">Drag &amp; Drop the Modules</div>
        </div>
    </div>
    <div class="span4">
       
 <div class="h-video" onclick="mw.site.modal_video('cOZhe_WtnWM', 'How to make Online Shop')">
            <div class="h-video-shot">
                <img src="http://img.youtube.com/vi/cOZhe_WtnWM/0.jpg" alt="" />
            </div>
            <div class="h-video-title">How to make Online Shop</div>
        </div>
    </div>


</div>

<h2 class="z-title"  id="contact-us">Did you tried Microweber already?</h2>
    <br>

 <div class="row">

    <div class="span6">
        <module type="contact_form">
    </div>

    <div class="span6" >
    <div class="well">
    
       <h3 style="font-size: 19px">We are working on our website right now.</h3>
               <h4 style="font-size: 18px">Expect more very soon! </h4>


        <h4 style="font-size: 17px">Write us a message if you have any suggestion or problems. </h4>

        </div>
    </div>

</div>


</div>






<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
