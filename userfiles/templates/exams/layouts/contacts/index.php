0<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="wide_stripe"></div>
<editable  rel="page" field="content_body">
  <div class="content_center container_holder" style="padding-top:20px;" >
    <h1>Welcome to our bookstore</h1>
    <p> <br />
      This little book is deceptive in its brevity for it contains powerful instructions for the achievement of personal mastery. Put it at the top of your shopping list! Follow its simple instructions and experience</p>
    <br />
    <br /> 
  </div>
  <div class="home_center_wide">
    <div class="home_center_mid container_holder">
      <div class="container">
        <div class="row">
          <div class="col" style="width:648px;">
            <h1>Send us a message</h1>
            <p>You can use the form bellow to send us a message </p>
            <microweber module="forms/mail_form" />
          </div>
          <div class="col" style="width:310px; padding-left:5px;">
            <div class="container">
              <h1>Find us</h1>
              <microweber module="mics/google_map" />
              
            </div>
            <div class="container">
      <br /><br />
       London, UK
<hr /><br /> 
121-141 Westbourne Terrace <br />
Paddington, London W2 6JR <br />
44-207-563-3800 Phone <br />
44-207-563-3801 Fax <br />
<br />
<br />


<br /><br />
      Contact
<hr /><br />
Sonny Makhijani<br />
 CEO
<br />
Sonny.Makhijani@kingspubliser.com
<br />
Tel +44.207.563.3871

<br />
<br />

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</editable>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
