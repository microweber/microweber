<?php

/*

type: layout

name: contacts layout

description: contacts site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $th = get_pictures(PAGE_ID, $for = 'page');
//p($th);
$pic = $th[0] ["urls"]["original"];



if($pic == false){
	
	$par_page = get_page(PAGE_ID);
	if(!empty($par_page )){
		$th = get_pictures($par_page['content_parent'], $for = 'page');
		$pic = $th[0] ["urls"]["original"];
	}
}

if($pic == false){
$pic = 	  TEMPLATE_URL ."img/banner_contact_us2.jpg";
}
//p($th);
?>

<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print  $pic ?>" /> </div>
</div>
<!-- /#shadow -->
<div id="main">
  <div id="contact_form"> 
  
    <editable  rel="page" field="custom_field_form">
  <span class="a"><strong style="width:360px;">Write us an e-mail</strong></span>
    <p> <br />
      Tell us about yourself, and one of our associates will contact you. <br />
      <br />
      Monday - Friday <br />
      7:00 AM - 6:00 PM CDT </p>
    <br />
    <form method="post" action="#" id="the_contact_form">
      <div class="field">
        <input type="text" class="required" id="name" name="name" default="Your name" />
        <span class="err">Please enter your name</span> </div>
      <div class="field">
        <input type="text" class="required" default="Your E-mail" name="email" id="email" />
        <span class="err">Please enter your E-mail</span> </div>
      <div class="field">
        <input type="text" default="Phone" id="cphone" name="phone" />
      </div>
      <div class="area">
        <textarea default="Message" class="required" id="message" name="message"></textarea>
        <span class="err">Please enter your message</span> </div>
      <input type="submit" class="xhidden" />
      <a href="#" class="a action-submit text-centered right"><strong style="width:160px;">Send</strong></a>
    </form>
    
    </editable>
  </div>
  <!-- /#contact_form -->
  <div id="contacts_info">
    <div class="col" style="width: 246px;">
      <h5 class="h5">E-mail</h5>
      <p>info@microweber.com</p>
      <br />
      <a href="https://www.facebook.com/Microweber" target="_blank"><img src="<? print TEMPLATE_URL ?>img/contacts_fb.jpg" alt="" /></a> </div>
    <div class="col" style="width:150px; ">
      <h5 class="h5">Phone</h5>
      <p>123456789</p>
      <br />
      <h5 class="h5">Fax</h5>
      <p>123456789</p>
    </div>
    <div class="col" style="width: 140px">
   
    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
