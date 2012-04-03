<?php

/*

type: layout

name: layout

description: site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="direction">
  <div class="wrapper">
    <div id="post-40" class="post-40 page type-page status-publish hentry"> <br />
      <br />
      <img src="<? print TEMPLATE_URL ?>/images/directv_top_text.png"  />
      <div class="c" style="height: 20px;">&nbsp;</div>
      <div class="b btrans">
        <div class="bt">&nbsp;</div>
        <div class="bm"> <img style="float:left;margin:-10px 0 0 -20px" src="<? print TEMPLATE_URL ?>/images/dtv_banner_top.png"  />
          <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
          <img src="<? print TEMPLATE_URL ?>/images/bsf.jpg" />
          <div class="c" style="padding-bottom: 17px;">&nbsp;</div>
          <strong>DIRECTV for BUSINESS™ </strong> <br />
          When your business needs a high quality TV experience
          <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
          <div class="quote offer_ctrl_1 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot1.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Bars & Restaurants</strong> </p>
            <p>Make your establishment the go-to place for the best sports.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(1)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="quote offer_ctrl_2 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot2.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Offices</strong> </p>
            <p>Give your employees access to news that shape the industry.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(2)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="quote offer_ctrl_3 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot3.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Shops, Gyms & Lobbies.</strong> </p>
            <p>Keep your customers entertained while they shop, wait or work out.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(3)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="quote offer_ctrl_4 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot4.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Hotels</strong> </p>
            <p>Fill every room every night by providing the best TV experience.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(4)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="quote offer_ctrl_5 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot5.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Dorms</strong> </p>
            <p>Create a dorm life your students want with DIRECTV.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(5)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="quote offer_ctrl_6 offer_ctrl"> <br />
            <p><img src="<? print TEMPLATE_URL ?>/images/quot6.jpg" /></p>
            <p><strong style="color: #00A5E1;font-size: 14px;">Hospitals</strong> </p>
            <p>Lift your patients' spirits and make their stay more enjoyable.</p>
            <p>Call <strong>1-866-Instals (467-8257)</strong> </p>
            <p>or</p>
            <a href="javascript:set_order(6)" class="rbtn" style="margin: 0 auto"><strong>Send us a request</strong></a> </div>
          <div class="c">&nbsp;</div>
          <img src="<? print TEMPLATE_URL ?>/images/piis.jpg" /> <br />
          <br />
          <div class="c">&nbsp;</div>
          <div id="theform"> <br />
            <br />
            <form method="post" action="#" class="TheForm" id="request_1">
              <input type="text"  name="offer_name"  id="offer_name"   />
              <span class="field"> <span>
              <input type="text" class="required" name="first_name" default="First Name" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="last_name" default="Last Name" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="street_address" default="Street Address" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="zip_postal_code" default="Zip/Postal Code" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="home_phone" default="Home phone" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="mobile_phone" default="Mobile phone" />
              </span> </span> <span class="field"> <span>
              <input type="text" class="required" name="email" default="E-mail" />
              </span> </span> <span class="field" style="margin-bottom: 0"> <span>
              <input type="text" class="required" name="confirm_email" default="Confirm E-mail " />
              </span> </span>
              <div class="c" style="height: 10px;"> &nbsp;</div>
              <span class="iselect"> <span id="offer_plan_sel_val"></span>
              <select name="offer_plan" id="offer_plan" class="required" onchange="set_order(this.value)">
                <option value="0" selected="selected">-- Please, Select your plan and offer --</option>
                <option value="1">Bars & Restaurants</option>
                <option value="2">Offices</option>
                <option value="3">Shops, Gyms & Lobbies.</option>
                <option value="4">Hotels</option>
                <option value="5" >Dorms</option>
                <option value="6" >Hospitals</option>
              </select>
              </span> <em style="font-size: 10px;">(You will receive your order confirmation at this email) </em> <br />
              <br />
              <a class="bs action-submit"><span>Make request for this offer !</span></a> <br />
              <em style="font-size: 10px;">* Your contact details will be safe and not be shared. See <a href="<?php echo (108); ?>">Privacy</a></em>
            </form>
          </div>
          <div id="xtheform"> <img style="margin-left: -10px" src="<? print TEMPLATE_URL ?>/images/dtfbS.jpg" alt="" />
            <p>Limited time offers! The promotional prices are available for a limited time only. </p>
            <br />
            <br />
            <div class="Yblock" style="font-size: 15px;width:450px"> <strong>YOU CHOSE THIS PLAN: </strong> <br />
              <br />
              <strong class="red"><strong class="chosen_off"> </strong></strong> </div>
          </div>
          <div class="c">&nbsp;</div>
          <br />
          <br />
        </div>
        <div class="bb">&nbsp;</div>
      </div>
    </div>
    <br />
    <a href="<?php echo (65); ?>"> <img alt="" src="<? print TEMPLATE_URL ?>/images/ip2.jpg"> </a> <br />
    <br />
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
