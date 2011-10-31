<?php

/*

type: layout

name: contact

description: contact site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="content_wrapper">
  <!-- sidebar -->
  
  <? include TEMPLATE_DIR. "sidebar_services.php"; ?>

  <!-- /sidebar -->
  <!-- content -->
  <div id="content">
    <h3>Talk to us</h3>
    <h6>IMS company currently serves clients from a myriad of industries, link build service, providing Virtual assistant for seo, website management, seo services ... </h6>
    <br />
    <p>In 2009, IMS company was established to provide outsource services for individuals & companies from all around the globe. With our HQ located in VietNam and Staff Hubs located in Vietnam, IMS company has all the advantages and abilities to serve you the best services with lowest costs. If you are looking for a reliable long-term partner in the areas of offshore outsourcing, IMS company is one of your best choices.</p>
    <!-- contact form -->
    <div id="formfeedback"></div>
    <form id="contact-form"  method="post" name="contact-form">
      <label>Name:</label>
      <input type="text" name="name" class="contact-input" id=
        "name" value="" />
      <label>Email:</label>
      <input type="text" name="email" class=
        "contact-input" id="email" value="" />
      <label>Message:</label>
      <textarea name="message" cols="" rows="" class="contact-textarea" id="message">
</textarea>
      <br />
      <input align="right" name="submit" type="submit" class="contact-submit" id=
        "submit" value="" />
    </form>
    <!-- /contact form -->
  </div>
  <!-- /content -->
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
