<?php

/*

type: layout

name: contacts layout

description: contacts site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="wrapMiddle">
  <div class="wrapContent contacts">
    <div class="contentMain">
      <div class="column1">
        <div class="titleHolder">
          <div class="title">Write us an e-mail</div>
          <div class="clear"></div>
        </div>
        <label> Write us an e-mail and we will contact you immediatly <br/>
          Every working day from 09:00 to 19:00</label>
        <form action="?">
          <span class="inputHolder">
          <input type="text" value = "Your Name" onclick="value=''" onblur="this.value=!this.value?'Your Name':this.value;" name="" />
          </span> <span class="inputHolder">
          <input type="text" value = "Your E-mail" onclick="value=''" onblur="this.value=!this.value?'Your E-mail':this.value;" name="" />
          </span> <span class="inputHolder">
          <input type="text" value = "Phone Number" onclick="value=''" onblur="this.value=!this.value?'Phone Number':this.value;" name="" />
          </span> <span class="textarea">
          <textarea></textarea>
          </span> <a href="#" title="" class="bttnHolder"> <span class="bttn">send</span> </a> <span class="note">* Your personal information is strictly confidential</span>
        </form>
      </div>
      <div class="column2">
        <div class="titleHolder">
          <div class="title">Where To find us</div>
        </div>
        <div class="holder">
          <label>United Kingdom<br/>
            London</label>
          <h3>E-MAIL</h3>
          <br/>
          <div class="fanHolder">
            <label>Become a fan in</label>
            <span class="facebook"></span> </div>
          <div class="fanHolder">
            <label>Follow Us In Twitter</label>
            <span class="twitter"></span> </div>
        </div>
      </div>
      <div class="column3">
        <div class="titleHolder">
          <div class="title">Phones</div>
        </div>
        <div class="holder">
          <label>0045 283.982.84</label>
          <h3>FAX</h3>
          <label>0045 283.982.84</label>
          <h3>Find us on the map</h3>
          <a href="#" title="" class="bttnHolder"> <span class="bttn">click here</span> </a> <a href="" class="logoContacts" title=""> <img src="<? print TEMPLATE_URL ?>images/logo-contacts.png" alt="" /> </a> </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
