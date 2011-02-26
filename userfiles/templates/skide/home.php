

{content}
<?php
  if($user_id == false): ?>

<div id="slide_engie">
  <div class="wrap">
    <div id="slide_engie_content">
      <div class="randomize" id="home_randomizer">
        <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_21.jpg)"></div>
        <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_3.jpg)"></div>
        <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_31.jpg)"></div>
        <div class="randomize_image" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/slide_1.jpg)"></div>
      </div>
      <div id="slide_engine_info">
        <h2>Parents</h2>
        <a href="#" id="home_parents_reg" onclick="modal.ajax({file:'fblogin.php',width:450,height:220});return false;">Facebook registration</a>
        <h3>Your child never enters Facebook. </h3>
        <p>We use Facebook to validate your identity. Create kid accounts after signing in. </p>
        <a href="<?php print site_url('what-is-skid-e-kids') ?>" id="home_parents_about">What is Skid-e-Kids?</a> </div>
    </div>
  </div>
</div>
<!-- /#slide_engine -->
<div class="wrap"> <br />
  <br />
  <div class="ui_content_2">
    <h1>What parents say </h1>
    <microweber module="posts/list" limit="6" category='testimonials'>
   
  </div>
  <div id="sidebar" class="ui_bar_2">
    <h1>Skid-e-Kids in the press</h1>
    <br />
    <br />
    <img src="<?php print TEMPLATE_URL; ?>static/img/logos.jpg" width="320" alt=""  /> <br />
    <br />
    <p>Read more about Skid-e-Kids in press coverage and events timeline </p>
    <a href="<? print category_link(42) ?>" class="mw_btn_x right"><span>See all</span></a>

    <br />
<br />
<br />
<br />
<br />

     <microweber module="users/new" limit="15">
    </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

if($.cookie("show_video")!="no"){
  var embed = '<embed width="250" height="313" align="left" loop="true" wmode="transparent" quality="high" src="<? print TEMPLATE_URL ?>static/flash/briana.swf" type="application/x-shockwave-flash"></embed>';
  $("#briana").html(embed);
  $.cookie("show_video", "no", {expires:7});
}


});

</script>




<div id="briana">



</div>











<?php else: ?>

<?php include (ACTIVE_TEMPLATE_DIR.'home_logged.php') ?>

<?php endif; ?>
