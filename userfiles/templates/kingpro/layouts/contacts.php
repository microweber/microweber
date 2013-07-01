<?php

/*

type: layout
content_type: static
name: Contact Us

description: Contact us layout

*/


?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>


	<div class="container">
        <div class="edit">
            <div class="mw-empty"></div>
        </div>
		<h2 class="edit element section-title">
        	<div class="mw-row">
    			<div class="mw-col" style="width:40%">
    				<div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
    			</div>
    			<div class="mw-col" style="width:20%">
    				<div class="mw-col-container"><h2 align="center" class="edit element" field="title" rel="content">Page Title</h2></div>
    			</div>
    			<div class="mw-col" style="width:40%">
    				<div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
    			</div>
    		</div>
        </h2>
  <div class="edit" field="content" rel="page">

    <div class="row">
      <div class="span6">
        <module type="google_maps" />


        <div class="edit">
        <div class="vspace"></div>
          <h3>Address</h3>
          <hr>
          <p>
          	10 "Professor Georgi Zlatarski" , bl. B, fl. 3,<br />
          	Sofia 1700,<br />
          	Bulgaria
          </p>
          <ul class="contact-list">
            <li>
            	<em class="icon-phone"></em> <span>Phone: +1 (310) 123 4567<br /></span>
            </li>
            <li><em class="icon-envelope"></em><span>help@microweber.com</span></li>
          </ul>
        </div>
        <h3>Follow Us</h3>
        <hr>
        <div class="social-icons">
        	<a href="http://www.facebook.com/microweber">&nbsp;<span class="icon-facebook icon-4">&nbsp;</span></a>
			<a href="http://www.twitter.com/microweber">&nbsp;<span class="icon-twitter">&nbsp;</span></a>
			<a href="http://plus.google.com">&nbsp;<span class="icon-google-plus">&nbsp;</span></a>
			<a href="http://youtube.com">&nbsp;<span class="icon-youtube">&nbsp;</span></a><br>
		</div>

      </div>
      <div class="span5 offset1">
        <module type="contact_form"   class="contact-form" id="contact-form" template="basic" />

      </div>
    </div>
  </div>
	</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
