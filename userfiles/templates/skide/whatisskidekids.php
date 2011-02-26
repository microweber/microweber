<script type="text/javascript">


$(document).ready(function(){
       $("#shedule_content_slide").width($('.shedule_item').length*$('.shedule_item:first').outerWidth(true));
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

       if($(".shedule_item").length>3){
         $("#schedule_ctrls").show();
       }

       $(".popschedule").click(function(){
          modal.init({
            html:document.getElementById('the_slider'),
            width:1000,
            height:$('#shedule_content').outerHeight(),
            oninit:function(){
               $("#shedule_content_slide").width($('.shedule_item').length*$('.shedule_item:first').outerWidth(true));
            }
          });
          modal.overlay();

       });


});


</script>


<div id="slide_engie">
 <div class="wrap">
   <div id="slide_engie_content">
  <div class="randomize" id="home_randomizer">


<? /*
    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/a_1.jpg)"></div>
    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_2.jpg)"></div>
    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_11.jpg)"></div>
*/ ?>

    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/what_1.jpg)"></div>
    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/what_2.jpg)"></div>
    <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/what_3.jpg)"></div>


  </div>

  <div id="aboutSEK">
    <div id="about_video">

    </div>
    <div class="whatisbox">
        <h2 style="padding-bottom: 0">Why Skid-e-kids?</h2>
        <? /*
        <a href="#" onclick="modal.ajax({file:'fblogin.php',width:450,height:220});" class="whatis_fb_login">Facebook Acoount</a>
        */ ?>

<ul>
  <li>Your kid is in a safe and friendly environment</li>
  <li>You are always in control and you can track your kids’s activity</li>
  <li>We have plenty of games so your kid can have a lot of fun</li>
  <li>We have many fun and engaging educational materials</li>
  <li>We love what we do and we continuously improve this site</li>
</ul>

        <a class="right mw_btn_s" href="<? print site_url("users/user_action:register") ?>"><span>Register</span></a>
        <p style="padding: 0 0 3px 21px;"><a href="<? print site_url("blog/categories:38") ?>" class="mw_blue_link">What the parents think?</a></p>
        <p style="padding: 3px 0 0 21px;"><a href="<? print site_url("blog/categories:42") ?>" class="mw_blue_link">What the media think?</a> </p>
    </div>
  </div>

 </div>
 </div><!-- /#slide_engine -->
 <div class="c" style="padding-bottom: 40px;">&nbsp;</div>
   <div class="wrap">
   <div class="tabs whatis_tabs left" style="width: 574px">
    <ul class="tabnav">
      <li><a href="#tab-WhatisSkideKides">What is Skid-e-Kides</a></li>
      <li><a href="#tab-MovieNights">Movie Nights</a></li>
      <li><a href="#tab-Trackyourkid">Track your kid</a></li>
    </ul>
    <div class="c">&nbsp;</div>
    <div class="tab" id="tab-WhatisSkideKides">
        <div class="richtext">
            <h3>Skidekids is a social networking alternative for kids ages 7 to 14</h3>

            <p>It is very safe, fun and educational. <br />
            It is strictly designed to give your children the excitement of being on Facebook, without exposing them to all the negative things that comes with an open social network like Facebook and others.<br />
            On Skid-e-kids, the parents are in charge, they have special features that allow them to instantly view all the friends and activities of their child.
            </p>
            <p>Kids can play Video Games  <br />
            They can watch full age-appropriate blockbuster movies. <br />
            They can invite and socialize with friends and classmates   <br />
            They can swap/trade, sell toys and video games.    <br />
            Most importantly, they can get help with school homework on any subject, by simply posting the question to get answers.</p>

            <p>Skid-e-kids is the only social network that is truly committed to not only keeping our children safe, but also making sure that they are systematically learning while they are having fun.</p>
      </div>
    </div>
    <div class="tab" id="tab-MovieNights">
     <h3 style="margin: 0.83em 0;">Movie nights</h3>

     <img style="float: left;margin-right: 7px;" src="<?php print TEMPLATE_URL; ?>static/img/what_mn.jpg" alt=""  />

     <p style="padding-bottom: 10px;">Perfect movies for kids and grown-ups alike,
you'll find stories here touching on the friendships,
fears, and adventures of children
(and adults young at heart!)</p>

<a href="#" class="mw_btn_s popschedule"><span>Schedule</span></a>




<div id="the_slider">
      <div id="shedule_content" style="padding-top: 0">
       <div id="shedule_content_slide" style="left: 0px;">
        <?
	   $var_params= array();


 $var_params['selected_categories'] =  array(14);
$sched = get_posts($var_params);

	  ?>

      <? foreach($sched as $sch): ?>






      <div class="shedule_item" style="background-color: #485462">
        <h2><? print $sch["content_title"]; ?></h2>
        <img src="<? print thumbnail($sch['id'], 250); ?>" alt="" />
        <div class="shedule_item_txt"><? print $sch["the_content_body"]; ?></div>
        <a href="#" class="mw_blue_link">View Trailer</a>
        <div class="shedule_item_embed">
         <textarea><? print html_entity_decode($sch['custom_fields']["embed_code"]); ?></textarea>
        </div>

      </div>




      <? endforeach; ?>
      </div>
      </div>
      <div id="schedule_ctrls" >
        <a href="#" id="schedule_left"></a>
        <a href="#" id="schedule_right"></a>
      </div>

      </div>












    </div>
    <div class="tab" id="tab-Trackyourkid">

      <h3 style="margin: 0.83em 0;">Tack your kid</h3>

    <img style="float: left;margin-right: 7px;" src="<?php print TEMPLATE_URL; ?>static/img/what_track.jpg" alt=""  />

<p style="padding-bottom: 10px;">On Skidekids, the parents are in charge,
they have special features that allows
them to instantly view all the friends
and activities of their child.</p>

<p>By the easy dashboard switch you can
track your kid’s activity in real time.
Check all the tols inside</p>

    </div>
  </div>
  <div id="whatis_members">



  <microweber module="users/new" dashboard_user="<? mw_var($dashboard_user) ?>" limit="15" list_class="user_friends_list_wide">

  <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
  <a href="<? print site_url('users/user_action:register') ?>" class="mw_btn_x right"><span>Register Now</span></a>
 <? /*
 <strong class="right" style="color: #0671AF;margin-top: 10px;"><big>2,958</big> Members&nbsp;</strong>
 */ ?>

  </div>
 </div>
 </div>
 <br /><br /><br />

