
<div id="" class="nopadding">
  <?php
  $the_post = $post;
  $more = false;
 $more = CI::model('core')->getCustomFields('table_content', $the_post['id']);
	$the_post['custom_fields'] = $more;
	?>
  <div class="portlet portlet-closable">
    <div class="portlet-header">
      <h2><?php print $the_post['content_title']; ?></h2>
      <br />
    </div>
    <!-- .portlet-header -->
    <div class="movienight_content">
      <div id="<?php print $the_post['id']; ?>tab1" class="movie_night_content"> <?php print html_entity_decode( $the_post['content_body'] );  ; ?> <br />
        <div id="mn_video" style="display: none"> <? print html_entity_decode ($the_post['custom_fields']['embed_code'], ENT_QUOTES);  ?> </div>
        <script type="text/javascript">

$(document).ready(function(){




       $("#shedule_content_slide").width($('.shedule_item').length*$('.shedule_item:first').outerWidth(true));
       $("#the_slider").hide().removeClass("xhidden");
 




       $(".shedule_item .mw_blue_link").click(function(){
           var embed = '<div class="schedule_pop">' + $(this).parent().find("textarea").val() + '</div>';
           modal.init({
              html:embed,
              width:520,
              height:420
           });
           modal.overlay();
       });

       if($(".shedule_item").length>3){
         $("#schedule_ctrls").show();
       }

       var step = 320;

       $("#schedule_left").click(function(){
        var cur = parseFloat($("#shedule_content_slide").css("left"));
        if(cur<0){
          $("#shedule_content_slide").not(":animated").animate({left:cur+step})
        }
       });
       $("#schedule_right").click(function(){
        var cur = parseFloat($("#shedule_content_slide").css("left"));
        var max = ($("#shedule_content_slide").width()+cur)<=960?true:false;
        if(!max){
           $("#shedule_content_slide").not(":animated").animate({left:cur-step})
        }



       });


});

      </script>
        <div class="c">&nbsp;</div>
        <a href="#" class="btn" onclick="$('#the_slider').toggle();$('html, body').animate({scrollTop:$('#the_slider').offset().top}, 100)">Schedule</a>
        <div id="the_slider" class="xhidden">
          <div id="shedule_content" style="">
            <div id="shedule_content_slide" style="left: 0px;">
              <?
	   $var_params= array();


 $var_params['selected_categories'] =  array(14);
$sched = get_posts($var_params);

	  ?>
              <? foreach($sched as $sch): ?>
              <div class="shedule_item">
                <h2><? print $sch["content_title"]; ?></h2>
                <img src="<? print thumbnail($sch['id'], 350); ?>" alt="" />
                <div class="shedule_item_txt"><? print $sch["the_content_body"]; ?></div>
                <a href="#" class="mw_blue_link">View Trailer</a>
                <div style="height: 10px;">&nbsp;</div>
                <a class="user_activity_likes" href="<? print voting_link($sch['id'], '#post-likes-'.$sch['id']); ?>"><strong id="post-likes-<? print ($sch['id']); ?>"><? print votes_count($sch['id']); ?></strong><span></span><strong >Like</strong></a>
                <div class="shedule_item_embed">
                  <textarea><? print html_entity_decode($sch['custom_fields']["embed_code"]); ?></textarea>
                </div>
              </div>
              <? endforeach; ?>
            </div>
          </div>
          <div id="schedule_ctrls"> <a href="#" id="schedule_left"></a> <a href="#" id="schedule_right"></a> </div>
        </div>
        <br />
        <br />
      </div>
    </div>
    <!-- .portlet-content -->
  </div>
  <!-- .portlet -->
  <? /*
  <h2 class="coment-title">Post your comment</h2>
  <? comment_post_form($the_post['id']); ?>
  <div class="c"> </div>
  <? comments_list($the_post['id'], 'default'); ?>
  <br />
  <br />
*/ ?>
</div>
<!--<div class="inner_video_side">
  <div class="bluebox">
    <div class="blueboxcontent">
      <div class="video_list_item" style="margin: 10px auto;"> <a class="mw_blue_link" href="#">The worst ! ever ! American idol ! FUNNY</a> <a style="background-image: url(&quot;http://pecata/dev.microweber.com/public_html/skide/userfiles/media/pictures/150_150/no.gif&quot;);" class="img" href=""> </a> <a href="javascript:mw.content.Vote('','', '');" class="user_activity_likes left"><strong>0</strong><span></span></a> <a href="" class="user_activity_comments right"><strong id="post-likes-339">1</strong><span></span></a> </div>
      <div class="video_list_item" style="margin: 10px auto;"> <a class="mw_blue_link" href="#">The worst ! ever ! American idol ! FUNNY</a> <a style="background-image: url(&quot;http://pecata/dev.microweber.com/public_html/skide/userfiles/media/pictures/150_150/no.gif&quot;);" class="img" href=""> </a> <a href="javascript:mw.content.Vote('','', '');" class="user_activity_likes left"><strong>0</strong><span></span></a> <a href="" class="user_activity_comments right"><strong id="post-likes-339">1</strong><span></span></a> </div>
    </div>
  </div>
</div>-->
