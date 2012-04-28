<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>



<div class="body_part_inner">
      <div class="contatct_left">
        <div class="contact_exemp_logo"><img src="<? print TEMPLATE_URL ?>images/contactexemp_logo.jpg" alt="logo" width="365" height="114" /></div>
        <div class="mail_icon"></div>
        <div class="contact_writeus">Write us an e-mail</div>
        <input type="text" class="contact_textbox" value="Your Name" />
        <input type="text" class="contact_textbox" value="Your E-mail" />
        <input type="text" class="contact_textbox" value="Your Phone" />
        <select class="contact_drop">
          <option>Select About?</option>
        </select>
        <div class="contact_drop_close"></div>
        <textarea name="" cols="" rows="" class="contact_message">Message
</textarea>
        <div class="contact_send_but">
          <input type="image" src="<? print TEMPLATE_URL ?>images/contact_send_but.jpg" />
        </div>
      </div>
      <div class="contact_right">
        <div class="contact_moreinfo"> More information </div>
        <div class="address_phones">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><div class="contacts_address"> <span class="contact_blue">WHERE TO FIND US</span> <br />
                  <br />
                  USA, Dallas<br />
                  Fort Worth metroplex</div></td>
              <td><div class="contacts_phones" ><span class="contact_blue">PHONES</span><br />
                  <br />
                  877.454.6667<br />
                  <br />
                  <span class="contact_blue">FAX</span><br />
                  <br />
                  877.454.6667 </div></td>
            </tr>
          </table>
        </div>
        <div class="contact_email"> <span class="contact_blue">EMAIL</span><br />
          <br />
          <a href="mailto:Info@jobs.exemplarhealthresources.com">Info (at) jobs.exemplarhealthresources.com</a><br />
          <span class="contact_blue"><br />
          FIND US ON THE MAP</span> </div>
        <div class="contact_clickhere_but"><img src="<? print TEMPLATE_URL ?>images/contact_clickhere_but.jpg" alt="find us on the map" /></div>
      </div>
    </div>

<? include   TEMPLATE_DIR.  "footer.php"; ?>
