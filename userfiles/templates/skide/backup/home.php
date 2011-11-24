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
<a href="#" id="home_parents_reg">New Registration</a>
    <h3>Your child never enters Facebook. </h3>

<p>We use Facebook to validate your identity. Create kid accounts after signing in. </p>

<a href="#" id="home_parents_about">What is Skid-e-Kids?</a>



</div>

 </div>
 </div>

</div><!-- /#slide_engine -->
<div class="wrap">
<br /><br />

<div class="ui_content_2">
    <h1>What parents say </h1>
    <ul class="parent_list">
        <li>
            <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/demo/parent.jpg)"><span></span></a>
            <strong>John and Lisa M. </strong>
            <p>As a parent, and someone in the law enforcement community, I  definitely recommend skidekids to all parents... Your kids would love it. </p>
        </li>
        <li>
            <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/demo/parent.jpg)"><span></span></a>
            <strong>John and Lisa M. </strong>
            <p>As a parent, and someone in the law enforcement community, I  definitely recommend skidekids to all parents... Your kids would love it. </p>
        </li>
        <li>
            <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>static/img/demo/parent.jpg)"><span></span></a>
            <strong>John and Lisa M. </strong>
            <p>As a parent, and someone in the law enforcement community, I  definitely recommend skidekids to all parents... Your kids would love it. </p>
        </li>
    </ul>



</div>
<div id="sidebar" class="ui_bar_2">
    <h1>Skid-e-Kids in the press</h1>
     <br /><br />
    <img src="<?php print TEMPLATE_URL; ?>static/img/demo/logos.jpg" width="320" alt=""  />
    <br /><br />
    <p>Read more about Skid-e-Kids in press coverage and events timeline   </p>
    <a href="#" class="mw_btn_x right"><span>See all</span></a>
</div>

</div>


<?php else: ?>




<?php include (ACTIVE_TEMPLATE_DIR.'home_logged.php') ?>
<?php endif; ?>
