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
  <div class="container edit">

    <h2 class="section-title"><hr class="left"><span class="edit" field="title" rel="page">Page title</span><hr class="right"></h2>

    <div class="element thumbnail" style="height:350px;">
        <module type="google_maps" class="autoscale" />
    </div>

    <div class="element edit page-post-content" field="content" rel="page">Content will appear here.</div>
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
        </div>
    </div>

  </div>

 </div>

<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
