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
  <div id="contact_form"> <span class="a"><strong style="width:360px;">Write us an e-mail</strong></span>
   
<p>  <br />Write us an e-mail and we will contact you immediately <br />
Monday - Friday 
<br />
7:00 AM - 6:00 PM CDT
  </p><br />
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
  </div>
  <!-- /#contact_form -->
  <div id="contacts_info">
    <div class="col" style="width: 246px;">
      
      <h5 class="h5">E-mail</h5>
      <p>info@exemplarhealthresources.com</p>
      <br />
      <a href="https://www.facebook.com/pages/Exemplar-Health-Resources-LLC/130372863723105" target="_blank"><img src="<? print TEMPLATE_URL ?>img/contacts_fb.jpg" alt="" /></a> </div>
    <div class="col" style="width:150px; ">
      <h5 class="h5">Phone</h5>
      

      <p>877.454.6667</p>
      
      <br />

         <h5 class="h5">Fax</h5> 
      

      <p>877.454.6667</p>
        
        
    </div>
    <div class="col" style="width: 140px">
     <!-- <h5 class="h5">Намете ни на карта</h5>
      <p><a href="http://maps.google.bg/maps?q=%D0%A1%D0%BE%D1%84%D0%B8%D1%8F,+%D0%9F%D0%B8%D0%BC%D0%B5%D0%BD+%D0%97%D0%BE%D0%B3%D1%80%D0%B0%D1%84%D1%81%D0%BA%D0%B8+14&hl=bg&ie=UTF8&sll=42.704545,23.32292&sspn=0.112776,0.22934&vpsrc=0&t=h&z=17" target="_blank"><img src="<? print TEMPLATE_URL ?>img/sf.jpg" alt="" /></a></p>
      <p><a href="#"><img src="<? print TEMPLATE_URL ?>img/bl.jpg" alt="" /></a></p>-->
    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
