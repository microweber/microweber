<?php

/*

type: layout

name: home layout

description: home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="banner_box">
  <div class="banner"><img src="<? print TEMPLATE_URL ?>images/banner.jpg" alt="banner" border="0" usemap="#Map" />
    <map name="Map" id="Map">
      <area shape="rect" coords="332,206,630,230" href="#" />
    </map>
  </div>
  
  
  
 
     
    
       <microweber module="users/login" view="blocks/login_box_small.php"  />

  

     
    
    
 
  
  
</div>
<div class="banner_box_bot"></div>
<? require TEMPLATE_DIR. "blocks/category_scroll.php"; ?>
<div class="products_container">
  <div class="prod_all_container">
    <div class="prodbox">
      <div class="prod_bg"><img src="<? print TEMPLATE_URL ?>images/prod_img_88.png" alt="product" /></div>
      <div class="prod_text">See products from Nike</div>
    </div>
    <div class="prodbox">
      <div class="prod_bg"><img src="<? print TEMPLATE_URL ?>images/prod_img_91.png" alt="product" width="200" height="120" /></div>
      <div class="prod_text">See products from Adidas</div>
    </div>
    <div class="prodbox"> 
      <div class="prod_bg"><img src="<? print TEMPLATE_URL ?>images/prod_img_93.png" alt="product" width="200" height="120" /></div>
      <div class="prod_text">See products from Apple</div>
    </div>
    <div class="prodbox">
      <div class="prod_bg"><img src="<? print TEMPLATE_URL ?>images/prod_img_95.png" alt="product" width="200" height="120" /></div>
      <div class="prod_text">See products from Apple</div>
    </div>
  </div>
  <a href="#" id="selectall">See All from Shopping Center</a> </div>
<div class="product_container_bot"></div>
<div class="box3">
<div class="box3_top"></div>
<div class="box3_mid">
  <div class="box3_about">
    <div class="box3_tit">About Us</div>
    <a href="#" id="about_link">About this website</a> <a href="#" id="about_link">Our Mission</a> <a href="#" id="about_link">What You say about us</a> <a href="#" id="about_link">Partners</a> </div>
  <div class="box3_about">
    <div class="box3_tit">Get Sponsored</div>
    <div class="sponsored_logo"><a href="http://www.2studyfoundation.org.uk/" target="_blank"><img src="<? print TEMPLATE_URL ?>images/sponsored_logo.png" alt="sponsored" /></a></div>
    <div class="get_sponsored_bg_img">
      <div class="giving">Giving you the opportunity to <br />
        get <span class="giving_orange">Help2Study</span></div>
      <div class="getmoney_tit"></div>
      <div class="applynow_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/applynow_but.png" alt="apply now" border="0" /></a></div>
    </div>
  </div>
  <div class="box3_about" style="margin-right:0px;">
    <div class="box3_tit" style="margin-bottom:10px;">The Kewl Stuff</div>
    <div class="stuffbg">
      <div class="stuff_box"> <a href="#" id="stuff_link">Agony Corner</a>
        <div class="askyour">Ask your question</div>
      </div>
      <div class="stuff_box"> <a href="#" id="stuff_link">Shopping Center</a>
        <div class="cool_stuffbg">Cool stuff for you</div>
      </div>
      <div class="stuff_box"> <a href="#" id="stuff_link">Content</a>
        <div class="userfull_thinksbg">Usefull thinks to know</div>
      </div>
      <div class="stuff_box"> <a href="#" id="stuff_link">Mentoring</a>
        <div class="findnowbg"> Find Now!</div>
      </div>
    </div>
  </div>
</div>
<div class="box3_bot"></div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
