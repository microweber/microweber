<?php

/*

type: layout

name: contacts layout

description: contacts site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print TEMPLATE_URL ?>img/mapi.jpg" /> </div>
</div>
<!-- /#shadow -->
<div id="main">
  <div id="contact_form"> <span class="a"><strong style="width:360px;">Напишете ни е-мейл</strong></span>
    <p> Напишете ни е-мейл и ние ще се свържем с вас!<br />
      Всеки работен ден от 08:00 до 20:00 h </p>
    <form method="post" action="#" id="the_contact_form">
      <div class="field">
        <input type="text" class="required" id="name" name="name" default="Вашето име" />
        <span class="err">Моля въведете вашето име</span> </div>
      <div class="field">
        <input type="text" class="required" default="Вашият E-mail" name="email" id="email" />
        <span class="err">Моля въведете E-mail</span> </div>
      <div class="field">
        <input type="text" default="Телефон" id="cphone" name="phone" />
      </div>
      <div class="area">
        <textarea default="Съобщение" class="required" id="message" name="message"></textarea>
        <span class="err">Моля въведете съобщение</span> </div>
      <input type="submit" class="xhidden" />
      <a href="#" class="a action-submit text-centered right"><strong style="width:160px;">Изпрати</strong></a>
    </form>
  </div>
  <!-- /#contact_form -->
  <div id="contacts_info">
    <div class="col" style="width: 246px;">
      <h5 class="h5">къде се намираме?</h5>
      <p>София, България<br />
        ул. "Св. Пимен Зографски" 14</p>
      <p>Благоевград, България </p>
      <br />
      <h5 class="h5">E-mail</h5>
      <p>info@dentalencentar.com</p>
      <br />
      <a href="https://www.facebook.com/pages/Дентален-Център-3М/241127939263153" target="_blank"><img src="<? print TEMPLATE_URL ?>img/contacts_fb.jpg" alt="" /></a> </div>
    <div class="col" style="width:150px; ">
      <h5 class="h5">Телефони</h5>
      София:<br />

      <p>+359 879 991 215,<br />
        +359 2 962 60 26</p>
        <br />
         Благоевград:
        <br />
       
        
      <p> +359 73 83 38 88
         </p>
    </div>
    <div class="col" style="width: 140px">
      <h5 class="h5">Намете ни на карта</h5>
      <p><a href="http://maps.google.bg/maps?q=%D0%A1%D0%BE%D1%84%D0%B8%D1%8F,+%D0%9F%D0%B8%D0%BC%D0%B5%D0%BD+%D0%97%D0%BE%D0%B3%D1%80%D0%B0%D1%84%D1%81%D0%BA%D0%B8+14&hl=bg&ie=UTF8&sll=42.704545,23.32292&sspn=0.112776,0.22934&vpsrc=0&t=h&z=17" target="_blank"><img src="<? print TEMPLATE_URL ?>img/sf.jpg" alt="" /></a></p>
      <p><a href="#"><img src="<? print TEMPLATE_URL ?>img/bl.jpg" alt="" /></a></p>
    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
