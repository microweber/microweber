<?php

/*

  type: layout
  content_type: static
  name: Home
  description: Home layout

*/

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="container">
    <div id="mw-team">
      <div class="mw-member" id="mw-team-activator"><span class="mw-member-image"><img src="<?php print( TEMPLATE_URL); ?>img/team/alex.jpg" alt="" /></span>
        <div class="box"><i class="box-arr-leftcenter"></i>
          <div class="box-content">Microweber is free & easy drag and drop content managament system (CMS) of new generation.</div>
        </div>
      </div>
      <div class="mw-member mwmember active" style="display: none"> <span class="mw-member-image"><img src="<?php print( TEMPLATE_URL); ?>img/team/alex.jpg" alt="" /></span></div>
      <div class="mw-member mwmember"> <span class="mw-member-image"><img src="<?php print( TEMPLATE_URL); ?>img/team/peter.jpg" alt="" /></span></div>
      <div class="mw-member mwmember"> <span class="mw-member-image"><img src="<?php print( TEMPLATE_URL); ?>img/team/boris.jpg" alt="" /></span></div>
      <script type="text/javascript">
         _team = [
           {
            "text" : "Microweber is free & easy drag and drop content managament system (CMS) of new generation.",
            "img" : "<?php print( TEMPLATE_URL); ?>img/team/alex.jpg"
          },
           {
            "text" : "PHP",
            "img" : "<?php print( TEMPLATE_URL); ?>img/team/peter.jpg"
          },
          {
            "text" : "Design",
            "img" : "<?php print( TEMPLATE_URL); ?>img/team/boris.jpg"
          }
        ]
        $(document).ready(function(){
          team.init()
        });
      </script>

      </div>


    <br>
    <br>
    <br>
    <div class="row">
      <div class="span4">
        <div id="h-features">
          <div class="box" id="how-it-works">
            <div class="box-content">
              <h3>How it works? </h3>
              <p>The easiest CMS in the Internet! Make Web by drag & drop as you always wanted!</p>
            </div>
            <i class="box-arr-rightcenter"></i> </div>
          <div class="box box-deactivated" id="shop-for-free">
            <div class="box-content">
              <h3>Shop for Free? </h3>
              <p>Sure! Create your online shop right now!</p>
            </div>
            <i class="box-arr-rightcenter"></i> </div>
          <div class="box box-deactivated" id="need-a-blog">
            <div class="box-content">
              <h3>Need a Blog? </h3>
              <p>Alright! Your professional Blog for free Get it!</p>
            </div>
            <i class="box-arr-rightcenter"></i> </div>
          <a href="javascript:;" tabindex="1" class="start" title="Get Started">Get Started</a> </div>
        <div class="vspace"></div>
      </div>
      <div class="span8">
        <div class="box" style="width: 100%;height: 370px;">
          <div class="box-content" >
            <div style="height: 350px;background: #307EB1 url(<?php print( TEMPLATE_URL); ?>img/logo.png) no-repeat center;"> 
              <!--
                              <video controls=""  name="media" style="height: 340px;width: 95%; margin:auto; display: block">
                                <source src="http://r18---sn-nv47en7k.c.youtube.com/videoplayback?cp=U0hVTllNVF9GUUNONV9RR1hDOklJTm9sbmRQYVM1&expire=1369926217&fexp=927604%2C901439%2C929400%2C916625%2C900352%2C924605%2C928201%2C901208%2C929123%2C929915%2C929906%2C925714%2C929919%2C929119%2C931202%2C932802%2C928017%2C912518%2C911416%2C906906%2C904476%2C904830%2C930807%2C919373%2C906836%2C933701%2C912711%2C929606%2C910075&id=79a8c2883d216b6b&ip=78.90.67.20&ipbits=8&itag=22&key=yt1&ms=nxu&mt=1369902794&mv=m&newshard=yes&ratebypass=yes&signature=6D706C9D984EC20158D0BE82E7EDD40D8FCE762C.9A6CABEF8FB5F1CDF242F9566E09FCDCE1F1BAC3&source=youtube&sparams=cp%2Cid%2Cip%2Cipbits%2Citag%2Cratebypass%2Csource%2Cupn%2Cexpire&sver=3&upn=0y9j5jDoUDE&cpn=QGSIJGkgp9mpeEG1&ptk=musicshake&begin=8440&redirect_counter=1&cms_redirect=yes" type="audio/mpeg">
                              </video>     --> 
              
              <!--       <iframe
                                src="http://player.vimeo.com/video/67285302"
                                width="100%"
                                height="350"
                                frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>      --> 

              <!--<iframe
                                     style="margin:auto;display: block;"
                                     src="http://fast.wistia.net/embed/medias/1be6jcecsf?wmode=transparent&playerColor=2B6F9C&controlsVisibleOnLoad=true&playButton=false"
                                     allowtransparency="true"
                                     frameborder="0"
                                     width="100%"
                                     height="350px"
                                     scrolling="no"></iframe>-->
              
              <div id="wistia_abcde12345" style="height:350px;width:100%" data-video-width="100%" data-video-height="350"> </div>
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

                                      //wistiaEmbed.style.display = 'none';
                                      </script> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
