<?php

/*

type: layout

name: layout

description: site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="cox_body_container">
  <div class="cox_container" align="center">
    <div class="cox_content">
      <div class="cox_home_shadow"></div>
      <div class="cox_home_tit">Browse for Top Bundles</div>
      <div class="phone_icon"><img src="<? print TEMPLATE_URL ?>images/phone_icon_10.jpg" alt="phone" /></div>
      <div class="phone_text">Order by phone <span class="red">1-866-Instals (467-8257)</span></div>
      <div class="cox_home_nav">
        <ul>
   
         <li class="current"><a href="javascript:see_all_offers();">Top Bundles</a></li>
          <li><a href="javascript:see_only_offers('.has_tv');">TV</a></li>
          <li><a href="javascript:see_only_offers('.has_net');">Internet</a></li>
          <li><a href="javascript:see_only_offers('.has_phone');">Phone</a></li>
<!--          <li><a href="special_offers.html">Special Offers</a></li> 
-->        </ul>
      </div>
      <div class="b" style="float:left">
        <div class="bm" style="float:left">
          <div class="cox_offers_subhead">Please select from our most popular Cox offers for Hampton.</div>
          <div class="cox_offers_sub_tit">Show bundles for: </div>
          <div class="tvicon_set">
            <div class="tv-iocn"><img src="<? print TEMPLATE_URL ?>images/tv_icon.jpg" alt="tv icon" /></div>
            <div class="tvchkbut">
              <input type="checkbox" class="offer_ctrl_select_boxes" value=".has_tv" onchange="see_only_offers()" />
            </div>
            <div class="tv_lable">TV</div>
          </div>
          <div class="tv_plus">+</div>
          <div class="internet_icon_set">
            <div class="internet-icon"><img src="<? print TEMPLATE_URL ?>images/internet_icon.jpg" alt="tv icon" width="44" height="44" /></div>
            <div class="internetchkbut">
              <input type="checkbox" class="offer_ctrl_select_boxes" value=".has_net" onchange="see_only_offers()"  />
            </div>
            <div class="internet_lable">INTERNET<br />
            </div>
          </div>
          <div class="tv_plus">+</div>
          <div class="internet_icon_set">
            <div class="internet-icon"><img src="<? print TEMPLATE_URL ?>images/phone_icon.jpg" alt="tv icon" width="44" height="44" /></div>
            <div class="phonechkbut">
              <input type="checkbox" class="offer_ctrl_select_boxes" value=".has_phone" onchange="see_only_offers()"  />
            </div>
            <div class="phone_lable">PHONE<br />
            </div>
          </div>
          <div class="show_bundles_but"><a href="javascript:see_all_offers()"><img src="<? print TEMPLATE_URL ?>images/shoebundles_but.jpg" alt="show bundles" border="0" /></a></div>
          <? include("offer1.php"); ?>
          <? include("offer2.php"); ?>
          <? include("offer3.php"); ?>
          <? include("offer4.php"); ?>
          <? include("offer5.php"); ?>
          <? include("offer6.php"); ?>
          <? include("offer7.php"); ?>
          <? include("offer8.php"); ?>
          <? include("offer9.php"); ?>
          <? include("offer10.php"); ?>
          <div class="select_best_plan_tit">Select your best plan, send us a request and we will contact you today</div>
          <div class="c">&nbsp;</div>
          <div id="theform" style="margin-left:20px;"> <br>
            <br>
            <form method="post" action="#" class="TheForm" id="request_1">
              <input name="offer_name" id="offer_name" type="text">
              <h3 style="text-align:left; padding-left:5px;">Names and address</h3>
              <br>
              <span class="field"> <span>
              <input value="First Name" class="required" name="first_name" default="First Name" type="text">
              </span> </span> <span class="field"> <span>
              <input value="Last Name" class="required" name="last_name" default="Last Name" type="text">
              </span> </span> <span class="field"> <span>
              <input value="Street Address" class="required" name="street_address" default="Street Address" type="text">
              </span> </span> <span class="field"> <span>
              <input value="Zip/Postal Code" class="required" name="zip_postal_code" default="Zip/Postal Code" type="text">
              </span> </span> <span class="field"> <span>
              <input value="Home phone" class="required" name="home_phone" default="Home phone" type="text">
              </span> </span> <span class="field"> <span>
              <input value="Mobile phone" class="required" name="mobile_phone" default="Mobile phone" type="text">
              </span> </span> <span class="field"> <span>
              <input value="E-mail" class="required" name="email" default="E-mail" type="text">
              </span> </span> <span class="field" style="margin-bottom: 0"> <span>
              <input value="Confirm E-mail " class="required" name="confirm_email" default="Confirm E-mail " type="text">
              </span> </span>
              <div class="c" style="height: 10px;"> &nbsp;</div>
              <span class="iselect"> <span id="offer_plan_sel_val">-- Please, Select your plan and offer --</span>
              <select name="offer_plan" id="offer_plan" class="required" onChange="set_order(this.value)">
                <option value="0" selected="selected">-- Please, Select your plan and offer --</option>
                <option value="1">Cox 25-25-25 Bundle</option>
                <option value="2">Cox Good Bundle</option>
                <option value="3">Cox Better Bundle</option>
                <option value="4">Cox Deluxe Bundle </option>
                <option value="5">Cox TV Economy </option>
                <option value="6">Cox Advanced TV </option>
                <option value="7">Cox Advanced TV Preferred </option>
                <option value="8">Cox Advanced TV Premier </option>
                <option value="9">Cox Digital Telephone Essential </option>
                <option value="10">Cox Digital Telephone Premier</option>
              </select>
              </span> <em style="font-size: 10px;">(You will receive your order confirmation at this email) </em> <br>
              <br>
              <br>
              <a class="bs action-submit"><span>Make request for this offer !</span></a> <br>
              <em style="font-size: 10px;">* Your contact details will be safe and not be shared. See <a href="https://www.digital-connections.tv//info/privacy/">Privacy</a></em>
            </form>
          </div>
          <div id="xtheform">
            <div align="left"><img src="<? print TEMPLATE_URL ?>images/cox_large_logo.jpg" alt="" width="487" height="362" style="margin-left: -10px;"> </div>
            <p>&nbsp;</p>
            <br>
            <br>
            <div class="Yblock" style="font-size: 18px;width:450px; background-color:none; text-align:left; font-weight:bold"> <strong>YOU CHOSE THIS PLAN: </strong><br>
              <strong class="red"><strong class="chosen_off"> </strong></strong> </div>
          </div>
          <div class="c">&nbsp;</div>
          <br>
          <br>
        </div>
      </div>
      <div class="bb" style="float:left"></div>
      <div style="width:931px; float:left; margin-top:10px; margin-bottom:40px;"> <a href="https://www.digital-connections.tv//info/affiliates/"> <img alt="" src="<? print TEMPLATE_URL ?>images/ip2.jpg"> </a> </div>
    </div>
  </div>
</div>
<? include TEMPLATE_DIR. "footer.php"; ?>
