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
        <label>
        <editable  rel="page" field="custom_field_subscribe_form_help" page_id="<? print PAGE_ID ?>"> Write us an e-mail and we will contact you immediatly <br/>
          Every working day from 09:00 to 19:00</editable>
        
        </label>
        <form action="?" id="mail_form_contact_us">
          <span class="inputHolder">
          <input type="text" value = "Your Name" onclick="value=''" onblur="this.value=!this.value?'Your Name':this.value;" name="name" />
          </span> <span class="inputHolder">
          <input type="text" value = "Your E-mail" onclick="value=''" onblur="this.value=!this.value?'Your E-mail':this.value;" name="email" />
          </span> <span class="inputHolder">
          <input type="text" value = "Phone Number" onclick="value=''" onblur="this.value=!this.value?'Phone Number':this.value;" name="phone" />
          </span> <span class="textarea">
 <textarea name="message" ></textarea>
          </span> <a  href='javascript:send_m("#mail_form_contact_us")'  title="" class="bttnHolder"> <span class="bttn">Send</span> </a> <span class="note">* Your personal information is strictly confidential</span>
        </form>
      </div>
      <div class="column2">
        <div class="titleHolder">
          <div class="title">Where To find us</div>
        </div>
        <div class="holder">
        <editable  rel="page" field="custom_field_addr" page_id="<? print PAGE_ID ?>"> 
        
      <label>Global Wills LLP</label>
Warwick Technology Park<br/>
Gallows Hill<br/>
Warwick<br/>
CV34 6UW<br/>
United Kingdom<br/>


        
           
          <h3>E-MAIL</h3>
          info@globalwills.com
          
          
          
          
          
          <br/>
          <div class="fanHolder">
            <label>Become a fan in</label>
            <a target="_blank" href="https://www.facebook.com/pages/Global-Wills-LLP/163344917097225" class="facebook"></a> </div>
          <div class="fanHolder">
            <label>Follow Us In Twitter</label>
            <a class="twitter" href="http://twitter.com/#!/globalwills" target="_blank"></a> </div>
          
            </editable>
            
            
        </div>
      </div>
      <div class="column3">
        <div class="titleHolder">
          <div class="title">Phone</div>
        </div>
        <div class="holder">
        <editable  rel="page" field="custom_field_phone_map" page_id="<? print PAGE_ID ?>">  
           <label>+44 203 287 2037</label>
          <h3>Find us on the map</h3>
          <a href="http://maps.google.bg/maps?q=Warwick+CV34+6UW+United+Kingdom&ie=UTF8&oe=utf-8&client=firefox-a&hnear=%D0%A3%D0%BE%D1%80%D1%83%D0%B8%D0%BA+CV34+6UW,+%D0%92%D0%B5%D0%BB%D0%B8%D0%BA%D0%BE%D0%B1%D1%80%D0%B8%D1%82%D0%B0%D0%BD%D0%B8%D1%8F&gl=bg&t=h&z=16&vpsrc=0" target="_blank" title="" class="bttnHolder"> <span class="bttn">click here</span> </a> <a href="" class="logoContacts" title=""> <img src="<? print TEMPLATE_URL ?>images/logo-contacts.png" alt="" /> </a> 
          
          </editable>
          </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
