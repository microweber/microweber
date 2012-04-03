<?php

/*

type: layout

name: layout

description: site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $view = url_param('view'); ?>

<div class="direction">
<div class="wrapper cox_body_container">
  <div class="webstore_container" align="center">
    <div class="cox_content">
      <div class="cox_home_shadow"></div>
      <div class="cox_home_tit">Web Store </div>
      <div class="phone_icon"><img src="<? print TEMPLATE_URL ?>images/phone_icon_10.jpg" alt="phone" /></div>
      <div class="phone_text">Order by phone <span class="red">1-866-Instals (467-8257)</span></div>
      <div class="webstore_search">
        <div class="webstore_cat_tit">Categories</div>
        <div class="webstore_search_lable">Search</div>
        <form method="post">
        <input type="text" class="webstore_search_txt" name="keyword" value="<? print url_param('keyword'); ?>" />
        <div class="webstore_search_but">
          <input type="image" src="<? print TEMPLATE_URL ?>images/webstore_search_but.png" />
        </div>
        </form>
      </div>
      <div class="b" style="float:left">
        <div class="bm" style="float:left; width:930px;">
        
        
        <? if($view != 'cart'): ?>
          <div class="webstore_left">
            <div class="webstore_left_links">
              <? category_tree("include_first=false"); ?>
              <!--<ul>
              <li><a href="#">New Products</a></li>
              <li><a href="#">Satellite</a></li>
              <li><a href="#">Cable</a></li>
              <li><a href="#">Installation Supplies</a></li>
              <li><a href="#">Grounding Supplies</a></li>
              <li><a href="#">Alignment Tools</a></li>
              <li><a href="#">Off Air</a></li>
              <li><a href="#">Mounts</a></li>
              <li><a href="#">Television</a></li>
              <li><a href="#">Video Distribution</a></li>
              <li><a href="#">Bargain Bin</a></li>
            </ul>-->
            </div>
            <div class="webstore_cart_box cart_hide_on_complete">
              <div class="webstore_cart_icon"><img src="<? print TEMPLATE_URL ?>images/cart_icon.jpg" alt="cart" /></div>
              <div class="webstore_cart_tit">My Cart</div>
              <mw module="cart/items" view="layouts/shop/small_cart.php" />
            </div>
          </div>
          
          
           <? endif; ?>
          <div class="webstore_rt <? print $view ?>">
            <? if(is_file(TEMPLATE_DIR. "layouts/shop/".$view.'.php')) : ?>
            <? include TEMPLATE_DIR. "layouts/shop/".$view.'.php'; ?>
            <? else : ?>
            <? include TEMPLATE_DIR. "layouts/shop/list.php"; ?>
            <? endif; ?>
          </div>
        </div>
        <div class="bb" style="float:left"></div>
        <div style="width:931px; float:left; margin-top:10px; margin-bottom:40px;"> <a href="https://www.digital-connections.tv//info/affiliates/"> <img alt="" src="<? print TEMPLATE_URL ?>images/ip2.jpg"> </a> </div>
      </div>
    </div>
    <br />
    <br />
  </div>
</div>
<? include TEMPLATE_DIR. "footer.php"; ?>
