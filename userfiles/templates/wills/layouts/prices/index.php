<?php

/*

type: layout

name: prices layout

description: prices site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="clear"></div>
<div class="wrapMiddle">
  <div class="wrapContent">
    <div class="contentLeft">
      <!--left box 1-->
      <div class="Box">
        <!--TreeMenu-->
        <div class="menuTreeHolder">
          <ul class="menuTree">
            <li class="title">
              <label>Prices</label>
            </li>
            <li><a href=".item1" title="" class="panel selected"><span class="leftCorner"></span><span class="rightCorner"></span>Our pricing</a></li>
          </ul>
        </div>
        <!--end TreeMenu-->
        <br/>
        <div class="leftForm">
          <form action="?">
            <div class="title">Type your details for FREE consultation</div>
            <label>Please write us an e-mail on the form below.  Thank you!</label>
            <span class="inputHolder">
            <input type="text" value = "Your Name" onclick="value=''" onblur="this.value=!this.value?'Your Name':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Your E-mail" onclick="value=''" onblur="this.value=!this.value?'Your E-mail':this.value;" name="" />
            </span> <span class="inputHolder">
            <input type="text" value = "Phone Number" onclick="value=''" onblur="this.value=!this.value?'Phone Number':this.value;" name="" />
            </span> <span class="textarea">
            <textarea></textarea>
            </span> <span class="note">* Your personal information is strictly confidential</span>
            <div class="clear"></div>
            <a href="#" title="" class="bttnHolder"> <span class="bttn">Read More</span> </a>
            <div class="clear"></div>
          </form>
        </div>
      </div>
      <!--end left box 1-->
    </div>
    <div class="contentMain">
      <div class="prices">
      
       <editable  rel="page" field="content_body">
        <h1>Our pricing</h1>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
        <div class="pricesTable">
          <div class="pricesTop">
            <div class="title">Our Pricing list</div>
          </div>
          <div class="pricesMiddle">
            <div class="item">We charge for a single will.</div>
            <div class="item itemSecond">If it is 2 mirror wills the price is</div>
            <div class="item">Lasting Power of Attorney</div>
            <div class="item itemSecond">Will Storage</div>
            <div class="item">Online asset location and inventory service</div>
            <div class="item itemSecond">Extra planning and consultancy work will be charged at </div>
          </div>
          <div class="pricesBottom"></div>
          <div class="priceList">
            <div class="item">&pound;100</div>
            <div class="item">&pound;180</div>
            <div class="item">&pound;150</div>
            <div class="item">FREE</div>
            <div class="item">FREE</div>
            <div class="item">&pound;100 / per hour</div>
          </div>
        </div>
        
        </editable>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
