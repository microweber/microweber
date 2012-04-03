<?php

/*

type: layout

name: home layout

description: home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<script type="text/javascript">
$(window).load(function() {
    $('.nivoSlider').nivoSlider({
        effect: 'fade',
		directionNav: false, // Next & Prev navigation
        directionNavHide: true,
		slices: 1, // For slice animations
        boxCols: 1, // For box animations
        boxRows: 1, 
		keyboardNav: false, 
		 controlNav: false
 
    });
});
</script>
<style>
.nivoSlider {
	position:relative;
	width:618px; /* Change this to your images width */
	height:246px; /* Change this to your images height */
}
.nivoSlider img {
	position:absolute;
	top:0px;
	left:0px;
	display:none;
}
.nivoSlider a {
	position:absolute;
	width:618px; /* Change this to your images width */
	height:246px; /* Change this to your images height */
}
</style>
<div class="banner_box">
  <table width="100%" border="0">
    <tr>
      <td><div class="nivoSlider"> <a href="<? print site_url('shopping-center'); ?>"><img src="<? print TEMPLATE_URL ?>images/banner.jpg"  border="0"  /></a>
          <!--    <a href="<? print site_url('forum'); ?>"><img src="<? print TEMPLATE_URL ?>images/1.1.jpg"   border="0"  /></a>
-->
          <a href="<? print site_url('forum'); ?>"><img src="<? print TEMPLATE_URL ?>images/1.2.jpg"  border="0"  /></a>
          <!-- <map name="Map" id="Map">
      <area shape="rect" coords="332,206,630,230" href="#" />
    </map>-->
        </div></td>
      <td><microweber module="users/login" view="blocks/login_box_small.php"  /></td>
    </tr>
  </table>
</div>
<div class="banner_box_bot"></div>
<? require TEMPLATE_DIR. "blocks/category_scroll.php"; ?>
<? require TEMPLATE_DIR. "blocks/product_scroll.php"; ?>
<div class="product_container_bot"></div>
<div class="box3">
<div class="box3_top"></div>
<div class="box3_mid">
 
 
    <div class="box3_about">
     <editable rel="page" field="custom_fields_home_bot">
      <div class="box3_tit">About Us</div>
      <a href="#" class="about_link">About this website</a> <a href="#" class="about_link">Our Mission</a> <a href="#" class="about_link">What You say about us</a> <a href="#" class="about_link">Partners</a>
       </editable>
       </div>
     
      
      
      
   
    <div class="box3_about">
       <editable rel="page" field="custom_fields_home_bot2">
      <div class="box3_tit">Get Sponsored</div>
      <div class="sponsored_logo"><a href="http://www.2studyfoundation.org.uk/" target="_blank"><img src="<? print TEMPLATE_URL ?>images/sponsored_logo.png" alt="sponsored" /></a></div>
      <div class="get_sponsored_bg_img">
        <div class="giving">Giving you the opportunity to <br />
          get <span class="giving_orange">Help2Study</span></div>
        <div class="getmoney_tit"></div>
        <div class="applynow_but"><a href="http://www.2studyfoundation.org.uk/" target="_blank"><img src="<? print TEMPLATE_URL ?>images/applynow_but.png" alt="apply now" border="0" /></a></div>
      </div>
       </editable>
    </div>
    
    
     
      
     
     
    <div class="box3_about" style="margin-right:0px;">
    <editable rel="page" field="custom_fields_home_bot3">
    
      <div class="box3_tit" style="margin-bottom:10px;">The Kewl Stuff</div>
      <div class="stuffbg">
        <div class="stuff_box"> <a href="<? print page_link(3873); ?>" id="stuff_link">Agony Corner</a>
          <div class="askyour">Ask your question</div>
        </div>
        <div class="stuff_box"> <a href="<? print page_link(3499); ?>" id="stuff_link">Shopping Centre</a>
          <div class="cool_stuffbg">Cool stuff for you</div>
        </div>
        <div class="stuff_box"> <a href="<? print page_link(3477); ?>" id="stuff_link">How?</a>
          <div class="userfull_thinksbg">See how it works</div>
        </div>
        <div class="stuff_box"> <a href="<? print page_link(3512); ?>" id="stuff_link">Mentoring</a>
          <div class="findnowbg"> Find help!</div>
        </div>
      </div>
        </editable>
    </div>

</div>
<div class="box3_bot"></div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
