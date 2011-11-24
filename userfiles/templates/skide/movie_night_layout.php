<?php

/*

type: layout

name: Movie night layout

description: Movie night site layout









*/



?>

<?php include "header.php" ?>


<!--<embed width="640" height="501" type="application/x-shockwave-flash" allowscriptaccess="always" menu="false" src="http://www.miniclip.com/games/gravity-guy/en/gameloader.swf?mc_gamename=Gravity+Guy&mc_hsname=2577&mc_shockwave=0&mc_scoreistime=0&mc_lowscore=0&mc_negativescore=0&mc_icon=gravityguy2smallicon.jpg&mc_iconBig=gravityguy2medicon.jpg&mc_players_site=1&fn=gravityguy.swf&mc_gameUrl=%2Fgames%2Fgravity-guy%2Fen%2F&mc_sessid=6133481622474850304-878979795-6201714075689287680&mc_v2=1&mc_ua=f7bcad0&mc_geo=NapMia&mc_geoCode=BG&loggedin=0">-->


<script type="text/javascript">
$(document).ready(function(){
  $(".curtain").css({
    height:$("#mn").height()
  });
  $("#before_video").css({
          height:$("#mn").height(),
          width:$("#mn").width()
        });
    $(window).load(function(){
        $("#before_video").css({
          height:$("#mn").height(),
          width:$("#mn").width()
        });
        setTimeout(function(){
            $(".curtain").animate({width:'0%'}, 2000, function(){
              $("#mn_video").show()
               $("#before_video").animate({opacity:'hide'});
            });

            if(!$("#mn_video").hasEmbed()){
                $(".btn.exec").hide();
                $("#the_slider").show();
                $(".shedule_item").css("background", "#000");
            }

        }, 2000);
    });




});

</script>

<div class="relative" id="mn">
  <div class="wrap">
      <div id="main_content">
      
      <?
	   $var_params= array();
 
 
 $var_params['selected_categories'] =  array(13);
$posts = get_posts($var_params);
$post = 	  $posts[0];
	  ?>
      
            <?php if(!empty($post)) : ?>

            <?php include "movie_night_read.php" ?>

            <?php else : ?>

            <?php
 
  		  include "movie_night_read.php"
  		  //include "movie_night_list.php" ?>

            <?php endif; ?>
      </div>
  </div>
  <div id="before_video">

  </div>
  <img class="curtain curtain_left" src="<? print TEMPLATE_URL ?>static/img/curatin_left.jpg" alt="" />
<img class="curtain curtain_right" src="<? print TEMPLATE_URL ?>static/img/curatin_right.jpg" alt="" />
</div>



      <?php include "footer.php" ?>

