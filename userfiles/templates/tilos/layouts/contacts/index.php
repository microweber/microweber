<?php

/*

type: layout

name: contacts layout

description: contacts site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? include TEMPLATE_DIR."sidebar.php"; ?>

<div id="main">
  <h2 class="title">
    <editable  rel="page" field="content_title">Contacts </editable>
  </h2>
  <br />
  <microweber module="forms/mail_form"  />
</div>
<div class="more_contacts">
  <editable  rel="page" field="custom_field_content_title2">
    <h3 class="title nopadding">More contacts</h3>
  </editable>
  <br />
  <br />
  <table cellpadding="0" cellspacing="0">
    <tr>
      <td><img src="<? print TEMPLATE_URL ?>img/contact_ico1.jpg" alt="" /></td>
      <td><editable  rel="page" field="custom_field_contact_us_address"> Tilos, Inc.<br />
          20822 Currier Road<br />
          City of Industry, CA 91789<br />
          USA<br />
        </editable></td>
    </tr>
    <tr>
      <td><img src="<? print TEMPLATE_URL ?>img/contact_ico2.jpg" alt="" /></td>
      <td><editable  rel="page" field="custom_field_contact_us1"> Tilos Dealer Question/Support <br />
          <a href="mailto:sale@tilos.com"><strong>sales@tilos.com</strong> </a> <br />
          <br />
          General Public Question/Support<br />
          <a href="mailto:info@tilos.com"><strong>info@tilos.com</strong></a> </editable></td>
    </tr>
    <tr>
      <td><img style="top: -5px" src="<? print TEMPLATE_URL ?>img/contact_ico3.jpg" alt="" /></td>
      <td><editable  rel="page" field="custom_field_contact_us2"> Tel: +1-909-348-0130<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+1-800-475-5703 Toll Free in US<br />
          Fax:&nbsp;+1-909-348-0134 </editable></td>
    </tr>
  </table>
</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
