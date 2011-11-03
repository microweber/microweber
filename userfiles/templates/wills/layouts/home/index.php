<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="clear"></div>
<div class="wrapVideo">
  <div class="wrapContent">
    <div class="videoHolder">
      <div class="leftColumn">
         <editable  rel="page" field="content_body">
        <h1>What Is Global Wills?</h1>
        <p>We can prepare a will for you that sets your affairs in order, giving you and your loved ones peace of mind.</p>
        <p>Having a will is something that every adult should have, it does not matter if you are in the prime of health, 25 years old and on your way to be 110. It is a basic part of caring about those you love. Procrastinating and evading this responsibility imposes a cost that is borne today, not just after you have passed.</p>
        <a href="" title="" class="bttnHolder"><span class="bttn">Read More</span></a></editable> </div>
      <div class="rightColumn"> <img src="<? print TEMPLATE_URL ?>images/video.jpg" alt="" /> </div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div class="wrapMiddle">
  <div class="wrapContent home">
    <div class="contentMain">
      <div class="container">
        <div class="leftColumn">
          <div class="titleHolder">
          <editable  rel="page" field="custom_field_home_sub">
            <h1>Risk of not having a will</h1>
            <ul>
              <li> <span class="number">1</span> <span class="text">Your children have the potential of ending up in care if guardians are not named in a Will.</span> </li>
              <li> <span class="number">2</span> <span class="text">You estate will be divided according to the applicable laws of intestacy</span> </li>
              <li> <span class="number">3</span> <span class="text">Your loved ones may not receive your assets in the order you wish for.</span> </li>
              <li> <span class="number">4</span> <span class="text">Correct planning can reduce the tax liabilities of your estate</span> </li>
              <li> <span class="number">5</span> <span class="text">Your foreign assets will not be covered and may be subject to  foreign rules</span> </li>
              <li> <span class="number">6</span> <span class="text">Your estate can suffer much higher legal costs</span> </li>
              <li> <span class="number">7</span> <span class="text">Distributions to beneficiaries may be delayed by years</span> </li>
            </ul>
            <a href="#" title="" class="bttnHolder"> <span class="bttn">Read More</span> </a>
            </editable>
            <div class="clear"></div>
          </div>
        </div>
        <div class="rightColumn">
          <h2>Type your details for</h2>
          <h1>FREE consultation</h1>
          <label> Tell us your story and we will take care for your needs.  Please write us an e-mail on the form below.  Thank you </label>
          <form action="?">
            <span class="inputHolder">
            <input type="text" value = "Your Name" onclick="value=''" onblur="this.value=!this.value?'Your Name':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Your E-mail" onclick="value=''" onblur="this.value=!this.value?'Your E-mail':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Phone Number" onclick="value=''" onblur="this.value=!this.value?'Phone Number':this.value;" name="" />
            </span> <span class="textarea">
            <textarea></textarea>
            </span> <a href="#" title="" class="bttnHolder"> <span class="bttn">Read More</span> </a> <span class="note">* Your personal information is strictly confidential</span>
          </form>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
