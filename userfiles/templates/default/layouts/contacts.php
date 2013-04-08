<?php

/*

type: layout
content_type: static
name: Contacts

description: Contact us layout

*/


?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="container edit" field="content" rel="page">
    <h2 class="section-title">
      <hr class="left">
      <span class="edit" field="title" rel="page">Page title</span>
      <hr class="right">
    </h2>
    <module type="google_maps" />
    <div class="element page-post-content">Content goes here.</div>
    <hr>
    <div class="row">
      <div class="span6">
        <module type="contact_form" template="inline" class="contact-form" id="contact-form" />
      </div>
      <div class="span5 offset1">
        <div class="edit">
          <h3>Address</h3>
          <p>2301 Nam egestas congue eleifend. Nulla tincidunt lobortis risus nec luctus. </p>
          <ul class="contact-list">
            <li><span class="contact-icon phone"></span><span>1 817 274 2933</span></li>
            <li><span class="contact-icon mail"></span><span>help@microweber.com</span></li>
          </ul>
        </div>
        <hr>
        <div class="social-icons"> <a href="#"><i class="social tw"></i></a> <a href="#"><i class="social fb"></i></a> <a href="#"><i class="social flickr"></i></a> <a href="#"><i class="social in"></i></a> <a href="#"><i class="social gp"></i></a> <a href="#"><i class="social pin"></i></a> <a href="#"><i class="social tumblr"></i></a> <br>
          <a href="#"><i class="social wp"></i></a> <a href="#"><i class="social yt"></i></a> <a href="#"><i class="social vim"></i></a> <a href="#"><i class="social picasa"></i></a> <a href="#"><i class="social forrst"></i></a> <a href="#"><i class="social rss"></i></a> <a href="#"><i class="social myspace"></i></a> </div>
      </div>
    </div>
  </div>
</div>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
